<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FundAccount;
use App\Models\SellingInvoice;
use App\Models\Shift;
use App\Traits\PaymentTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SellingInvoiceController extends Controller{

    use PaymentTrait;
    
    public function __construct(){
        $this->middleware('permission:view_selling_invoices')->only('index');
        $this->middleware('permission:add_selling_invoice')->only('create');
        $this->middleware('permission:view_selling_invoice')->only('show');
        $this->middleware('permission:return_selling_invoice')->only('edit');
        $this->middleware('permission:print_selling_invoice')->only('print');
        $this->middleware('permission:repayment_selling_invoice')->only('repayment');
        $this->middleware('permission:repayment_for_customer_selling_invoice')->only('repayment_for_customer');
        $this->middleware('permission:payment_fund_selling_invoice')->only('payment_fund');
        $this->middleware('permission:delivery_selling_invoice')->only('delivery');
    }

    public function index(Request $request){
        $customers = Customer::where('status', 'مفعل')->orderBy('name', 'asc')->get(['name','id']);
        $shiftIds = Shift::orderBy('id', 'desc')->take(5)->pluck('id');
        $query = SellingInvoice::query();

        if ($request->filled('invoice_date')) {
            $query->where('invoice_date', $request->invoice_date);
        }

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', $request->invoice_number);
        }

        if ($request->filled('is_returned')) {
            $query->where('is_returned', $request->is_returned);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('invoice_date', [$request->start_date, $request->end_date]);
        }

        if (!$request->all()) {
            $query->whereIn('shift_id', $shiftIds);
        }

        // Get the paginated results
        $invoices = $query->orderBy('created_at', 'desc')->get()->take(50);
        return view('pages.sellingInvoices.index', compact('invoices', 'customers'));
    }
    
    public function create(){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            return view('pages.sellingInvoices.create');
        }else{
            toastr()->warning('يجب بدء وردية جديدة أولا!');
            return redirect()->intended(route('shifts.index'));
        }
    }

    public function store(Request $request){
        //
    }

    public function show(SellingInvoice $sellingInvoice){
        $invoice = SellingInvoice::find($sellingInvoice->id);
        if($invoice){
            return view('pages.sellingInvoices.show', compact('invoice'));
        }else{
            toastr()->error('الفاتورة غير موجودة');
            return redirect()->back();
        }
    }

    public function print(SellingInvoice $sellingInvoice, Request $request){
        $invoice = SellingInvoice::find($sellingInvoice->id);
        if($invoice){
            $returned = $request->query('returned', false);
            return view('pages.sellingInvoices.print', compact('invoice', 'returned'));
        }else{
            toastr()->error('الفاتورة غير موجودة');
            return redirect()->back();
        }
    }

    public function repayment(Request $request, SellingInvoice $sellingInvoice){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            if($sellingInvoice){
                try {
                    DB::beginTransaction();
                    if($sellingInvoice->payment_fund_status == 'معلق'){
                        toastr()->warning('يجب تأكيد حالة استلام المبلغ المدفوع اولا');
                        return redirect()->back();
                    }else{
                        if($sellingInvoice->payment_status != 'تم الدفع' || $sellingInvoice->remaining > 0){
                            // paid this invoice
                            $amount = $request->paid;
                            $remaining = $sellingInvoice->remaining;
                            $paid_for_this_invoice = $amount <= $sellingInvoice->remaining ? $amount : $sellingInvoice->remaining;

                            $sellingInvoice->customer->account += $paid_for_this_invoice;
                            $sellingInvoice->customer->save();
                            
                            $sellingInvoice->paid += $paid_for_this_invoice;
                            $sellingInvoice->remaining -= $paid_for_this_invoice;
                            $sellingInvoice->account_after += $paid_for_this_invoice;
                            $sellingInvoice->save();
                            if($sellingInvoice->remaining == 0){
                                $sellingInvoice->payment_status = 'تم الدفع';
                                $sellingInvoice->save();
                            }

                            // paid the rest of the invoices
                            if($request->paid - $paid_for_this_invoice > 0){
                                $sellingInvoice->over_paid = $request->paid - $paid_for_this_invoice;
                                $sellingInvoice->save();
                                $this->applyCustomerExcessPayment($sellingInvoice->customer, $request->paid - $paid_for_this_invoice);
                            }
                            
                            $this->addPayment(
                                'قبض',
                                $currentShift->id,
                                $sellingInvoice->customer_id,
                                null,
                                auth()->user()->id,
                                $sellingInvoice->id,
                                $request->paid,
                            );
                        
                        logActivity(' قام الموظف بسداد مديونية فاتورة بيع رقم '.$sellingInvoice->invoice_number, 'الفواتير');
                        DB::commit();
                        
                        toastr()->success('تم سداد مديونية هذة الفاتورة بنجاح');
                        return redirect()->back();
                    }else{
                        toastr()->error('لا يوجد مديونية علي هذة الفاتورة');
                        return redirect()->back();
                    }
                }
            } catch (\Exception $e) {
                    DB::rollBack();
                    toastr()->error($e->getMessage());
                    return redirect()->back();
                }
            }else{
                toastr()->error('الفاتورة غير موجودة');
                return redirect()->back();
            }
        }else{
            toastr()->warning('يجب بدء وردية جديدة أولا!');
            return redirect()->intended(route('shifts.index'));
        }
    }

    public function delivery(Request $request, SellingInvoice $sellingInvoice){
        if($sellingInvoice){
            try {
                if($sellingInvoice->delivery_status != 'تم التوصيل'){
                    DB::beginTransaction();
                    $sellingInvoice->delivery_status = 'تم التوصيل';
                    $sellingInvoice->save();
                    logActivity(' قام الموظف بتعديل حالة توصيل فاتورة بيع رقم '.$sellingInvoice->invoice_number, 'الفواتير');
                    DB::commit();
                    toastr()->success('تم تعديل حالة توصيل هذة الفاتورة بنجاح');
                    return redirect()->back();
                }else{
                    toastr()->error('تم توصيل هذة الفاتورة بالفعل');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back();
            }
        }else{
            toastr()->error('الفاتورة غير موجودة');
            return redirect()->back();
        }
    }

    public function payment_fund(Request $request, SellingInvoice $sellingInvoice){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            if($sellingInvoice){
                try {
                    if($sellingInvoice->payment_fund_status != 'تم الاستلام'){
                        DB::beginTransaction();
                        
                        $sellingInvoice->payment_fund_status = 'تم الاستلام';
                        $sellingInvoice->save();
    
                        $this->addPayment(
                            'بيع',
                            $currentShift->id,
                            $sellingInvoice->customer_id,
                            null,
                            auth()->user()->id,
                            $sellingInvoice->id,
                            $sellingInvoice->paid,
                        );
    
                        logActivity(' قام الموظف بتعديل حالة استلام مبلغ فاتورة بيع رقم '.$sellingInvoice->invoice_number, 'الفواتير');
                        DB::commit();
                        toastr()->success('تم تعديل حالة استلام مبلغ هذة الفاتورة بنجاح');
                        return redirect()->back();
                    }else{
                        toastr()->error('تم استلام مبلغ هذة الفاتورة بالفعل');
                        return redirect()->back();
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    toastr()->error($e->getMessage());
                    return redirect()->back();
                }
            }else{
                toastr()->error('الفاتورة غير موجودة');
                return redirect()->back();
            }
        }else{
            toastr()->warning('يجب بدء وردية جديدة أولا!');
            return redirect()->intended(route('shifts.index'));
        }
    }

    public function edit(SellingInvoice $sellingInvoice){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            if($sellingInvoice->payment_fund_status == 'معلق'){
                toastr()->warning('يجب تأكيد حالة استلام المبلغ المدفوع اولا');
                return redirect()->back();
            }else{
                if(Carbon::parse($sellingInvoice->invoice_date)->diffInDays(Carbon::now()) < 5){
                    return view('pages.sellingInvoices.edit', compact('sellingInvoice'));
                }else{
                    toastr()->warning('لقد مر علي انشاء الفاتورة 5 أيام, لا يسمح باسترجاعها الأن');
                    return redirect()->back();
                }
            }
        }else{
            toastr()->warning('يجب بدء وردية جديدة أولا!');
            return redirect()->intended(route('shifts.index'));
        }
    }

    public function update(Request $request, SellingInvoice $sellingInvoice){
        //
    }

    public function destroy(SellingInvoice $sellingInvoice){
        //
    }
}
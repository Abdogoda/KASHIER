<?php

namespace App\Http\Controllers;

use App\Models\PurchasingInvoice;
use App\Models\Shift;
use App\Models\Supplier;
use App\Traits\PaymentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PurchasingInvoiceController extends Controller{

    use PaymentTrait;

    public function __construct(){
        $this->middleware('permission:view_purchasing_invoices')->only('index');
        $this->middleware('permission:add_purchasing_invoice')->only('create');
        $this->middleware('permission:view_purchasing_invoice')->only('show');
        $this->middleware('permission:return_purchasing_invoice')->only('edit');
        $this->middleware('permission:repayment_purchasing_invoice')->only('repayment');
        $this->middleware('permission:print_purchasing_invoice')->only('print');
    }

    public function index(Request $request){
        $suppliers = Supplier::where('status', 'مفعل')->orderBy('name', 'asc')->get(['name','id']);
        $shiftIds = Shift::orderBy('id', 'desc')->take(5)->pluck('id');
        $query = PurchasingInvoice::query();

        if ($request->filled('invoice_date')) {
            $query->where('invoice_date', $request->invoice_date);
        }

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', $request->invoice_number);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('invoice_date', [$request->start_date, $request->end_date]);
        }

        if (!$request->all()) {
            $query->whereIn('shift_id', $shiftIds);
        }

        // Get the paginated results
        $invoices = $query->orderBy('created_at', 'desc')->get()->take(50);
        return view('pages.purchasingInvoices.index', compact('invoices', 'suppliers'));
    }
    
    public function create(){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            return view('pages.purchasingInvoices.create');
        }else{
            toastr()->warning('يجب بدء وردية جديدة أولا!');
            return redirect()->intended(route('shifts.index'));
        }
    }

    public function store(Request $request){
        //
    }

    public function show(PurchasingInvoice $purchasingInvoice){
        $invoice = PurchasingInvoice::find($purchasingInvoice->id);
        if($invoice){
            return view('pages.purchasingInvoices.show', compact('invoice'));
        }else{
            toastr()->error('الفاتورة غير موجودة');
            return redirect()->back();
        }
    }

    public function print(PurchasingInvoice $purchasingInvoice){
        $invoice = PurchasingInvoice::find($purchasingInvoice->id);
        if($invoice){
            return view('pages.purchasingInvoices.print', compact('invoice'));
        }else{
            toastr()->error('الفاتورة غير موجودة');
            return redirect()->back();
        }
    }


    public function repayment(Request $request, PurchasingInvoice $purchasingInvoice){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            if($purchasingInvoice){
                try {
                    if($purchasingInvoice->payment_fund_status == 'معلق'){
                        toastr()->warning('يجب تأكيد حالة استلام المبلغ المدفوع اولا');
                        return redirect()->back();
                    }else{
                        DB::beginTransaction();
                        if($purchasingInvoice->supplier->account < 0 && $purchasingInvoice->payment_status != 'تم الدفع'){
                            $this->applySupplierExcessPayment($purchasingInvoice->supplier, $request->paid, false);
                            
                            $this->addPayment(
                                'شراء',
                                $currentShift->id,
                                null,
                                $purchasingInvoice->supplier_id,
                                auth()->user()->id,
                                $purchasingInvoice->id,
                                $request->paid,
                                'صرف'
                            );
                            
                            logActivity(' قام الموظف بسداد مديونية فاتورة بيع رقم '.$purchasingInvoice->invoice_number, 'الفواتير');
                            
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

    public function edit(PurchasingInvoice $purchasingInvoice){
        //
    }

    public function update(Request $request, PurchasingInvoice $purchasingInvoice){
        //
    }

    public function destroy(PurchasingInvoice $purchasingInvoice){
        //
    }
}
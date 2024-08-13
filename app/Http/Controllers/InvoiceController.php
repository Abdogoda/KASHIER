<?php

namespace App\Http\Controllers;

use App\Models\FundAccount;
use App\Models\Invoice;
use App\Models\Shift;
use App\Traits\PaymentTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller{
    
    use PaymentTrait;
    
    public function __construct(){
        $this->middleware('permission:view_invoices')->only('index');
        $this->middleware('permission:add_invoice')->only('create');
        $this->middleware('permission:add_invoice')->only('store');
        $this->middleware('permission:view_invoice')->only('show');
        $this->middleware('permission:print_invoice')->only('print');
        $this->middleware('permission:return_invoice')->only('update');
    }

    public function index(Request $request){
        $shiftIds = Shift::orderBy('id', 'desc')->take(5)->pluck('id');
        $query = Invoice::query();

        if ($request->filled('invoice_date')) {
            $query->whereDate('created_at', $request->invoice_date);
        }

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', $request->invoice_number);
        }

        if ($request->filled('is_returned')) {
            $query->where('is_returned', $request->is_returned);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if (!$request->all()) {
            $query->whereIn('shift_id', $shiftIds);
        }

        // Get the paginated results
        $invoices = $query->orderBy('created_at', 'desc')->get()->take(50);
        return view('pages.invoices.index', compact('invoices'));
    }
    

    public function create(){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            return view('pages.invoices.create');
        }else{
            toastr()->warning('يجب بدء وردية جديدة أولا!');
            return redirect()->intended(route('shifts.index'));
        }
    }


    public function store(Request $request){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            $request->validate([
                'invoice_date' => 'nullable|date|before_or_equal:today',
                'invoice_time' => 'nullable|date_format:H:i',
                'type' => ['required', 'string', 'max:255', 'in:صرف,قبض'],
                'cost' => 'required|numeric|min:0',
                'description' => ['required', 'string', 'max:1000'],
            ]);
            
            try {
                DB::beginTransaction();

                if($request->type == 'صرف' && $request->cost > FundAccount::first()->balance){
                    toastr()->warning('المبلغ الذي تريد صرفة أكبر من الميزانية المتاحة');
                    return redirect()->back()->withInput();
                }
                
                $attributes = $request->all();
                
                $attributes['invoice_date'] ??= Carbon::now()->format('Y-m-d');
                $attributes['invoice_time'] ??= Carbon::now()->format('H:i');
                $attributes['shift_id'] = $currentShift->id;
                $attributes['employee_id'] = auth()->user()->id;

    
                $invoice = Invoice::create($attributes);

                $this->addPayment(
                    'سند '.$invoice->type,
                    $currentShift->id,
                    null,
                    null,
                    auth()->user()->id,
                    $invoice->id,
                    $invoice->cost,
                    $invoice->type
                );
    
                logActivity(' قام الموظف باضافة فاتورة سند '.$request->type.' جديدة رقم '.$invoice->id, 'الفواتير');
                
                DB::commit();
                toastr()->success('تم اضافة فاتورة سند جديد بنجاح');
                return redirect()->intended(route('invoices.show', $invoice));
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back()->withInput();
            }
        }else{
            toastr()->warning('يجب بدء وردية جديدة أولا!');
            return redirect()->intended(route('shifts.index'));
        }
    }

    
    public function show(Invoice $invoice){
        $invoice = Invoice::find($invoice->id);
        if($invoice){
            return view('pages.invoices.show', compact('invoice'));
        }else{
            toastr()->error('الفاتورة غير موجودة');
            return redirect()->back();
        }
    }
    
    public function print(Invoice $invoice){
        $invoice = Invoice::find($invoice->id);
        if($invoice){
            return view('pages.invoices.print', compact('invoice'));
        }else{
            toastr()->error('الفاتورة غير موجودة');
            return redirect()->back();
        }
    }

    public function edit(Invoice $invoice){
        //
    }

    public function update(Request $request, Invoice $invoice){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            $request->validate([
                'return_value' => 'required|numeric|min:0',
            ]);
            
            try {
                DB::beginTransaction();

                if($invoice->is_returned != 0){
                    toastr()->warning('تم استرجاع الفاتورة من قبل');
                    return redirect()->back()->withInput();
                }
                if($request->return_value > $invoice->cost ||($invoice->type == 'قبض' && $request->request_value > FundAccount::first()->balance)){
                    toastr()->warning('المبلغ الذي تريد استرجاعة أكبر من الميزانية المتاحة');
                    return redirect()->back()->withInput();
                }
    
                $invoice->update([
                    'is_returned' => $request->return_value < $invoice->cost ? 2 : 1,
                    'return_value' => $request->return_value,
                    'return_date_time' => now()
                ]);

                $type = $invoice->type == 'قبض' ? 'صرف' : 'قبض';
                $this->addPayment(
                    'سند '.$type,
                    $currentShift->id,
                    null,
                    null,
                    auth()->user()->id,
                    $invoice->id,
                    $request->return_value,
                    $type
                );
    
                logActivity(' قام الموظف باسترجاع فاتورة سند '.$request->type.' رقم '.$invoice->id, 'الفواتير');
                
                DB::commit();
                toastr()->success('تم استرجاع الفاتورة بنجاح');
                return redirect()->intended(route('invoices.show', $invoice));
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back()->withInput();
            }
        }else{
            toastr()->warning('يجب بدء وردية جديدة أولا!');
            return redirect()->intended(route('shifts.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
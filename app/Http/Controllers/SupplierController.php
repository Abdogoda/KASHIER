<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Rules\ValidPhoneRule;
use App\Traits\PaymentTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller{
    
    use PaymentTrait;
    
    public function index(){
        $suppliers = Supplier::all();
        return view('pages.suppliers.index', compact('suppliers'));
    }

    public function create(){
        return view('pages.suppliers.create');
    }

    
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:suppliers,name'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:suppliers,email'],
            'phone' => ['nullable', new ValidPhoneRule, 'unique:suppliers,phone'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255', 'in:مفعل,غير مفعل'],
        ]);
        
        try {
            DB::beginTransaction();
            $attributes = $request->all();
            
            $supplier = Supplier::create($attributes);

            logActivity(' قام الموظف باضافة مورد جديد رقم '.$supplier->id, 'الموردين');
            
            DB::commit();
            toastr()->success('تم اضافة مورد جديد بنجاح');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }

    public function show(Supplier $supplier){
        if($supplier){
            return view('pages.suppliers.show', compact('supplier'));
        }else{
            toastr()->error('لم يتم العثور علي المورد');
            return redirect()->back();
        }
    }
    
    public function edit(Supplier $supplier){
        //
    }
    
    public function update(Request $request, Supplier $supplier){
        if($supplier){
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:suppliers,name,'.$supplier->id],
                'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:suppliers,email,'.$supplier->id],
                'phone' => ['nullable', new ValidPhoneRule, 'unique:suppliers,phone,'.$supplier->id],
                'address' => ['nullable', 'string', 'max:255'],
                'status' => ['nullable', 'string', 'max:255', 'in:مفعل,غير مفعل'],
            ]);
            
            try {
                DB::beginTransaction();
    
                $supplier->update($request->all());

                logActivity(' قام الموظف بتعديل بيانات مورد رقم '.$supplier->id, 'الموردين');
                
                DB::commit();
                toastr()->success('تم تعديل بيانات المورد بنجاح');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back();
            }
        }else{
            toastr()->error('لم يتم العثور علي المورد');
            return redirect()->back();
        }
    }

    public function repayment(Request $request, Supplier $supplier){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            try {
                DB::beginTransaction();
                if($request->paid > 0){
                    $formal_account = $supplier->account;
                    $this->applySupplierExcessPayment($supplier, $request->paid, false);

                    $payment = $this->addPayment(
                        'قبض',
                        $currentShift->id,
                        $supplier->id,
                        null,
                        auth()->user()->id,
                        null,
                        $request->paid,
                    );
                    DB::commit();
                    
                    $transaction = [
                        'formal_account' =>  $formal_account,
                        'paid' =>  $request->paid,
                        'account' =>  $supplier->account,
                        'number' => $payment->id,
                        'supplier' => $supplier->name,
                        'date' => Carbon::now()->format('Y-m-d'),
                        'time' => Carbon::now()->format('H:i'),
                        'employee' => auth()->user()->name,
                        'type' => 'سداد'
                    ];
                    
                    return redirect()->intended(route('suppliers.print_transaction', ['transaction' => $transaction]));
                }else{
                    toastr()->error('لا يوجد مديونية علي هذا العميل');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back();
            }
        }else{
            toastr()->warning('يجب بدء وردية جديدة أولا!');
            return redirect()->intended(route('shifts.index'));
        }
    }
  public function print_transaction(Request $request){
        $transaction = $request['transaction'];
        return view('pages.suppliers.print_transaction', compact('transaction'));
    }


    public function destroy(Supplier $supplier){
        //
    }
}
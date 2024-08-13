<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Rules\ValidPhoneRule;
use App\Traits\PaymentTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller{

    use PaymentTrait;
    public function __construct(){
        $this->middleware('permission:view_customers')->only('index');
        $this->middleware('permission:add_customer')->only('create');
        $this->middleware('permission:add_customer')->only('store');
        $this->middleware('permission:view_customer')->only('show');
        $this->middleware('permission:edit_customer')->only('update');
        $this->middleware('permission:delete_customer')->only('destroy');
    }
    public function index(){
        $customers = Customer::all();
        return view('pages.customers.index', compact('customers'));
    }

    public function create(){
        return view('pages.customers.create');
    }

    
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:customers,name'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:customers,email'],
            'phone' => ['nullable', new ValidPhoneRule, 'unique:customers,phone'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        
        try {
            DB::beginTransaction();
            $attributes = $request->all();
            
            $customer = Customer::create($attributes);
            
            logActivity(' قام الموظف باضافة عميل جديد رقم '.$customer->id, 'العملاء');
            DB::commit();
            toastr()->success('تم اضافة عميل جديد بنجاح');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }

    public function show(Customer $customer){
        if($customer){
            return view('pages.customers.show', compact('customer'));
        }else{
            toastr()->error('لم يتم العثور علي العميل');
            return redirect()->back();
        }
    }
    
    public function edit(customer $customer){
        //
    }
    
    public function update(Request $request, Customer $customer){
        if($customer){
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:customers,name,'.$customer->id],
                'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:customers,email,'.$customer->id],
                'phone' => ['nullable', new ValidPhoneRule, 'unique:customers,phone,'.$customer->id],
                'address' => ['nullable', 'string', 'max:255'],
                'status' => ['nullable', 'string', 'max:255', 'in:مفعل,غير مفعل'],
            ]);
            
            try {
                DB::beginTransaction();
    
                $customer->update($request->all());
                
                logActivity(' قام الموظف بتحديث بيانات عميل رقم '.$customer->id, 'العملاء');
                DB::commit();
                toastr()->success('تم تعديل بيانات العميل بنجاح');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back();
            }
        }else{
            toastr()->error('لم يتم العثور علي العميل');
            return redirect()->back();
        }
    }

    public function repayment(Request $request, Customer $customer){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            try {
                DB::beginTransaction();
                if($request->paid > 0){
                    $formal_account = $customer->account;
                    $this->applyCustomerExcessPayment($customer, $request->paid, false);
                    
                    $payment = $this->addPayment(
                        'قبض',
                        $currentShift->id,
                        $customer->id,
                        null,
                        auth()->user()->id,
                        null,
                        $request->paid,
                    );
                    DB::commit();
                    
                    $transaction = [
                        'formal_account' =>  $formal_account,
                        'paid' =>  $request->paid,
                        'account' =>  $customer->account,
                        'number' => $payment->id,
                        'customer' => $customer->name,
                        'date' => Carbon::now()->format('Y-m-d'),
                        'time' => Carbon::now()->format('H:i'),
                        'employee' => auth()->user()->name,
                        'type' => 'سداد'
                    ];
                    
                    return redirect()->intended(route('customers.print_transaction', ['transaction' => $transaction]));
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

    public function repayment_for_customer(Request $request, Customer $customer){
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            try {
                DB::beginTransaction();
                if($request->paid > 0 && $customer->account > 0){
                    $formal_account = $customer->account;
                    $value = $customer->account >= $request->paid ? $request->paid : $customer->account;
                    $payment = $this->addPayment(
                        'صرف',
                        $currentShift->id,
                        $customer->id,
                        null,
                        auth()->user()->id,
                        null,
                        $value,
                        'صرف'
                    );
                    $customer->account -= $value;
                    $customer->save();
                    logActivity(' قام الموظف بصرف مبلغ '.$request->paid-$value.' لحساب العميل رقم '.$customer->id, 'العملاء');
                    
                    DB::commit();
                    
                    $transaction = [
                        'formal_account' =>  $formal_account,
                        'paid' =>  $request->paid,
                        'account' =>  $customer->account,
                        'number' => $payment->id,
                        'customer' => $customer->name,
                        'date' => Carbon::now()->format('Y-m-d'),
                        'time' => Carbon::now()->format('H:i'),
                        'employee' => auth()->user()->name,
                        'type' => 'صرف'
                    ];
                    
                    return redirect()->intended(route('customers.print_transaction', ['transaction' => $transaction]));
                }else{
                    toastr()->error('لا يوجد مستحقات لهذا العميل');
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
        return view('pages.customers.print_transaction', compact('transaction'));
    }

    public function destroy(customer $customer){
        //
    }
}
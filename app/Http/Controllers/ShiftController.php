<?php

namespace App\Http\Controllers;

use App\Models\FundAccount;
use App\Models\Payment;
use App\Models\PurchasingInvoice;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller{

    public function __construct(){
        $this->middleware('permission:view_shifts')->only('index');
        $this->middleware('permission:start_shift')->only('create');
        $this->middleware('permission:view_shift')->only('show');
        $this->middleware('permission:end_shift')->only('destroy');
    }


    public function index(){
        $shifts = Shift::orderBy('created_at', 'desc')->get()->take(50);
        return view('pages.shifts.index', compact('shifts'));
    }

    public function create(){
        //
    }

    public function store(Request $request){
        try {
            $currentShift = Cache::get('current_shift');
            $pendingShift = Shift::where('status', 'جارية')->first();

            if ($currentShift || $pendingShift) {
                toastr()->warning('هناك وردية جارية بالفعل!');
                return redirect()->back();
            }else{
                Db::beginTransaction();
                $employeeId = auth()->user()->id;
                Carbon::setLocale('ar');
                $currentDay = Carbon::now()->isoFormat('dddd'); // e.g., 'Sunday'
                $currentDate = Carbon::now()->toDateString(); // e.g., '2024-08-06'
                $currentTime = Carbon::now()->toTimeString(); // e.g., '14:35:12'

                $shift = Shift::create([
                    'employee_id' => $employeeId,
                    'day' => $currentDay,
                    'start_date' => $currentDate,
                    'start_time' => $currentTime,
                    'initial_amount' => FundAccount::first()->balance,
                ]);

                Cache::put('current_shift', $shift);
                logActivity(' قام الموظف ببدأ وردية جديدة رقم '.$shift->id, 'الوردية');

                DB::commit();
                toastr()->success('تم بدأ الوردية الجديدة بنجاح');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect()->back();
        }
    }

    public function show(Shift $shift){
        if($shift){
            $purchasing_invoices = $shift->purchasing_invoices;
            $selling_invoices = $shift->selling_invoices;
            $invoices = $shift->invoices;
            return view('pages.shifts.show', compact('shift', 'purchasing_invoices', 'selling_invoices', 'invoices'));
        }else{
            toastr()->error('لم يتم العثور علي الوردية');
            return redirect()->back();
        }
    }

    public function edit(Shift $shift){
        //
    }

    public function update(Request $request, Shift $shift){
        if($shift){
            Cache::forget('current_shift'); // Remove the shift from cache
            try {
                Db::beginTransaction();

                $total_amount = FundAccount::first()->balance;
                $added_amount = $shift->payments()->where('payment_type', 'قبض')->sum('amount');
                $withdraw_amount = $shift->payments()->where('payment_type', 'صرف')->sum('amount');

                $shift->end_date = Carbon::now()->toDateString();
                $shift->end_time = Carbon::now()->toTimeString();
                $shift->status = 'منتهية';
                
                $shift->total_amount = $total_amount;
                $shift->added_amount = $added_amount;
                $shift->withdraw_amount = $withdraw_amount;
                $shift->difference_amount = $shift->initial_amount - $total_amount + $added_amount - $withdraw_amount;

                $shift->save();
                Artisan::call('db:backup');
                
                logActivity(' قام الموظف بانهاء الوردية رقم '.$shift->id, 'الوردية');

                DB::commit();
                toastr()->success('تم انهاء الوردية بنجاح');
                return redirect()->intended(route('shifts.show', $shift));
            } catch (\Exception $e) {
                
                Cache::put('current_shift', $shift);
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back();
            }
        }else{
            toastr()->error('لم يتم العثور علي الوردية');
            return redirect()->back();
        }
    }

    public function destroy(Shift $shift){
        //
    }
}
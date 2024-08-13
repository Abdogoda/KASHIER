<?php

namespace App\Http\Controllers;

use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseProductController extends Controller{

    public function __construct(){
        $this->middleware('permission:view_warehouse')->only('index');
        $this->middleware('permission:edit_warehouse')->only('update');
    }
    public function index(){
        $warehouse_products = WarehouseProduct::all();
        return view('pages.warehouse.index', compact('warehouse_products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseProduct $warehouseProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseProduct $warehouseProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseProduct $warehouseProduct){
        $warehouseProduct = WarehouseProduct::find($request->id);
        if($warehouseProduct){
            $request->validate([
                'opening_balance' => 'required|integer|min:0',
            ]);
            if (($warehouseProduct->opening_balance - $request->opening_balance) > $warehouseProduct->balance && $request->opening_balance < $warehouseProduct->opening_balance) {
                toastr()->warning('يجب ان يكون الفرق بين الرصيد الافتتاحي المضاف والرصيد الافتتاحي الفعلي أكثر من الرصيد الحالي');
                return redirect()->back();
            }else{
                
                try {
                    DB::beginTransaction();
                    
                    $added_balance = $request->opening_balance - $warehouseProduct->opening_balance;
                    $warehouseProduct->update([
                        'opening_balance' => $request->opening_balance,
                        'balance' => $warehouseProduct->balance + $added_balance,
                    ]);
                    
                    DB::commit();
                    toastr()->success('تم تعديل الرصيد الافتتاحي بنجاح');
                    return redirect()->back();
                } catch (\Exception $e) {
                    DB::rollBack();
                    toastr()->error($e->getMessage());
                    return redirect()->back()->withInput();
                }
            }
        }else{
            toastr()->error('لم يتم العثور علي المنتج');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseProduct $warehouseProduct)
    {
        //
    }
}
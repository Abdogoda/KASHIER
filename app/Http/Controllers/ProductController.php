<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSegment;
use App\Models\Segment;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller{
    
    public function __construct(){
        $this->middleware('permission:view_products')->only('index');
        $this->middleware('permission:add_product')->only('create');
        $this->middleware('permission:add_product')->only('store');
        $this->middleware('permission:view_product')->only('show');
        $this->middleware('permission:edit_product')->only('update');
        $this->middleware('permission:delete_product')->only('destroy');

        // Cache all products if not already cached
        if (!Cache::has('all_products')) {
            Cache::forever('all_products', Product::all());
        }
    }

    public function index(){
        $products = Cache::get('all_products');
        $segments = Segment::all();
        return view('pages.products.index', compact('products', 'segments'));
    }

    public function create(){
        $segments = Segment::all();
        return view('pages.products.create', compact('segments'));
    }

    
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'purchasing_price' => 'required|numeric|min:0',
            'opening_balance' => 'required|integer|min:0',
            'segments' => 'required|array|min:1',
            'segments.*' => 'nullable|numeric|min:0',
            'description' => ['nullable', 'string', 'max:255'],
        ]);
        $selling_price = false;
        foreach ($request->segments as $segment) {
            if (!is_null($segment) && $segment > 0) {
                $selling_price = $segment;
                break;
            }
        }
        if (!$selling_price) {
            toastr()->warning('يجب اضافة سعر بيع شريحة واحد علي الأقل');
            return redirect()->back()->withInput();
        }
        
        try {
            DB::beginTransaction();

            $product = Product::create([
                'name' => $request->name,
                'purchasing_price' => $request->purchasing_price,
                'selling_price' => $selling_price,
                'description' => $request->description ?? null,
            ]);

            WarehouseProduct::create([
                'product_id' => $product->id,
                'opening_balance' => $request->opening_balance,
                'balance' => $request->opening_balance,
            ]);
            
            foreach ($request->segments as $segment => $price) {
                ProductSegment::create([
                    'product_id' => $product->id,
                    'segment_id' => $segment,
                    'segment_price' => $price ?? null,
                ]);
            }
            logActivity(' قام الموظف باضافة منتج جديد رقم '.$product->id, 'المنتجات');

            DB::commit();
            toastr()->success('تم اضافة منتج جديد بنجاح');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function show(Product $product){
        if($product){
            $segments = Segment::all();
            return view('pages.products.show', compact('product', 'segments'));
        }else{
            toastr()->error('لم يتم العثور علي المنتج');
            return redirect()->back();
        }
    }
    
    public function edit(Product $product){
        //
    }
    
    public function update(Request $request, Product $product){
        if($product){
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:products,name,'.$product->id],
                'status' => ['nullable', 'string', 'max:255', 'in:مفعل,غير مفعل'],
                'purchasing_price' => 'required|numeric|min:0',
                'segments' => 'required|array|min:1',
                'segments.*' => 'nullable|numeric|min:0',
                'description' => ['nullable', 'string', 'max:255'],
            ]);
            $selling_price = false;
            foreach ($request->segments as $segment) {
                if (!is_null($segment) && $segment > 0) {
                    $selling_price = $segment;
                    break;
                }
            }
            if (!$selling_price) {
                toastr()->warning('يجب اضافة سعر بيع شريحة واحد علي الأقل');
                return redirect()->back()->withInput();
            }
            
            try {
                DB::beginTransaction();
    
                $product->update([
                    'name' => $request->name,
                    'status' => $request->status,
                    'purchasing_price' => $request->purchasing_price,
                    'selling_price' => $selling_price,
                    'description' => $request->description ?? null,
                ]);
                
                DB::table('product_segments')->where('product_id', $product->id)->delete();
                foreach ($request->segments as $segment => $price) {
                    ProductSegment::create([
                        'product_id' => $product->id,
                        'segment_id' => $segment,
                        'segment_price' => $price ?? null,
                    ]);
                }
    
                logActivity(' قام الموظف بتعديل بيانات منتج رقم '.$product->id, 'المنتجات');

                DB::commit();
                toastr()->success('تم تعديل المنتج جديد بنجاح');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollBack();
                toastr()->error($e->getMessage());
                return redirect()->back()->withInput();
            }
        }else{
            toastr()->error('لم يتم العثور علي المنتج');
            return redirect()->back();
        }
    }


    public function destroy(Product $product){
        //
    }
}
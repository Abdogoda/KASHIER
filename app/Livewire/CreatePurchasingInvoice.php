<?php

namespace App\Livewire;

use App\Models\FundAccount;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ProductSegment;
use App\Models\PurchasingInvoice;
use App\Models\Segment;
use App\Models\Supplier;
use App\Models\WarehouseProduct;
use App\Traits\PaymentTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreatePurchasingInvoice extends Component{
    public $products = [];
    public $supplier_id=null, $supplier_name=null, $supplier_phone=null, $supplier_type=true, $payment_method='نقدي', $invoice_date=null, $invoice_time=null;
    public $discount_rate=0.0, $discount_value=0.0, $total_amount_after_discount=0;
    public $paid=0, $remaining=0, $product_count=0;
    public $account = 0.0;
    public $total_amount = 0;
    
    use PaymentTrait;


    protected $rules = [
        'supplier_id' => 'nullable|exists:suppliers,id',
        'supplier_name' => 'nullable|string|max:255',
        'supplier_phone' => ['nullable', 'string', 'max:255', 'regex:/^01[0125]\d{8}$/'],
        'supplier_type' => 'required|boolean',
        'payment_method' => 'required|string|max:255|in:نقدي وأجل,نقدي,أجل',

        'invoice_date' => 'nullable|date|before_or_equal:today',
        'invoice_time' => 'nullable|date_format:H:i',

        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.price' => 'required|numeric|min:0',
        'products.*.segment0' => 'required|numeric|min:0',
    ];

    public function mount(){
        $this->products[] = [
            'product_id' => null,
            'price' => 0,
            'balance' => 0,
            'quantity' => 1,
            'totalPrice' => 0,
            'segment0' => 0,
            'segment1' => 0,
            'segment2' => 0,
        ];
    }

    public function addProduct(){
        $this->products[] = [
            'product_id' => null,
            'price' => 0,
            'balance' => 0,
            'quantity' => 1,
            'totalPrice' => 0,
            'segment0' => 0,
            'segment1' => 0,
            'segment2' => 0,
        ];
    }

    public function removeProduct($index){
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public function UpdatedSupplierId($supplier_id){
        if($supplier_id){
            $this->supplier_type = false;
            $supplier = Supplier::find($supplier_id);
            $this->supplier_name = $supplier->name;
            $this->supplier_phone = $supplier->phone;
            $this->account = $supplier->account < 0 ? $supplier->account * -1 : 0;
        }
    }

    public function updatedSupplierType(){
        if($this->supplier_type){
            $this->supplier_id = null;
            $this->supplier_name = null;
            $this->supplier_phone = null;
        }
    }

    public function UpdatedSupplierName(){
        if($this->supplier_id == null){
            $supplier = Supplier::where('name', $this->supplier_name)->get();
            if($supplier){
                toastr()->warning('اسم العميل موجود بالفعل');
            }
        }
    }


    public function updatedProducts($value, $name){
        $segments = explode('.', $name);
        $index = $segments[0];
        $field = $segments[1];
        
        if($value){
            if ($field === 'product_id') {
                $selectedProduct = Product::find($value);
                $this->products[$index]['price'] = $selectedProduct->purchasing_price;
                $this->products[$index]['balance'] = $selectedProduct->warehouseProduct->balance;
                $this->products[$index]['segment0'] = $selectedProduct->segments->where('segment_id', 1)->first()->segment_price;
                $this->products[$index]['segment1'] = $selectedProduct->segments->where('segment_id', 1)->first()->segment_price;
                $this->products[$index]['segment2'] = $selectedProduct->segments->where('segment_id', 3)->first()->segment_price;
            }
            $this->products[$index]['totalPrice'] = round($this->products[$index]['quantity'] * $this->products[$index]['price'], 1);

            if ($field === 'segment0' || $field === 'segment1' || $field === 'segment2') {
                if($value < $this->products[$index]['price']){
                    $this->products[$index][$field] = $this->products[$index]['price'];
                    toastr()->warning('سعر البيع لابد ان يكون اكبر من او يساوي سعر الشراء');
                }
            }
        }

        $this->calculateTotal();
    }
    
    public function updatedDiscountRate(){
        if($this->discount_rate > 0){
            $this->discount_value = round($this->total_amount * ($this->discount_rate / 100), 1);
        }
        $this->calculateTotal();
    }
    
    public function updatedDiscountValue(){
        if($this->discount_value > 0){
            $this->discount_rate = round( ($this->discount_value / $this->total_amount) * 100, 1);
        }
        $this->calculateTotal();
    }
    
    public function calculateTotal(){
        $this->total_amount = 0;
        $this->product_count = 0;

        foreach ($this->products as $product) {
            $this->total_amount += $product['totalPrice'];
            $this->product_count += $product['quantity'];
        }
        $this->total_amount = round($this->total_amount, 1);
        $this->total_amount_after_discount = round($this->total_amount - $this->discount_value, 1);
        $this->paid = $this->total_amount_after_discount;
        if($this->payment_method == 'أجل'){
            $this->paid = 0;
        }
        $this->remaining = round($this->total_amount_after_discount - $this->paid, 1);
    }
    
    
    public function updatedPaid(){
        if($this->paid > $this->total_amount_after_discount + $this->account){
            toastr()->warning('لا بد أن لا يزيد المدفوع عن السعر الكلي بعد الخصم ');
            $this->paid = $this->total_amount_after_discount + $this->account;
        }
        if($this->paid < $this->total_amount_after_discount && $this->remaining < $this->total_amount_after_discount){
            $this->payment_method = 'نقدي وأجل';
        }
        $this->remaining = round($this->total_amount_after_discount - $this->paid, 1);
    }

    public function updatedPaymentMethod(){
        if($this->payment_method == 'أجل'){
            $this->paid = 0;
        }elseif($this->payment_method == 'نقدي'){
            $this->paid = $this->total_amount_after_discount;
        }
        $this->remaining = round($this->total_amount_after_discount - $this->paid, 1);
    }

    public function saveInvoice(){
        
        $currentShift = Cache::get('current_shift');
        if($currentShift){
            $this->validate();

            $this->invoice_date ??= Carbon::now()->format('Y-m-d');
            $this->invoice_time ??= Carbon::now()->format('H:i');

            $supplier_type = $this->supplier_type ?  'مورد لحظي' : 'مورد' ;

            try {
                DB::beginTransaction();

                // create the supplier if it doesn't exist
                if($this->supplier_id == null && $this->supplier_name != null && $this->supplier_name != '' && $this->supplier_name != 'غير محدد'){
                    $existingsupplier = Supplier::where('name', $this->supplier_name)->first();

                    if (!$existingsupplier) {
                        $supplier = Supplier::create([
                            'name' => $this->supplier_name,
                            'phone' => $this->supplier_phone,
                        ]);
                        $this->supplier_id = $supplier->id;
                    }else{
                        toastr()->warning('اسم المورد موجود بالفعل');
                        return;
                    }
                }

                // create the purchasing invoice
                $purchasing_invoice = PurchasingInvoice::create([
                    "supplier_type" => $supplier_type,
                    'payment_method' => $this->payment_method,
    
                    'shift_id' => $currentShift->id,
                    'employee_id' => auth()->user()->id,
                    'supplier_id' => $this->supplier_id,
                    'supplier_name' => $this->supplier_name ?? 'غير محدد',
                    'supplier_phone' => $this->supplier_phone ?? 'غير محدد',
    
                    'payment_status' => $this->remaining == 0 ? 'تم الدفع'  : 'معلق',
                    
                    'cost_before_discount' => $this->total_amount,
                    'discount_rate' => $this->discount_rate,
                    'discount_value' => $this->discount_value,
                    'cost_after_discount' => $this->total_amount - $this->discount_value,
                    'total_cost' => $this->total_amount - $this->discount_value,
    
                    'paid' => $this->paid > $this->total_amount ? $this->total_amount : $this->paid,
                    'over_paid' => $this->paid > $this->total_amount ? round($this->paid - $this->total_amount, 1) : 0,
                    'remaining' => $this->remaining,
    
                    'product_count' => $this->product_count,
                    'status' => 'مفعل',
                    
                    'invoice_date' => $this->invoice_date,
                    'invoice_time' => $this->invoice_time,
                ]);

                foreach ($this->products as $product) {
                    $actual_product = Product::find($product['product_id']);
                    $product_warehouse = $actual_product->warehouseProduct;

                    // add the product to the created invoice
                    InvoiceProduct::create([
                        "purchasing_invoice_id" => $purchasing_invoice->id,
                        "product_id" => $product['product_id'],
                        "price" => $product['price'],
                        "quantity" => $product['quantity'],
                        "total_price" => $product['totalPrice'],
                    ]);

                    // update product purchasing and selling prices
                    $actual_product->update([
                        "purchasing_price" => $product['price'],
                        "selling_price" => $product['segment0']
                    ]);
                    
                    // update the product segments prices
                    ProductSegment::where('product_id', $product['product_id'])->where('segment_id', 1)->first()->update(["segment_price" => $product['segment0']]);
                    ProductSegment::where('product_id', $product['product_id'])->where('segment_id', 2)->first()->update(["segment_price" => $product['segment1']]);
                    ProductSegment::where('product_id', $product['product_id'])->where('segment_id', 3)->first()->update(["segment_price" => $product['segment2']]);

                    // update the product balance in warehouse
                    if($product_warehouse){
                        $product_warehouse->incoming_balance += $product['quantity'];
                        $product_warehouse->balance += $product['quantity'];
                        $product_warehouse->update();
                    }else{
                        $product_warehouse = new WarehouseProduct();
                        $product_warehouse->product_id += $product['product_id'];
                        $product_warehouse->incoming_balance = $product['quantity'];
                        $product_warehouse->balance = $product['quantity'];
                        $product_warehouse->save();
                    }
                }

                if($purchasing_invoice->supplier){
                    $purchasing_invoice->account_before = $purchasing_invoice->supplier->account;
                    $purchasing_invoice->save();
                }

                if($this->paid > 0){
                    // add payment invoice and update fund
                    $this->addPayment(
                        'شراء',
                        $currentShift->id,
                        null,
                        $this->supplier_id,
                        auth()->user()->id,
                        $purchasing_invoice->id,
                        $this->paid,
                        'صرف'
                    );
                }
                if($this->remaining > 0){
                    $purchasing_invoice->supplier->account -= $this->remaining;
                    $purchasing_invoice->supplier->save();
                }
                if($purchasing_invoice->over_paid > 0){
                    $this->applySupplierExcessPayment($purchasing_invoice->supplier, round($this->paid - $this->total_amount, 1));
                }

                logActivity(' قام الموظف باضافة فاتورة شراء جديدة رقم '.$purchasing_invoice->invoice_number, 'الفواتير');

                DB::commit();
                toastr()->success('تم اضافة فاتورة جديدة بنجاح');
                return redirect()->intended(route('purchasing_invoices.show', $purchasing_invoice));

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

    public function render(){
        $availableProducts = Product::where('status', 'مفعل')->orderBy('name', 'asc')->get(['name','id']);
        $availableSuppliers = Supplier::where('status', 'مفعل')->orderBy('name', 'asc')->get(['name','id']);
        $availableSegments = Segment::all();
        return view('livewire.create-purchasing-invoice', compact('availableProducts', 'availableSuppliers', 'availableSegments'));
    }
}
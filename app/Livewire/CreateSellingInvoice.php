<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ProductSegment;
use App\Models\Segment;
use App\Models\SellingInvoice;
use App\Models\Shift;
use App\Traits\NotificationTrait;
use App\Traits\PaymentTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateSellingInvoice extends Component{
    
    public $products = [];
    public $customer_id=null, $customer_name=null, $customer_phone=null, $customer_type=true, $account=0, $segment_id=1;
    public $payment_method='نقدي', $invoice_date=null, $invoice_time=null, $delivery_status='تم التوصيل', $payment_fund_status='تم الاستلام';
    public $discount_rate=0.0, $discount_value=0.0, $total_cost=0, $product_count=0;
    public $paid=0, $remaining=0, $final_account=0.0;
    public $total_amount = 0;

    use PaymentTrait;
    use NotificationTrait;


    protected $rules = [
        'customer_id' => 'nullable|exists:customers,id',
        'customer_name' => 'nullable|string|max:255',
        'customer_phone' => ['nullable', 'string', 'max:255', 'regex:/^01[0125]\d{8}$/'],
        'customer_type' => 'required|boolean',
        'payment_method' => 'required|string|max:255|in:نقدي وأجل,نقدي,أجل',
        'delivery_status' => 'required|string|max:255|in:معلق,تم التوصيل',

        'invoice_date' => 'nullable|date|before_or_equal:today',
        'invoice_time' => 'nullable|date_format:H:i',

        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ];

    public function mount(){
        $this->products[] = [
            'product_id' => null,
            'name' => null,
            'purchasing_price' => 0,
            'selling_price' => 0,
            'price' => 0,
            'balance' => 0,
            'quantity' => 1,
            'totalPrice' => 0,
        ];
    }

    public function addProduct(){
        $this->products[] = [
            'product_id' => null,
            'name' => null,
            'purchasing_price' => 0,
            'selling_price' => 0,
            'price' => 0,
            'balance' => 0,
            'quantity' => 1,
            'totalPrice' => 0,
        ];
    }

    public function removeProduct($index){
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public function UpdatedCustomerId($customer_id){
        if($customer_id){
            $this->customer_type = false;
            $customer = Customer::find($customer_id);
            $this->customer_name = $customer->name;
            $this->customer_phone = $customer->phone;
            $this->segment_id = $customer->segment_id;
            $this->account = $customer->account;
            $this->updatedSegmentId();
        }
    }
    public function UpdatedCustomerName(){
        if($this->customer_id == null){
            $customer = Customer::where('name', $this->customer_name)->get();
            if($customer){
                toastr()->warning('اسم العميل موجود بالفعل');
            }
        }
    }

    public function updatedCustomerType(){
        if($this->customer_type){
            $this->customer_id = null;
            $this->customer_name = null;
            $this->customer_phone = null;
        }
    }

    public function updatedProducts($value, $name){
        $segments = explode('.', $name);
        $index = $segments[0];
        $field = $segments[1];
        
        if($value){
            if ($field === 'product_id') {
                $selectedProduct = Product::find($value);
                $selectedSegment = ProductSegment::where('product_id', $selectedProduct->id)->where('segment_id', $this->segment_id)->first();
                $this->products[$index]['name'] = $selectedProduct->name;
                $this->products[$index]['purchasing_price'] = $selectedProduct->purchasing_price;
                $this->products[$index]['selling_price'] = $selectedProduct->selling_price;
                $this->products[$index]['balance'] = $selectedProduct->warehouseProduct->balance;
                $this->products[$index]['price'] = $selectedSegment ? $selectedSegment->segment_price : $selectedProduct->selling_price;
            }
            if ($this->products[$index]['price'] < $this->products[$index]['purchasing_price']) {
                toastr()->warning(' لا بد أن يكون سعر بيع المنتج '.$this->products[$index]['name'].' أكبر من سعر الشراء ');
                $this->products[$index]['price'] = $this->products[$index]['purchasing_price'];
            }
            if ($this->products[$index]['quantity'] > $this->products[$index]['balance']) {
                toastr()->warning(' لا بد أن تكون كمية المطلوبة للمنتج '.$this->products[$index]['name'].' أصغر من الكمية الموجودة في المخزن ');
                $this->products[$index]['quantity'] = $this->products[$index]['balance'];
            }
            $this->products[$index]['totalPrice'] = round($this->products[$index]['quantity'] * $this->products[$index]['price'], 1);
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
    
    public function updatedSegmentId(){
        foreach ($this->products as $index => $product) {
            $selectedSegment = ProductSegment::where('product_id', $product['product_id'])->where('segment_id', $this->segment_id)->first();
            
            $this->products[$index]['price'] = $selectedSegment ? $selectedSegment->segment_price : $this->products[$index]['selling_price'];
            $this->products[$index]['totalPrice'] = round($this->products[$index]['quantity'] * $this->products[$index]['price'], 1);
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
        $this->total_cost = round($this->total_amount - $this->discount_value, 1);
        $this->paid = $this->total_cost;

        if($this->payment_method == 'أجل'){
            $this->paid = 0;
        }
        $this->remaining = round($this->total_cost - $this->paid, 1);
        $this->final_account = $this->account - $this->remaining;
        $this->final_account += $this->paid > $this->total_cost ? $this->paid - $this->total_cost : 0;
    }
    
    public function updatedPaid(){
        if($this->paid < $this->total_cost && $this->remaining < $this->total_cost){
            $this->payment_method = 'نقدي وأجل';
        }

        $this->remaining = $this->total_cost - $this->paid > 0 ? round($this->total_cost - $this->paid, 1) : 0;
        $this->final_account = $this->account - $this->remaining;
        $this->final_account += $this->paid > $this->total_cost ? $this->paid - $this->total_cost : 0;
    }

    public function updatedPaymentMethod(){
        if($this->payment_method == 'أجل'){
            $this->paid = 0;
        }elseif($this->payment_method == 'نقدي'){
            $this->paid = $this->total_cost;
        }
        $this->remaining = round($this->total_cost - $this->paid, 1);
        $this->final_account = $this->account - $this->remaining;
        $this->final_account += $this->paid > $this->total_cost ? $this->paid - $this->total_cost : 0;
    }

    public function saveInvoice(){
        
        $currentShift = Shift::where('status', 'جارية')->latest()->first();
        if($currentShift){
            $this->validate();

            $this->invoice_date ??= Carbon::now()->format('Y-m-d');
            $this->invoice_time ??= Carbon::now()->format('H:i');

            $customer_type = $this->customer_type ?  'عميل لحظي' : 'عميل' ;

            try {
                DB::beginTransaction();

                // create the customer if it doesn't exist
                if($this->customer_id == null && $this->customer_name != null && $this->customer_name != '' && $this->customer_name != 'غير محدد'){
                    $existingCustomer = Customer::where('name', $this->customer_name)->first();

                    if (!$existingCustomer) {
                        $customer = Customer::create([
                            'name' => $this->customer_name,
                            'phone' => $this->customer_phone,
                            'segment_id' => $this->segment_id,
                        ]);
                        $this->customer_id = $customer->id;
                    }else{
                        toastr()->warning('اسم العميل موجود بالفعل');
                        return;
                    }
                }

                if($this->customer_id == null && $this->remaining > 0){
                    toastr()->warning('لا يمكن البيع بالاَجل للعميل النقدي, يجب دفع المبلغ بالكامل');
                    return;
                }
                if($this->customer_id == null && $this->paid > $this->total_cost){
                    toastr()->warning('لا يمكن دفع قيمة أكثر من المبلغ الكلي لفواتير العميل النقدي');
                    return;
                }

                // create the selling invoice
                $selling_invoice = SellingInvoice::create([
                    "customer_type" => $customer_type,
                    'payment_method' => $this->payment_method,
    
                    'shift_id' => $currentShift->id,
                    'employee_id' => auth()->user()->id,
                    'customer_id' => $this->customer_id,
                    'customer_name' => $this->customer_name ?? 'غير محدد',
                    'customer_phone' => $this->customer_phone ?? 'غير محدد',
    
                    'delivery_status' => $this->delivery_status,
                    'payment_status' => $this->remaining == 0 ? 'تم الدفع'  : 'معلق',
                    'payment_fund_status' => $this->payment_fund_status,
                    
                    'cost_before_discount' => $this->total_amount,
                    'discount_rate' => $this->discount_rate,
                    'discount_value' => $this->discount_value,
                    'cost_after_discount' => $this->total_cost,
                    'total_cost' => $this->total_cost,
    
                    'paid' => $this->paid > $this->total_cost ? $this->total_cost : $this->paid,
                    'over_paid' => $this->paid > $this->total_cost ? $this->paid - $this->total_cost : 0,
                    'remaining' => $this->remaining,

                    'account_before' => $this->account,
                    'account_after' => $this->final_account,

                    'product_count' => $this->product_count,
    
                    'status' => 'مفعل',
                    
                    'invoice_date' => $this->invoice_date,
                    'invoice_time' => $this->invoice_time,
                ]);
                $profit = 0.0;

                foreach ($this->products as $product) {
                    $actual_product = Product::find($product['product_id']);
                    $product_warehouse = $actual_product->warehouseProduct;

                    if($product_warehouse){
                        if($product_warehouse->balance >= $product['quantity']){
                            // update the profit
                            $profit += ($actual_product->selling_price - $actual_product->purchasing_price) * $product['quantity'];
        
                            // assign the product to the created invoice
                            InvoiceProduct::create([
                                "selling_invoice_id" => $selling_invoice->id,
                                "product_id" => $product['product_id'],
                                "price" => $product['price'],
                                "quantity" => $product['quantity'],
                                "total_price" => $product['totalPrice'],
                            ]);
                            
                            // update warehouse balance
                            $product_warehouse->outgoing_balance += $product_warehouse->balance > $product['quantity'] ?  $product['quantity'] : $product_warehouse->balance;
                            $product_warehouse->balance -= $product_warehouse->balance > $product['quantity'] ?  $product['quantity'] : $product_warehouse->balance;

                        }
                        $product_warehouse->update();
                        if($product_warehouse->balance <= 5){
                            $this->addNotification('warning', 'المنتج '.$actual_product->name.' أوشك علي الانتهاء من المخزن');
                        }
                    }
                }

                // update the invoice profits
                $selling_invoice->update(['profits' => $profit]);

                // add payment invoice and update fund
                if($selling_invoice->payment_fund_status == 'تم الاستلام'){
                    $this->addPayment(
                        'بيع',
                        $currentShift->id,
                        $this->customer_id,
                        null,
                        auth()->user()->id,
                        $selling_invoice->id,
                        $this->paid,
                    );
                }

                logActivity(' قام الموظف باضافة فاتورة بيع جديدة رقم '.$selling_invoice->invoice_number, 'الفواتير');
                
                // pay the extra amount for the debit invoices
                if($this->customer_id != null){
                    $customer = Customer::find($this->customer_id);
                    if($customer){
                        if($this->paid > $this->total_cost){
                            $this->applyCustomerExcessPayment($customer, $this->paid - $this->total_cost);
                        }else{
                            if($this->remaining > 0){
                                $selling_invoice->remaining = $this->remaining <= $this->account ? 0 : $this->remaining - $this->account;
                                $selling_invoice->save();
                                $customer->account -= $this->remaining;
                                $customer->save();
                            }
                            if($this->final_account >= 0){
                                $selling_invoice->remaining = 0;
                                $selling_invoice->save();
                            }
                        }
                        if($selling_invoice->remaining = 0){
                            $selling_invoice->payment_status = 'تم الدفع';
                            $selling_invoice->save();
                        }
                    }
                }

                DB::commit();
                toastr()->success('تم اضافة فاتورة بيع جديدة بنجاح');
                return redirect()->intended(route('selling_invoices.show', $selling_invoice));

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
        $availableCustomers = Customer::where('status', 'مفعل')->orderBy('name', 'asc')->get(['name','id']);
        $availableSegments = Segment::all();
        return view('livewire.create-selling-invoice', compact('availableProducts', 'availableCustomers', 'availableSegments'));
    }
}
<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\FundAccount;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\Shift;
use App\Traits\PaymentTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReturnSellingInvoice extends Component{
    public $products = [];
    public $original_account = 0.0, $account=0.0;
    
    public $invoice=null, $payment_status='تم الدفع';
    
    public $original_cost=0.0 // المبلغ الكلي للفاتورة الاصليى
    , $original_paid=0.0 // المدفوع من الفاتورة الاصلية
    , $original_remaining=0.0; // المتبقي من الفاتورة الاصلية
    
    public $invoice_remaining=0.0 // المخصوم للفاتورة الأصلية
    , $invoice_difference=0.0 // المتبقي بعد الخصم
    , $remaining_for_customer = 0.0 //
    , $return_value=0.00 // المبلغ المسترجع
    , $paid=0.0; // المدفوع حاليا

    use PaymentTrait;

    protected $rules = [
        'products.*.quantity' => 'required|integer|min:1',
    ];

    public function mount($invoice){ 
        $this->invoice = $invoice;
        $this->original_account = $invoice->customer->account ?? 0;
        $this->original_cost = $invoice->total_cost ?? 0;
        $this->original_remaining = $invoice->remaining ?? 0;
        $this->paid = $invoice->paid ?? 0;
        foreach ($invoice->products as $key => $item) {
            $this->products[] = [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'sold_price' => $item->price,
                'price' => $item->price,
                'sold_quantity' => $item->quantity,
                'quantity' => $item->quantity,
                'totalPrice' => $item->total_price,
            ];
            $this->return_value += $item->total_price;
        }
        $this->calculateRemaining();
    }

    public function removeProduct($index){
        unset($this->products[$index]);
        $this->products = array_values($this->products);
        $this->calculateTotal();
        if(count($this->products) == 0){
            toastr()->warning('لا يوجد منتجات لاسترجاعها');
        }
    }

    public function updatedProducts($value, $name){
        $segments = explode('.', $name);
        $index = $segments[0];
        $field = $segments[1];
        
        if ($this->products[$index]['quantity'] == "0"){
            $this->removeProduct($index);
        }
        if($value){
            if ($this->products[$index]['price'] > $this->products[$index]['sold_price']) {
                toastr()->warning(' لا بد أن يكون سعر استرجاع المنتج '.$this->products[$index]['name'].' أصغر من سعر البيع ');
                $this->products[$index]['price'] = $this->products[$index]['sold_price'];
            }
            if ($this->products[$index]['quantity'] > $this->products[$index]['sold_quantity']) {
                toastr()->warning(' لا بد أن تكون كمية استرجاع المنتج '.$this->products[$index]['name'].' أصغر من كمية البيع ');
                $this->products[$index]['quantity'] = $this->products[$index]['sold_quantity'];
            }
            $this->products[$index]['totalPrice'] = round($this->products[$index]['quantity'] * $this->products[$index]['price'], 1);
        }

        $this->calculateTotal();
    }
    
    public function calculateTotal(){
        $this->return_value = 0;
        foreach ($this->products as $product) {
            $this->return_value += $product['totalPrice'];
        }
        $this->return_value = round($this->return_value, 1);
        $this->calculateRemaining();
    }
    
    public function calculateRemaining(){
        $this->invoice_remaining = min($this->original_remaining, $this->return_value); // المخصوم للفاتورة الاصلية
        $this->invoice_difference = round($this->original_remaining - $this->invoice_remaining, 1); // المتبقي بعد الخصم
        $this->remaining_for_customer = round($this->return_value - $this->invoice_remaining, 1); // المتبقي بعد الخصم
        $this->account = $this->original_account + $this->return_value;
    }

    public function saveInvoice(){
        if(count($this->products) == 0){
            toastr()->warning('لا يوجد منتجات لاسترجاعها');
        }else{
            $currentShift = Shift::where('status', 'جارية')->latest()->first();
            if($currentShift){
                $this->validate();

                try {
                    DB::beginTransaction();

                    // create the selling invoice
                    $this->invoice->update([
                        'is_returned' => $this->invoice->total_cost > $this->return_value ? 2 : 1,
                        'payment_status' => $this->invoice_difference == 0 ? 'تم الدفع'  : $this->invoice->payment_status,
                        'return_value' => $this->return_value,
                        'remaining' => $this->invoice_difference,
                        'return_date_time' => now(),
                    ]);

                    foreach ($this->products as $product) {
                        $actual_product = Product::find($product['product_id']);
                        $product_warehouse = $actual_product->warehouseProduct;

                        if($product_warehouse){
                            // assign products to the created invoice
                            InvoiceProduct::create([
                                "selling_invoice_id" => $this->invoice->id,
                                "product_id" => $product['product_id'],
                                "price" => $product['price'],
                                "quantity" => $product['quantity'],
                                "total_price" => $product['totalPrice'],
                                "type" => 1,
                            ]);

                            // update the old product quantity
                            $invoice_product = InvoiceProduct::where('product_id', $product['product_id'])->where('selling_invoice_id', $this->invoice->id)->first();
                            if($invoice_product->quantity > $product['quantity']){
                                $invoice_product->update([
                                    'quantity' => $invoice_product->quantity - $product['quantity'], 
                                    "total_price" => $invoice_product->total_price - $product['totalPrice'], 
                                ]);
                            }else{
                                $invoice_product->delete();
                            }

                            // update warehouse balance
                            $product_warehouse->incoming_balance += $product['quantity'];
                            $product_warehouse->balance += $product['quantity'];
                        }
                        $product_warehouse->update();
                    }

                    // pay the customer remaining
                    $this->invoice->customer->account += $this->invoice_remaining;
                    $this->invoice->customer->save();
                    if($this->remaining_for_customer > 0){
                        $this->addPayment(
                            'بيع',
                            $currentShift->id,
                            $this->invoice->customer_id,
                            null,
                            auth()->user()->id,
                            $this->invoice->id,
                            $this->remaining_for_customer,
                        );
                        $this->applyCustomerExcessPayment($this->invoice->customer, $this->remaining_for_customer);
                    }

                    logActivity(' قام الموظف باسترجاع فاتورة بيع رقم '.$this->invoice->invoice_number, 'الفواتير');

                    DB::commit();
                    toastr()->success('تم استرجاع فاتورة بيع بنجاح');
                    return redirect()->intended(route('selling_invoices.show', $this->invoice));

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
    }

    public function render(){
        $availableProducts = Product::where('status', 'مفعل')->orderBy('name', 'asc')->get(['name','id']);
        $availableCustomers = Customer::where('status', 'مفعل')->orderBy('name', 'asc')->get(['name','id']);
        return view('livewire.return-selling-invoice', compact('availableProducts', 'availableCustomers'));
    }
}
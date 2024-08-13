<div>
    <hr>
    <form wire:submit.prevent="saveInvoice">
        
        <h4>معلومات الفاتورة</h4>
        <div class="row align-items-center mb-3">
            <div class="col-6 col-md-3 form-group mb-3" wire:ignoer>
                <label for="customer_id">العميل</label>
                <select name="customer_id" class="form-control select2" id="customer_id" style="max-width: 100%">
                    <option value="">اختر عميل</option>
                    @foreach ($availableCustomers as $customer)
                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                    @endforeach
                </select>
                @error('customer_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            
            <div class="col-6 col-md-1 form-group mb-3">
                <input type="checkbox" id="customer_type" wire:model.change="customer_type">
                <label for="customer_type">بدون عميل</label>
                @error('customer_type') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-6 col-md-2 form-group mb-3">
                <label for="segment_id">نوع الشريحة</label>
                <select wire:model.change="segment_id" class="form-control" id="segment_id">
                    <option value="">اختر...</option>
                    @foreach ($availableSegments as $segment)
                        <option value="{{$segment->id}}">{{$segment->name}}</option>
                    @endforeach
                </select>
            </div>
    
            <div class="col-6 col-md-3 form-group  mb-3">
                <label for="customer_name">اسم العميل</label>
                <input type="text" id="customer_name" wire:model="customer_name" class="form-control" {{$customer_id ? 'readonly' : ''}}>
                @error('customer_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
    
            <div class="col-6 col-md-3 form-group  mb-3">
                <label for="customer_phone">رقم العميل</label>
                <input type="text" id="customer_phone" minlength="11" maxlength="11" wire:model="customer_phone" class="form-control" {{$customer_id ? 'readonly' : ''}}>
                @error('customer_phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-6 col-md-3 form-group  mb-3">
                <label for="invoice_date">تاريخ الفاتورة</label>
                <input type="date" id="invoice_date" wire:model="invoice_date" class="form-control">
                @error('invoice_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-6 col-md-3 form-group  mb-3">
                <label for="invoice_time">وقت الفاتورة</label>
                <input type="time" id="invoice_time" wire:model="invoice_time" class="form-control">
                @error('invoice_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-4 col-md-2 form-group  mb-3">
                <label for="payment_method">طريقة الدفع</label>
                <select wire:model.change="payment_method" id="payment_method" class="form-control">
                    <option value="نقدي">نقدي</option>
                    <option value="نقدي وأجل">نقدي وأجل</option>
                    <option value="أجل">أجل</option>
                </select>
                @error('payment_method') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-4 col-md-2 form-group  mb-3">
                <label for="payment_fund_status">حالة الدفع (خاصة بالدرج)</label>
                <select wire:model.change="payment_fund_status" id="payment_fund_status" class="form-control">
                    <option value="تم الاستلام">تم الاستلام</option>
                    <option value="معلق">معلق</option>
                </select>
                @error('payment_fund_status') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-4 col-md-2 form-group  mb-3">
                <label for="delivery_status">حالة التوصيل</label>
                <select wire:model.change="delivery_status" id="delivery_status" class="form-control">
                    <option value="تم التوصيل">تم التوصيل</option>
                    <option value="معلق">معلق</option>
                </select>
                @error('delivery_status') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <hr>
        <h4>منتجات الفاتورة</h4>
        <div class="table-responsive pb-3">
            <table class="teble table-stripe table-bordered w-100 text-nowrap">
                <thead class="table-dark">
                    <tr class="">
                        <th class="text-center p-2">اسم المنتج</th>
                        <th class="text-center p-2">رصيد المخزن</th>
                        <th class="text-center p-2">الكمية</th>
                        <th class="text-center p-2">سعر الشراء</th>
                        <th class="text-center p-2">سعر البيع</th>
                        <th class="text-center p-2">المجموع</th>
                        <th class="text-center p-2">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            <td class="p-2" style="min-width: 220px"> 
                                <select  class="form-control " id="product-{{ $index }}" wire:model.change="products.{{ $index }}.product_id" required>
                                    <option value="" >...اختر المنتج</option>
                                    @foreach($availableProducts as $availableProduct)
                                        <option value="{{ $availableProduct->id }}">{{ $availableProduct->name }}</option>
                                    @endforeach
                                </select>
                                @error("products.$index.product_id") <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            <td class="text-center p-2 text-{{$product['balance'] > 5 ? 'success' : 'danger'}}">{{$product['balance']}}</td>
                            <td class="p-2">
                                <input type="number" wire:model.change="products.{{ $index }}.quantity" max="{{$product['balance']}}" required class="form-control" min="1">
                                @error("products.$index.quantity") <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            <td class="text-center py-2">{{$product['purchasing_price']}}</td>
                            <td class="text-center p-2">
                                <input type="number" wire:model.change="products.{{ $index }}.price" min="{{$product['purchasing_price']}}" required class="form-control" min="1">
                                @error("products.$index.price") <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            <td class="text-center p-2 table-success" style="min-width: 100px"><b>{{$product['totalPrice']}}</b></td>
                            <td class="text-center p-2">
                                <button type="button" class="btn btn-danger" wire:click="removeProduct({{ $index }})">حذف</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="form-group py-3">
            <button type="button" class="btn btn-secondary" wire:click="addProduct">اضف منتج جديد <i class="fe fe-plus"></i></button>
        </div>

        <hr>
        <h4 class="pt-3">حساب الفاتورة</h4>
        <div class="row py-3 bg-light">
            {{-- money row --}}
            <div class="col-6 col-md-2 form-group mb-3">
                <label for="total_amount">المبلغ قبل الخصم</label>
                <input type="number" class="form-control" min="0" step="0.01" readonly id="total_amount" wire:model="total_amount">
            </div>
            <div class="col-6 col-md-2 form-group mb-3">
                <label for="discount_rate">نسبة الخصم %</label>
                <input type="number" class="form-control" min="0" max="100" step="0.01" id="discount_rate" wire:model.change="discount_rate">
                @error("discount_rate") <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-6 col-md-2 form-group mb-3">
                <label for="discount_value">قيمة الخصم</label>
                <input type="number" class="form-control" min="0" step="0.01" id="discount_value" wire:model.change="discount_value">
                @error("discount_value") <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-6 col-md-2 form-group mb-3">
                <label for="total_amount_after_discount" >المبلغ النهائي</label>
                <input type="number" class="form-control" readonly min="0" step="0.01" id="total_amount_after_discount" wire:model="total_amount_after_discount">
            </div>
            <div class="col-6 col-md-2 form-group mb-3">
                <label for="paid">المدفوع</label>
                <input type="number" class="form-control" min="0" step="0.01" id="paid" wire:model.change="paid">
                @error("paid") <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-6 col-md-2 form-group mb-3">
                <label for="remaining">المتبقي</label>
                <input type="number" class="form-control" min="0" step="0.01" id="remaining" readonly wire:model="remaining">
            </div>
        </div>
        <div class="row py-3 bg-light">
            <div class="col-6 col-md-4 form-group mb-3">
                <label for="account">حساب سابق</label>
                <input type="number" class="form-control" min="0" step="0.01" readonly id="account" wire:model="account">
            </div>
            <div class="col-6 col-md-4 form-group mb-3">
                <label for="final_account">الحساب النهائي</label>
                <input type="number" class="form-control" min="0" step="0.01" id="final_account" readonly wire:model="final_account">
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-3 pt-3">
            <button type="submit" onclick="return confirm('هل أنت متأكد من أنك تريد انشاء هذة الفاتورة؟')" class="btn btn-success text-white btn-lg mx-2" >حفظ الفاتورة</button>
            <a href="{{route('selling_invoices.index')}}" class="btn btn-danger btn-lg mx-2" >الغاء الفاتورة</a>
            <a href="{{route('dashboard')}}" target="__blank" class="btn btn-secondary btn-lg mx-2">تعليق الفاتورة</a>
            
        </div>

    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#customer_id').on('change', function (e) {
            var customer_id = $('#customer_id').select2("val");
            @this.set('customer_id', customer_id);
        });
    });
</script>
@endpush
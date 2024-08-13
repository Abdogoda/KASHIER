<div>
    <hr>
    <form wire:submit.prevent="saveInvoice">
        <h4>منتجات الفاتورة</h4>
        <div class="table-responsive pb-3">
            <table class="teble table-stripe table-bordered w-100 text-nowrap">
                <thead class="table-dark">
                    <tr class="">
                        <th class="text-center p-2">اسم المنتج</th>
                        <th class="text-center p-2">الكمية المشتراة</th>
                        <th class="text-center p-2">الكمية المسترجعة</th>
                        <th class="text-center p-2">سعر الشراء</th>
                        <th class="text-center p-2">سعر الاسترجاع</th>
                        <th class="text-center p-2">المجموع</th>
                        <th class="text-center p-2">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            <td class="p-2">{{$product['name']}}</td>
                            <td class="p-2 text-center">{{$product['sold_quantity']}}</td>
                            <td class="p-2">
                                <input type="number" wire:model.change="products.{{ $index }}.quantity" max="{{$product['sold_quantity']}}" required class="form-control" min="0">
                                @error("products.$index.quantity") <span class="text-danger">{{ $message }}</span> @enderror
                            </td>
                            <td class="p-2 text-center">{{$product['sold_price']}}</td>
                            <td class="text-center p-2">
                                <input type="number" wire:model.change="products.{{ $index }}.price" max="{{$product['sold_price']}}" required class="form-control" min="0">
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

        <hr>
        <h4 class="pt-3">حساب الفاتورة</h4>
        <div class="row py-3 bg-light">
            {{-- money row --}}
            <div class="col-6 col-md-4 col-lg-2 form-group mb-3">
                <label for="original_cost" >ثمن الفاتورة الأصلية</label>
                <input type="number" class="form-control" readonly min="0" step="0.01" id="original_cost" wire:model="original_cost">
            </div>
            <div class="col-6 col-md-4 col-lg-2 form-group mb-3">
                <label for="paid" >المدفوع من الفاتورة الأصلية</label>
                <input type="number" class="form-control" readonly min="0" step="0.01" id="paid" wire:model="paid">
            </div>
            <div class="col-6 col-md-4 col-lg-2 form-group mb-3">
                <label for="original_remaining" >المتبقي من الفاتورة الأصلية</label>
                <input type="number" class="form-control" readonly min="0" step="0.01" id="original_remaining" wire:model="original_remaining">
            </div>
            <div class="col-6 col-md-4 col-lg-2 form-group mb-3">
                <label for="return_value" >المبلغ المسترجع</label>
                <input type="number" class="form-control" readonly min="0" step="0.01" id="return_value" wire:model="return_value">
            </div>
            <div class="col-6 col-md-4 col-lg-2 form-group mb-3">
                <label for="invoice_remaining" >المخصوم للفاتورة الأصلية</label>
                <input type="number" class="form-control" readonly min="0" step="0.01" id="invoice_remaining" wire:model="invoice_remaining">
            </div>
            <div class="col-6 col-md-4 col-lg-3 form-group mb-3">
                <label for="invoice_difference" >المتبقي علي العميل</label>
                <input type="number" class="form-control text-white bg-danger" readonly min="0" step="0.01" id="invoice_difference" wire:model="invoice_difference">
            </div>
            <div class="col-6 col-md-4 col-lg-3 form-group mb-3">
                <label for="remaining_for_customer" >المتبقي للعميل</label>
                <input type="number" class="form-control text-white bg-success" readonly min="0" step="0.01" id="remaining_for_customer" wire:model="remaining_for_customer">
            </div>
            {{--  --}}
            <div class="col-6 col-md-4 col-lg-3 form-group mb-3">
                <label for="original_account" >الحساب السابق</label>
                <input type="number" class="form-control text-white bg-warning" readonly min="0" step="0.01" id="original_account" wire:model="original_account">
            </div>
            <div class="col-6 col-md-4 col-lg-3 form-group mb-3">
                <label for="account" >الحساب الحالي</label>
                <input type="number" class="form-control text-white bg-{{$account > 0 ? 'success' : 'danger'}}" readonly min="0" step="0.01" id="account" wire:model="account">
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center gap-3 pt-3">
            <button type="button" data-toggle="modal" data-target="#payment_status_modal" class="btn btn-success text-white btn-lg mx-2" >استرجاع الفاتورة</button>
            <button type="button" class="btn btn-danger btn-lg mx-2" onclick="return location.reload();">الغاء الاسترجاع</button>

            <div class="modal fade" id="payment_status_modal" tabindex="-1" role="dialog"
            aria-labelledby="payment_status_modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="payment_status_modalLabel">استرجاع للفاتورة رقم: {{$invoice->invoice_number}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"> 
                            <h2> هل أنت متأكد من استرجاع هذة الفاتورة؟</h2>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                            <button type="submit" class="btn mb-2 btn-primary">تأكيد</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
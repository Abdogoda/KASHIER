@extends('layouts.print-layout')
@section('title')
فاتورة {{$invoice->is_returned != 0 ? 'مرتجع' : ''}} شراء
@endsection

@section('details')
    <div class="info">
        <table>
            <tr>
                <td>الرقم</td>
                <td>{{$invoice->invoice_number}}</td>
            </tr>
            <tr>
                <td>التاريخ</td>
                <td>{{$invoice->invoice_date}} - {{formatTime($invoice->invoice_time)}}</td>
            </tr>
            <tr>
                <td>الكاشير</td>
                <td>{{$invoice->employee->name}}</td>
            </tr>
        </table>
    </div>
    <div class="mt-2">
        <p class='text-bold'>تفاصيل المورد:</p>
        @if ($invoice->customer_type == 'مورد')
            <p>الاسم: {{$invoice->customer_name}}</p>
            <p>الهاتف: {{$invoice->customer_phone}}</p>
            <p>العنوان: {{$invoice->customer->address}}</p>
        @else
            <p>مورد نقدي</p>
        @endif
    </div>
@endsection

@section('products')
    <table class="text-center products-table">
        <thead>
            <tr>
                <td class="text-bold">اسم المنتج</td>
                <td class="text-bold">السعر</td>
                <td class="text-bold">الكمية</td>
                <td class="text-bold">الاجمالي</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->products as $invoice_product)
                <tr>
                    <td>{{$invoice_product->product->name}}</td>
                    <td>{{$invoice_product->price}}</td>
                    <td>{{$invoice_product->quantity}}</td>
                    <td>{{$invoice_product->total_price}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('bottom')

<div class="info mt-2">
    <table>
        <tr>
            <td class="bg-light">طريقة الدفع</td>
            <td class="text-center">{{$invoice->payment_method}}</td>
            <td class="bg-light">حالة الدفع</td>
            <td class="text-center">{{$invoice->payment_status}}</td>
        </tr>
        <tr>
            <td class="bg-light">الاجمالي</td>
            <td class="text-center">{{$invoice->total_cost}}</td>
            <td class="bg-light">عدد القطع</td>
            <td class="text-center">{{$invoice->product_count ?? 0}}</td>
        </tr>
        @if ($invoice->is_returned != 0)
            <tr>
                <td class="bg-light">المسترجع</td>
                <td class="text-center">{{$invoice->return_value}}</td>
                <td class="bg-light">سعر الفاتورة بعد الاسترجاع</td>
                <td class="text-center">{{$invoice->total_cost - $invoice->return_value}}</td>
            </tr>
        @endif
    </table>
</div>
    <div class="invoice-body-bottom mt-2">
        <div class="invoice-body-item grid-2 border-bottom">
            <div class="item-td text-center"> <span class="text-bold">المدفوع:</span> {{$invoice->paid}}</div>
            @if ($invoice->over_paid > 0 && $invoice->is_returned == 0)
                <div class="item-td text-center"> <span class="text-bold">المدفوع فوق الحساب:</span> {{$invoice->over_paid}}</div>
            @endif
            <div class="item-td text-center"> <span class="text-bold">المتبقي:</span> {{$invoice->remaining}}</div>
        </div>
        @if ($invoice->supplier)
            <div class="invoice-body-item grid-2 border-bottom">
                @if ($invoice->is_returned == 0)
                    <div class="item-td text-center"> <span class="text-bold">حساب سابق:</span> {{$invoice->account_before}}</div>
                @endif
                <div class="item-td text-center"> <span class="text-bold">حساب حالي:</span> {{$invoice->supplier->account}}</div>
            </div>
        @endif
    </div>
@endsection
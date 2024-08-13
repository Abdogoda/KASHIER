@extends('layouts.print-layout')
@section('title')
    فاتورة سند {{$invoice->type}}
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
@endsection

@section('products')
    <table class="text-center products-table">
        <thead>
            <tr>
                <td class="text-bold">المبلغ</td>
                <td class="text-bold">البيان</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$invoice->cost - $invoice->return_value}}</td>
                <td>{{$invoice->description}}</td>
            </tr>
        </tbody>
    </table>
@endsection
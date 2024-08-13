@extends('layouts.print-layout')
@section('title')
ايصال {{$transaction['type']}} دفعة نقدية
@endsection

@section('details')
    <div class="info">
        <table>
            <tr>
                <td>الرقم</td>
                <td>{{$transaction['number']}}</td>
                <td>الكاشير</td>
                <td>{{$transaction['employee']}}</td>
            </tr>
            <tr>
                <td>التاريخ</td>
                <td colspan="3">{{$transaction['date']}} - {{formatTime($transaction['time'])}}</td>
            </tr>
            <tr>
                <td>العميل</td>
                <td colspan="3">{{$transaction['customer']}}</td>
            </tr>
        </table>
    </div>
    <div class="info mt-2">
        <p class='text-bold'>تفاصيل الحساب:</p>
        <table>
            <tr>
                <td>حساب سابق</td>
                <td>{{$transaction['formal_account']}}</td>
            </tr>
            <tr>
                <td class="bg-light">المبلغ المدفوع / المستلم</td>
                <td class="bg-light">{{$transaction['paid']}}</td>
            </tr>
            <tr>
                <td>حساب حالي</td>
                <td>{{$transaction['account']}}</td>
            </tr>
        </table>
    </div>
@endsection

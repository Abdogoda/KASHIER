@extends('layouts.app')
@section('title') استرجاع فاتورة بيع رقم: {{$sellingInvoice->invoice_number}} @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">استرجاع فاتورة بيع رقم: {{$sellingInvoice->invoice_number}}</h3>
                <a href="{{route('selling_invoices.show', $sellingInvoice)}}" class="btn btn-primary"> عرض الفاتورة</a>
            </div>

            {{-- livewire component --}}
            @livewire('return-selling-invoice', ['invoice'=>$sellingInvoice])
        </div>
    </div>
@endsection

@section('js')

@endsection
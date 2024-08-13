@extends('layouts.app')
@section('title') اضافة فاتورة شراء جديدة @endsection
@section('css')
    <link href="{{asset('assets/select-2/select2.min.css')}}" rel="stylesheet" />
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">اضافة فاتورة شراء جديدة</h3>
                <a href="{{route('purchasing_invoices.index')}}" class="btn btn-primary"> عرض جميع فواتير الشراء</a>
            </div>

            {{-- livewire component --}}
            @livewire('create-purchasing-invoice')
        </div>
    </div>
@endsection

@section('js')

<script src="{{asset('assets/select-2/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#supplier_id').select2();
    })
</script>
@endsection
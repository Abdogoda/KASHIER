@extends('layouts.app')
@section('title') اضافة فاتورة بيع جديد @endsection
@section('css')
    <link href="{{asset('assets/select-2/select2.min.css')}}" rel="stylesheet" />
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">اضافة فاتورة بيع جديد</h3>
                <a href="{{route('selling_invoices.index')}}" class="btn btn-primary"> عرض جميع فواتير البيع</a>
            </div>

            {{-- livewire component --}}
            @livewire('create-selling-invoice')
        </div>
    </div>
@endsection

@section('js')

<script src="{{asset('assets/select-2/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#customer_id').select2();
    })
</script>
@endsection
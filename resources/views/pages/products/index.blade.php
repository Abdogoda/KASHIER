@extends('layouts.app')
@section('title') المنتجات @endsection
@section('css')
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/buttons.dataTables.min.css')}}" />
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">جميع المنتجات</h3>
                <a href="{{route('products.create')}}" class="btn btn-primary">اضافة منتج جديد <i class="fe fe-plus" style="margin-right: 10px"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover cell-border stripe text-center  py-3" id="dataTable-1" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">رقم المنتج</th>
                            <th class="text-center">الاسم</th>
                            <th class="text-center">سعر الشراء</th>
                            @foreach ($segments as $segment)
                                <th class="text-center">{{$segment->name}}</th>
                            @endforeach
                            <th class="text-center">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{$product->id}}</td>
                                <td><a href="{{route('products.show', $product)}}">{{$product->name}}</a></td>
                                <td>{{$product->purchasing_price}}</td>
                                @foreach ($product->segments as $product_segment)
                                    <th class="text-center">{{$product_segment->segment_price}}</th>
                                @endforeach
                                <td class="text-center"><span class="badge badge-pill badge-{{$product->status == 'مفعل' ? 'success' : 'danger'}} text-white">{{$product->status}}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{asset('assets/dataTablesFolder/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/jszip.min.js')}}"></script>

<script src="{{asset('assets/dataTablesFolder/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/dataTablesFolder/js/buttons.print.min.js')}}"></script>
<script>
    $('#dataTable-1').DataTable(
      {
        responsive: true,
        autoWidth: true,
        dom: "Bfrtip",
        buttons: ["copy", "excel", {
            extend:"print",
            title: function() {
                var pageTitle = document.title;
                var currentDate = new Date().toISOString().split('T')[0];
                return pageTitle + ' | ' + currentDate;
            },
            customize: function (win) {
                $(win.document.body).css('direction', 'rtl');
                $(win.document.body).find('table').addClass('compact').css('font-size', '25px').css('text-align', 'right');
            }
        }],
        "lengthMenu": [
          [20, 40, 80, -1],
          [20, 40, 80, "All"]
        ],
        order: [[0, 'desc']],
        language: {
            url: `{{asset('assets/dataTablesFolder/datatables.ar.json')}}`,
        }
      });
  </script>
@endsection

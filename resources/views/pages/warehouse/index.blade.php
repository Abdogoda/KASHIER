@extends('layouts.app')
@section('title') المخزن @endsection
@section('css')
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/buttons.dataTables.min.css')}}" />
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">ادارة المخزن</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover cell-border stripe text-center  py-3" id="dataTable-1" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">رقم المنتج</th>
                            <th class="text-center">الاسم</th>
                            <th class="text-center">الرصيد الافتتاحي</th>
                            <th class="text-center">الوارد للمخزن</th>
                            <th class="text-center">الكمية المنصرفة</th>
                            <th class="text-center">الرصيد</th>
                            <th class="text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($warehouse_products as $warehouse_product)
                            <tr>
                                <td>{{$warehouse_product->product->id}}</td>
                                <td><a href="{{route('products.show', $warehouse_product->product_id)}}">{{$warehouse_product->product->name}}</a></td>
                                <td>{{$warehouse_product->opening_balance}}</td>
                                <td>{{$warehouse_product->incoming_balance}}</td>
                                <td>{{$warehouse_product->outgoing_balance}}</td>
                                <td class="text-{{$warehouse_product->balance < 5 ? 'danger' : 'success'}}">{{$warehouse_product->balance}}</td>
                                <td>
                                    @can('edit_warehouse')
                                        <button type="button" data-toggle="modal" data-target="#edit_product_{{$warehouse_product->id}}" class="btn btn-secondary">تغيير قيمة الرصيد الافتتاحي</لا>
                                    @endcan
                                </td>
                            </tr>

                            @can('edit_warehouse')
                                <div class="modal fade" id="edit_product_{{$warehouse_product->id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="edit_product_{{$warehouse_product->id}}Label" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="edit_product_{{$warehouse_product->id}}Label">تغيير قيمة الرصيد الافتتاحي</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('warehouse.update', $warehouse_product)}}" method="post">
                                                @csrf
                                                @method('patch')
                                                <input type="hidden" name="id" value="{{$warehouse_product->id}}">
                                                <div class="modal-body"> 
                                                    <div class="col-md-12 mb-3 form-group">
                                                        <label for="opening_balance">الرصيد الافتتاحي في المخزن</label>
                                                        <input type="number" min="0" name="opening_balance" value="{{$warehouse_product->opening_balance}}" class="form-control" id="opening_balance" autocomplete="opening_balance">
                                                        @error('opening_balance')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                                                    <button type="submit" class="btn mb-2 btn-primary">حفظ التغيرات</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endcan
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
            customize: function (win) {
                $(win.document.body).find('table').addClass('compact').css('font-size', '10px');
            }
        }],
        "lengthMenu": [
          [16, 32, 64, -1],
          [16, 32, 64, "All"]
        ],
        order:[[1, 'asc']],
        language: {
            url: `{{asset('assets/dataTablesFolder/datatables.ar.json')}}`,
        }
      });
  </script>
@endsection

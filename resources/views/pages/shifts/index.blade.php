@extends('layouts.app')
@section('title') الورديات @endsection
@section('css')
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/buttons.dataTables.min.css')}}" />
@endsection
@section('content')
    @if($current_shift)
        <div class="card shdow border-0 mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span><b>وقت بدء الوردية:</b> {{ $current_shift->day }} {{ $current_shift->start_date }} {{formatTime($current_shift->start_time)}} </span>
                    @can('end_shift')
                        <form action="{{route('shifts.update', $current_shift)}}" method="post">
                            @csrf
                            @method('put')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من أنك تريد انهاء الوردية الجارية؟')">انهاء الوردية</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @endif
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">الورديات</h3>
                @if (!$current_shift)
                    <button type="button" class="btn btn-success text-white" data-toggle="modal" data-target="#start_shift" >بدء وردية جديد <i class="fe fe-plus-circle" style="margin-right: 10px"></i></button>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-hover cell-border text-nowrap stripe text-center  py-3" id="dataTable-1" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">اسم الموظف</th>
                            <th class="text-center">اليوم</th>
                            <th class="text-center">تاريخ البدء</th>
                            <th class="text-center">وقت البدء</th>
                            <th class="text-center">تاريخ الانتهاء</th>
                            <th class="text-center">وقت الانتهاء</th>
                            <th class="text-center">المبلغ الافتتاحي</th>
                            <th class="text-center">المبلغ الكلي</th>
                            <th class="text-center">المبلغ المضاف</th>
                            <th class="text-center">المبلغ المسحوب</th>
                            <th class="text-center">العجز / الزيادة</th>
                            <th class="text-center">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shifts as $shift)
                            <tr>
                                <td><a href="{{route('shifts.show', $shift)}}">{{$shift->id}}</a></td>
                                <td><a href="{{route('employees.show', $shift->employee)}}">{{$shift->employee->name}}</a></td>
                                <td>{{$shift->day}}</td>
                                <td>{{$shift->start_date}}</td>
                                <td>{{formatTime($shift->start_time)}}</td>
                                <td>{{$shift->end_date}}</td>
                                <td>{{formatTime($shift->end_time)}}</td>
                                <td>{{$shift->initial_amount}}</td>
                                <td>{{$shift->total_amount}}</td>
                                <td class="table-success">{{$shift->added_amount}}</td>
                                <td class="table-warning">{{$shift->withdraw_amount}}</td>
                                <td class="table-{{$shift->difference_amount >= 0 ? 'success' : 'danger'}}">{{$shift->difference_amount}}</td>
                                <td class="text-center"><span class="badge badge-pill badge-{{$shift->status == 'جارية' ? 'warning' : 'success'}} text-white">{{$shift->status}}</span></td>
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
            customize: function (win) {
                $(win.document.body).css('direction', 'rtl');
                $(win.document.body).find('table').addClass('compact').css('font-size', '10px').css('text-align', 'right');
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

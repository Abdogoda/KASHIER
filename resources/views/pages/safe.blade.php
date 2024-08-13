@extends('layouts.app')
@section('title') الخزنة @endsection
@section('css')
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/dataTablesFolder/css/buttons.dataTables.min.css')}}" />
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
               <h3 class="card-title">الخزنة</h3>
            </div>
            <div class="row mb-3">
               <div class="col-md-6 mb-4">
                <div class="card shadow">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary">
                          <i class="fe fe-16 fe-pocket text-white mb-0"></i>
                        </span>
                      </div>
                      <div class="col pr-0">
                        <p class="text-muted mb-0">رصيد الخزنة الحالي</p>
                        <span class="h3 mb-0">{{$balance}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 my-4">
     <div class="card-body">
      <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
       <h3>{{request('date') ? 'معاملات يوم : '.request('date') : 'اخر معاملات الخزنة'}}</h3>
       <div class="d-flex gap-2">
        @if (request('date'))
          <a href="{{route('safe')}}" class="btn btn-outline-danger mr-2">حذف اعدادات البحث</a>
        @endif
        <button class="btn btn-primary" data-toggle="modal" data-target="#search_transactions">البحث في المعاملات</button>
       </div>
      </div>
      <div class="table-responsive mt-3">
        <table class="table table-hover cell-border stripe text-center text-nowrap py-3 dataTable-1" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">التوقيت</th>
                    <th class="text-center">الموظف</th>
                    <th class="text-center">الوردية</th>
                    <th class="text-center">نوع المعاملة</th>
                    <th class="text-center">نوع الفاتورة</th>
                    <th class="text-center">رقم الفاتورة</th>
                    <th class="text-center">الحساب قبل المعاملة</th>
                    <th class="text-center">قيمة المعاملة</th>
                    <th class="text-center">الحساب بعد المعاملة</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center">{{$transaction->created_at->diffForHumans()}}</td>
                        <td class="text-center"><a href="{{route('employees.show', $transaction->employee)}}">{{$transaction->employee->name}}</a></td>
                        <td class="text-center"><a href="{{route('shifts.show', $transaction->shift)}}">{{$transaction->shift->id}}</a></td>
                        <td class="text-center"><h5><span class="badge text-white bg-{{$transaction->payment_type == 'قبض' ? 'success' : 'danger'}}">{{$transaction->payment_type}}</span></h5></td>
                        <td class="text-center">{{$transaction->type}}</td>
                        <?php 
                        $url = null;
                          if($transaction->type == 'بيع'){
                           $url = 'selling_invoices';
                           $invoice = \App\Models\SellingInvoice::find($transaction->invoice_id);
                          }elseif($transaction->type == 'شراء'){
                           $url = 'purchasing_invoices';
                           $invoice = \App\Models\PurchasingInvoice::find($transaction->invoice_id);
                          }elseif($transaction->type == 'سند قبض' || $transaction->type == 'سند صرف' ){
                            $url = 'invoices';
                            $invoice = \App\Models\Invoice::find($transaction->invoice_id);
                          }
                        ?>
                        <td class="text-center"><a href="{{$url ? route($url.'.show', $invoice) : '#'}}">{{$transaction->invoice_id}}</a></td>
                        <td class="text-center">{{$transaction->before_balance}}</td>
                        <td class="text-center text-{{$transaction->payment_type == 'قبض' ? 'success' : 'danger'}}">{{$transaction->amount}}</td>
                        <td class="text-center">{{$transaction->after_balance}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
      </div>
     </div>
    </div>

      {{-- start payment status modal --}}
  <div class="modal fade" id="search_transactions" tabindex="-1" role="dialog" aria-labelledby="search_transactionsLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title" id="start_shiftLabel">ابحث عن المعاملات باليوم</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
             <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <form action="{{route('safe')}}" method="get">
             @csrf
             <div class="modal-body"> 
               <div class="mb-3 form-group">
                   <label for="date">التاريخ</label>
                   <input type="date" value="{{request('date')}}" name="date" class="form-control" id="date">
               </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                 <button type="submit" class="btn mb-2 btn-success text-white">بحث</button>
             </div>
         </form>
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
  $('.dataTable-1').DataTable(
      {
        responsive: true,
        autoWidth: true,
        dom: "Bfrtip",
        buttons: ["copy", "excel", {
            extend:"print",
            customize: function (win) {
                $(win.document.body).css('direction', 'rtl');
                $(win.document.body).find('table').addClass('compact').css('font-size', '22px').css('text-align', 'right');
            }
        }],
        "lengthMenu": [
          [20, 40, 80, -1],
          [20, 40, 80, "All"]
        ],
        language: {
            url: `{{asset('assets/dataTablesFolder/datatables.ar.json')}}`,
        }
      });
</script>
@endsection
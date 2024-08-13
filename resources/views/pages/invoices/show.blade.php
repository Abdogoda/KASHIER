@extends('layouts.app')
@section('title') فاتورة سند {{$invoice->type}} رقم : {{$invoice->invoice_number}} @endsection
@section('css')
@endsection
@section('content')
    
  <div class="card shadow border-0">
    <div class="card-body">
      <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
          <h3 >فاتورة سند {{$invoice->type}} رقم : {{$invoice->invoice_number}}</h3>
          <a href="{{route('invoices.index')}}" class="btn btn-primary">عرض فواتير السند </a>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-4">
          <div class="list-group px-0 mb-4">
            <div class="list-group-item">
              <div class="row align-items-center">
                <div class="col">
                  <h4 class="mb-2">رقم الفاتورة</h4>
                  <p class="text-muted mb-0">{{$invoice->invoice_number}}</p>
                </div>
              </div> 
            </div>
            <div class="list-group-item">
              <div class="row align-items-center">
                <div class="col">
                  <h4 class="mb-2">تاريخ الفاتورة</h4>
                  <p class="text-muted mb-0">{{formatArabicDate($invoice->invoice_date)}} | {{formatTime($invoice->invoice_time)}}</p>
                </div>
              </div> 
            </div>
            @if ($invoice->is_returned != 0)
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col">
                    <h4 class="mb-2">وقت الاسترجاع</h4>
                    <p class="text-muted mb-0">{{$invoice->return_date_time}}</p>
                  </div>
                </div> 
              </div>
            @endif
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="list-group px-0 mb-4">
            <div class="list-group-item">
              <div class="row align-items-center">
                <div class="col">
                  <h4 class="mb-2">رقم الوردية</h4>
                  <p class="text-muted mb-0"><a href="{{route('shifts.show', $invoice->shift)}}">{{$invoice->shift_id}}</a></p>
                </div>
              </div> 
            </div>
            <div class="list-group-item">
              <div class="row align-items-center">
                <div class="col">
                  <h4 class="mb-2">اسم الموظف</h4>
                  <p class="text-muted mb-0"><a href="{{route('employees.show', $invoice->employee)}}">{{$invoice->employee->name}}</a></p>
                </div>
              </div> 
            </div>
            @if ($invoice->is_returned != 0)
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col">
                    <h4 class="mb-2">نوع الاسترجاع</h4>
                    <p class="text-white mb-0"><span class="badge bg-{{$invoice->is_returned == 1 ? 'danger' : 'warning'}}">{{$invoice->is_returned == 1 ? 'استرجاع كلي' : 'استرجاع جزئي'}}</span></p>
                  </div>
                </div> 
              </div>
            @endif
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="list-group px-0 mb-4">
            <div class="list-group-item">
              <div class="row align-items-center">
                <div class="col">
                  <h4 class="mb-2">نوع الفاتورة</h4>
                  <p class="text-white mb-0"><span class="badge bg-{{$invoice->type == 'قبض' ? 'success' : 'danger'}}">{{$invoice->type}}</span></p>
                </div>
              </div> 
            </div>
            <div class="list-group-item">
              <div class="row align-items-center">
                <div class="col">
                  <h4 class="mb-2">تكلفة الفاتورة</h4>
                  <p class="text-muted mb-0">{{$invoice->cost}}</p>
                </div>
              </div> 
            </div>
            @if ($invoice->is_returned != 0)
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col">
                    <h4 class="mb-2">قيمة الاسترجاع</h4>
                    <p class="text-muted mb-0">{{$invoice->return_value}}</p>
                  </div>
                </div> 
              </div>
            @endif
          </div>
        </div>
        <div class="col-12">
          <div class="list-group px-0 mb-4">
            <div class="list-group-item">
              <div class="row align-items-center">
                <div class="col">
                  <h4 class="mb-2">وصف الفاتورة</h4>
                  <p class="text-muted mb-0">{{$invoice->description}}</p>
                </div>
              </div> 
            </div>
          </div>
        </div>
      </div>
      <div class="">
        @can('print_invoice')
            <a href="{{route('invoices.print', $invoice)}}" class="btn btn-success mt-2 text-white" target="__blank">طباعة الفاتورة <i class="fe fe-printer ml-2"></i></a>
        @endcan
        @can('return_invoice')
            @if (Carbon\Carbon::parse($invoice->invoice_date)->diffInDays(Carbon\Carbon::now()) <= 5 && $invoice->is_returned == 0)
                <a href="{{route('invoices.edit', $invoice)}}" class="btn ml-2 mt-2 btn-danger" data-toggle="modal" data-target="#return_invoice">استرجاع الفاتورة <i class="fe fe-corner-down-left ml-2"></i></a>
            @endif
        @endcan
      </div>
    </div>
  </div>


  {{-- start payment status modal --}}
  <div class="modal fade" id="return_invoice" tabindex="-1" role="dialog" aria-labelledby="return_invoiceLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="start_shiftLabel">استرجاع فاتورة سند رقم: {{$invoice->invoice_number}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form action="{{route('invoices.update', $invoice)}}" method="post">
                  @csrf
                  @method('patch')
                  <div class="modal-body"> 
                    <h3>هل تريد استرجاع فاتورة سند ال{{$invoice->type}}؟</h3>
                    <div class="mb-3 form-group">
                        <label for="cost">القيمة الأصلية</label>
                        <input type="number" value="{{$invoice->cost}}" min="0" step="0.01" readonly name="cost" class="form-control" id="cost">
                    </div>
                    <div class="mb-3 form-group">
                        <label for="return_value">المسترجع</label>
                        <input type="number" value="{{$invoice->cost}}" max="{{$invoice->cost}}" min="0" step="0.01" name="return_value" class="form-control" id="return_value">
                    </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                      <button type="submit" class="btn mb-2 btn-danger">استرجاع الفاتورة</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
@endsection 

@section('js')
@endsection
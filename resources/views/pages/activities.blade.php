@extends('layouts.app')
@section('title') العمليات علي السيستم @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0 timeline">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">العمليات علي السيستم</h3>
                <button type="button" data-toggle="modal" data-target="#search_for_activities" class="btn btn-primary">البحث عن عمليات</button>
            </div>
            @if ($activities)
                <h2 class="text-uppercase text-muted my-4">
                    {{request('date') ? request('date') : ''}} - {{request('type') ? request('type') : ''}}   ({{$activities->count()}})
                </h2>
                <div class="row pb-3">
                    @forelse ($activities as $activity)
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="pb-3 timeline-item item-{{$activities_colors[$activity->type]}}">
                                <div class="pl-5">
                                    <div class="mb-3"><strong class="mr-2"><a href="{{route('employees.show', $activity->employee)}}">{{$activity->employee->name}}</a></strong> - <span class="mx-2 small text-{{$activities_colors[$activity->type]}}">{{$activity->type}} </span><span class="badge badge-light">{{$activity->created_at}}</span></div>
                                    <div class="card d-inline-flex mb-2">
                                        <div class="card-body bg-light py-2 px-3">{{$activity->action}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12 text-center text-muted">لا يوجد عمليات علي السيستم لهذا البحث</div>
                    @endforelse
                </div>
                <a href="{{route('activities.index')}}" class="btn btn-outline-danger">حذف اعدادات البحث</a>
                <hr>
            @else
                <h2 class="text-uppercase text-muted my-4">اليوم ({{$todayActivities->count()}})</h2>
                <div class="row pb-3">
                    @forelse ($todayActivities as $activity)
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="pb-3 timeline-item item-{{$activities_colors[$activity->type]}}">
                                <div class="pl-5">
                                    <div class="mb-3"><strong class="mr-2"><a href="{{route('employees.show', $activity->employee)}}">{{$activity->employee->name}}</a></strong> - <span class="mx-2 small text-{{$activities_colors[$activity->type]}}">{{$activity->type}}</span> <span class="badge badge-light">{{$activity->created_at->diffForHumans()}}</span></div>
                                    <div class="card d-inline-flex mb-2">
                                        <div class="card-body bg-light py-2 px-3">{{$activity->action}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12 text-center text-muted">لا يوجد عمليات علي السيستم لهذا اليوم</div>
                    @endforelse
                </div>
                <hr>
                <h2 class="text-uppercase text-muted my-4">أمس ({{$yesterdayActivities->count()}})</h2>
                <div class="row pb-3">
                    @forelse ($yesterdayActivities as $activity)
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="pb-3 timeline-item item-{{$activities_colors[$activity->type]}}">
                                <div class="pl-5">
                                    <div class="mb-3"><strong class="mr-2"><a href="{{route('employees.show', $activity->employee)}}">{{$activity->employee->name}}</a></strong> - <span class="mx-2 small text-{{$activities_colors[$activity->type]}}">{{$activity->type}}</span> <span class="badge badge-light">{{$activity->created_at->diffForHumans()}}</span></div>
                                    <div class="card d-inline-flex mb-2">
                                        <div class="card-body bg-light py-2 px-3">{{$activity->action}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12 text-center text-muted">لا يوجد عمليات علي السيستم لهذا اليوم</div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>


        {{-- start search_for_activities modal --}}
        <div class="modal fade" id="search_for_activities" tabindex="-1" role="dialog"
        aria-labelledby="payment_statusLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="start_shiftLabel">البحث عن عمليات</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('activities.index')}}" method="get">
                    @csrf
                    <div class="modal-body"> 
                        <div class="mb-3 form-group">
                            <label for="date">اليوم</label>
                            <input type="date" name="date" value="{{request('date')}}" class="form-control" id="date">
                        </div>
                        <div class="mb-3 form-group">
                            <label for="type">المتبقي</label>
                            <select name="type" id="type" class="form-control">
                                <option value="{{null}}">الكل</option>
                                <option value="الوردية" {{ request('type') == 'الوردية' ? 'selected' : '' }}>الوردية</option>
                                <option value="الفواتير" {{ request('type') == 'الفواتير' ? 'selected' : '' }}>الفواتير</option>
                                <option value="المصادقة" {{ request('type') == 'المصادقة' ? 'selected' : '' }}>المصادقة</option>
                                <option value="الموظفين" {{ request('type') == 'الموظفين' ? 'selected' : '' }}>الموظفين</option>
                                <option value="العملاء" {{ request('type') == 'العملاء' ? 'selected' : '' }}>العملاء</option>
                                <option value="الموردين" {{ request('type') == 'الموردين' ? 'selected' : '' }}>الموردين</option>
                                <option value="المنتجات" {{ request('type') == 'المنتجات' ? 'selected' : '' }}>المنتجات</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
                        <button type="submit" class="btn mb-2 btn-primary">بحث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end search_for_activities modal --}}
@endsection

@section('js')
@endsection

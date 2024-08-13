@extends('layouts.app')
@section('title') الموظفين @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">جميع الموظفين</h3>
                <a href="{{route('employees.create')}}" class="btn btn-primary">اضافة موظف جديد <i class="fe fe-user-plus" style="margin-right: 10px"></i></a>
            </div>
            <div class="row">
                @foreach ($employees as $employee)
                    <div class="col-md-3">
                        <div class="card shadow mb-4">
                            <div class="card-body text-center">
                                <div class="avatar avatar-lg mt-4">
                                    <a href="{{route('employees.show', $employee)}}">
                                        <img src="{{asset('assets/images/icons/profile.png')}}" alt="employee-image" class="avatar-img rounded-circle">
                                    </a>
                                </div>
                                <div class="card-text my-2">
                                    <strong class="card-title my-0">{{$employee->name}}</strong>
                                    <p class="text-muted mb-0">{{$employee->phone}}</p>
                                    <p class="text-muted mb-0">{{$employee->email}}</p>
                                    <p><span class="badge badge-success text-white">{{$employee->role->name}}</span></p>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto">
                                        <small><span class="dot dot-lg bg-{{$employee->status == 'مفعل' ? 'success' : 'danger'}} mr-1"></span> {{$employee->status}} </small>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{route('employees.show', $employee)}}" class="btn btn-sm btn-primary">عرض</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
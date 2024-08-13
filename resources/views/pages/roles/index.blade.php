@extends('layouts.app')
@section('title') الوظائف @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title">جميع الوظائف</h3>
                @can('add_role')
                    <a href="{{route('roles.create')}}" class="btn btn-primary">اضافة وظيفة جديد <i class="fe fe-plus" style="margin-right: 10px"></i></a>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">الاسم</th>
                            <th class="text-center">الصلاحيات</th>
                            <th class="text-center">الموظفين</th>
                            <th class="text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$role->name}}</td>
                                <td>{{$role->permissions->count()}}</td>
                                <td>{{$role->employees->count()}}</td>
                                <td><a href="{{route('roles.show', $role)}}" class="btn btn-secondary">عرض</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">لا يوجد وظائف متاحة</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
@extends('layouts.app')
@section('title') عرض وظيفة ال{{$role->name}} @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
               <h3 class="card-title">عرض وظيفة ال{{$role->name}}</h3>
               <a href="{{route('roles.index')}}" class="btn btn-primary"> عرض جميع الوظائف</a>
            </div>
            <form action="{{route('roles.update', $role)}}" method="post">
             @csrf
             @method('put')
             <div class="row">
              <div class="col-md-12 mb-3 form-group">
               <label for="name">اسم الوظيفة <span class="text-danger">*</span></label>
               <input type="text" name="name" value="{{$role->name}}" class="form-control" id="name" autocomplete="name" autofocus>
               @error('name')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-12 mb-3 form-group">
               <label for="permissions">صلاحيات الوظيفة <span class="text-danger">*</span></label>
               <div class="row mt-3">
                @forelse ($permissions as $permission)
                    <label class="py-2 px-3 m-1 rounded border shadow {{ in_array($permission->id, $rolePermissions) ? 'bg-success text-white' : '' }}" for="permission{{$permission->id}}">
                        {{$permission->ar_name}}
                        <input type="checkbox" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} class="d-none" id="permission{{$permission->id}}" name="permissions[]" value="{{ $permission->id }}">
                    </label>
                @empty
                    <div class="m-auto p-2 shadow border rounded text-center text-muted">لا يوجد صلاحيات</div>
                @endforelse
               </div>
               @error('permissions')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="form-group col-md-6">
               <button type="submit" class="btn btn-primary btn-lg">تعديل الوظيفة</button>
               @if ($role->employees->count() == 0)
                   <button type="button"  data-toggle="modal" data-target="#deleterole" class="btn btn-danger btn-lg mx-2">حذف الوظيفة</button>
               @endif
              </div>
             </div>
            </form>
        </div>
    </div>

    <div class="card shadow border-0 mt-4">
      <div class="card-body">
        <h3 class="card-title">الموظفين المتربطين بهذة الوظيفة</h3>
        <div class="row">
          <ul class="mt-3 mb-0">
            @forelse ($role->employees as $employee)
              <li><a href="{{route('employees.show', $employee)}}">{{$employee->name}}</a></li>
          @empty
              <p class="text-danger">لا يوجود موظفين لهذة الوظيفة</p>
          @endforelse
          </ul>
        </div>
      </div>
    </div>


    <div class="modal fade" id="deleterole" tabindex="-1" role="dialog" aria-labelledby="deleteroleLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteroleLabel">حذف الوظيفة</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="اغلاق">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{route('roles.delete-role', $role)}}" method="post">
            @csrf
            <div class="modal-body">
              <h3>هل انت متأكد من أنك تريد حذف هذة الوظيفة؟</h3>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">اغلاق</button>
              <button type="submit" class="btn mb-2 btn-danger">حذف</button>
            </div>
          </form>
        </div>
      </div>
    </div>
@endsection



@section('js')
<script>
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            console.log(1);
            const label = document.querySelector(`label[for="${this.id}"]`);
            if (this.checked) {
                label.classList.add('bg-success', 'text-white');
            } else {
                label.classList.remove('bg-success', 'text-white');
            }
        });
    });
</script>
@endsection
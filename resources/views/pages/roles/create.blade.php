@extends('layouts.app')
@section('title') اضافة وظيفة جديد @endsection
@section('css')
@endsection
@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap">
               <h3 class="card-title">اضافة وظيفة جديد</h3>
               <a href="{{route('roles.index')}}" class="btn btn-primary"> عرض جميع الوظائف</a>
            </div>
            <form action="{{route('roles.store')}}" method="post">
             @csrf
             <div class="row">
              <div class="col-md-12 mb-3 form-group">
               <label for="name">اسم الوظيفة <span class="text-danger">*</span></label>
               <input type="text" name="name" value="{{old('name')}}" class="form-control" id="name" autocomplete="name" autofocus>
               @error('name')
                <span class="text-danger">{{ $message }}</span>
               @enderror
              </div>
              <div class="col-md-12 mb-3 form-group">
               <label for="permissions">صلاحيات الوظيفة <span class="text-danger">*</span></label>
               <div class="row mt-3">
                @forelse ($permissions as $permission)
                    <label class="py-2 px-3 m-1 rounded border shadow" for="permission{{$permission->id}}">
                        {{$permission->ar_name}}
                        <input type="checkbox" class="d-none" id="permission{{$permission->id}}" name="permissions[]" value="{{ $permission->id }}">
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
               <button type="submit" class="btn btn-primary btn-lg">اضافة وظيفة</button>
              </div>
             </div>
            </form>
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
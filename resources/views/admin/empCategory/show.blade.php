

@extends('layouts.adminLayout')
@section('content')



    <div class="col-md-8 offset-md-2">
        <div class="card">

            <div class="card">
                <h5 class="card-header">show empCategory</h5>
                <div class="card-body">
                    <a href="{{ route('admin.empCategory.index') }}" class="btn btn-success mb-3">Back</a>

                    <div class="mb-4">
                        <label for="name_uz" class="form-label">Name (uz)</label>
                        <input type="text" class="form-control" disabled value="{{$empCategory->name_uz}}">
                    </div>
                    <div class="mb-4">
                        <label for="name_ru" class="form-label">Name (ru)</label>
                        <input type="text" class="form-control" disabled value="{{$empCategory->name_ru}}">
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

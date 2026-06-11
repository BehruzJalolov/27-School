@extends('layouts.adminLayout')
@section('content')

    <div class="col-md-8 offset-md-2">
        <div class="card">
            <h5 class="card-header">Show Gallery Image</h5>
            <div class="card-body">
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-success mb-3">Back</a>

                <div class="mb-4">
                    <label class="form-label">Title (uz)</label>
                    <input type="text" class="form-control" disabled value="{{ $gallery->title_uz }}">
                </div>
                <div class="mb-4">
                    <label class="form-label">Title (ru)</label>
                    <input type="text" class="form-control" disabled value="{{ $gallery->title_ru }}">
                </div>
                <div class="mb-4">
                    <label class="form-label">Image</label><br>
                    <img src="{{ asset('storage/gallery/' . $gallery->image) }}" alt="Gallery Image" style="width: 250px; height: auto; border-radius: 8px;">
                </div>
            </div>
        </div>
    </div>

@endsection

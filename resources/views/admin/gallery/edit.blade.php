@extends('layouts.adminLayout')
@section('content')

    <div class="col-md-8 offset-md-2">
        <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <h5 class="card-header">Edit Gallery Image</h5>
                <div class="card-body">
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-success mb-3">Back</a>

                    <div class="mb-4">
                        <label for="title_uz" class="form-label">Title (uz)</label>
                        <input type="text" class="form-control @error('title_uz') is-invalid @enderror" id="title_uz" name="title_uz" value="{{ old('title_uz', $gallery->title_uz) }}">
                        @error('title_uz')
                        <div class="invalid-feedback" style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="title_ru" class="form-label">Title (ru)</label>
                        <input type="text" class="form-control @error('title_ru') is-invalid @enderror" id="title_ru" name="title_ru" value="{{ old('title_ru', $gallery->title_ru) }}">
                        @error('title_ru')
                        <div class="invalid-feedback" style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Current Image</label><br>
                        @if($gallery->image)
                            <img src="{{ asset('storage/gallery/' . $gallery->image) }}" alt="Gallery Image" style="width: 150px; height: auto; border-radius: 8px; margin-bottom: 10px;">
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                        <div class="invalid-feedback" style="color: red;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>

@endsection

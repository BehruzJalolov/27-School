@extends('layouts.adminLayout')
@section('content')

    <div class="col-md-8 offset-md-2">
        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <h5 class="card-header">Edit Posts</h5>
                <div class="card-body">
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-success">Back</a>

                    <div class="mb-4">
                        <label for="title_uz" class="form-label">title (uz)</label>
                        <input type="text" class="form-control @error('title_uz') is-invalid @enderror" id="title_uz" placeholder="title..." name="title_uz" value="{{ old('title_uz', $post->title_uz) }}">
                        @error('title_uz')
                        <div class="invalid-feedback" style="color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="title_ru" class="form-label">title (ru)</label>
                        <input type="text" class="form-control @error('title_ru') is-invalid @enderror" id="title_ru" placeholder="title_ru..." name="title_ru" value="{{ old('title_ru', $post->title_ru) }}">
                        @error('title_ru')
                        <div class="invalid-feedback" style="color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="body_uz" class="form-label">Body (uz)</label>
                        <input type="text" class="form-control @error('body_uz') is-invalid @enderror" id="body_uz" placeholder="body..." name="body_uz" value="{{ old('body_uz', $post->body_uz) }}">
                        @error('body_uz')
                        <div class="invalid-feedback" style="color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="body_ru" class="form-label">Body (ru)</label>
                        <input type="text" class="form-control @error('body_ru') is-invalid @enderror" id="body_ru" placeholder="body..." name="body_ru" value="{{ old('body_ru', $post->body_ru) }}">
                        @error('body_ru')
                        <div class="invalid-feedback" style="color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="image" class="form-label">Image</label>
                        @if($post->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/posts/' . $post->image) }}" alt="Posts Image" style="width: 150px; height: auto; border-radius: 8px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                        @error('image')
                        <div class="invalid-feedback" style="color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@extends('layouts.adminLayout')
@section('content')

    <div class="col-md-8 offset-md-2">
        <form action="{{ route('admin.statistic.store') }}" method="POST">
            @csrf

            <div class="card">
                <h5 class="card-header">Create Statistics</h5>
                <div class="card-body">
                    <a href="{{ route('admin.statistic.index') }}" class="btn btn-success mb-3">Back</a>

                    <div class="mb-4">
                        <label for="classesCount" class="form-label">Sinflar Soni</label>
                        <input type="number" class="form-control @error('classesCount') is-invalid @enderror" id="classesCount" placeholder="Sinflar soni..." name="classesCount" value="{{ old('classesCount') }}">
                        @error('classesCount')
                        <div class="invalid-feedback" style="color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="studentsCount" class="form-label">O'quvchilar Soni</label>
                        <input type="number" class="form-control @error('studentsCount') is-invalid @enderror" id="studentsCount" placeholder="O'quvchilar soni..." name="studentsCount" value="{{ old('studentsCount') }}">
                        @error('studentsCount')
                        <div class="invalid-feedback" style="color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="teachersCount" class="form-label">O'qituvchilar Soni</label>
                        <input type="number" class="form-control @error('teachersCount') is-invalid @enderror" id="teachersCount" placeholder="O'qituvchilar soni..." name="teachersCount" value="{{ old('teachersCount') }}">
                        @error('teachersCount')
                        <div class="invalid-feedback" style="color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="graduatesCount" class="form-label">Bitiruvchilar Soni</label>
                        <input type="number" class="form-control @error('graduatesCount') is-invalid @enderror" id="graduatesCount" placeholder="Bitiruvchilar soni..." name="graduatesCount" value="{{ old('graduatesCount') }}">
                        @error('graduatesCount')
                        <div class="invalid-feedback" style="color: red;">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
@extends('layouts.adminLayout')
@section('title')
    Admin - Statistics
@endsection
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <style>
                        .main-content {
                            margin-left: 250px;
                            padding: 20px;
                        }
                    </style>

                    @if($statistics->isEmpty())
                        <a href="{{ route('admin.statistic.create') }}" class="btn btn-primary">Create</a>
                    @else
                        <a href="{{ route('admin.statistic.create') }}" class="btn btn-primary mb-3">Create New</a>
                    @endif

                    <script>
                        setTimeout(function() {
                            var flash = document.getElementById('flash-message');
                            if (flash) {
                                flash.style.display = 'none';
                            }
                        }, 3000);
                    </script>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Sinflar Soni</th>
                                <th scope="col">Oquvchilar Soni</th>
                                <th scope="col">Oqituvchilar Soni</th>
                                <th scope="col">Bituruvchilar</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($statistics as $stat)
                                <tr>
                                    <th scope="row">{{ $stat->id }}</th>
                                    <td>{{ $stat->classesCount }}</td>
                                    <td>{{ $stat->studentsCount }}</td>
                                    <td>{{ $stat->teachersCount }}</td>
                                    <td>{{ $stat->graduatesCount }}</td>
                                    <td class="d-flex justify-content-center align-items-center">
                                        <form action="{{ route('admin.statistic.destroy', $stat->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger">Delete</button>
                                        </form>
                                        <a class="btn btn-success" href="{{route('admin.statistic.show', $stat->id)}}">Show</a>
                                        <a class="btn btn-primary" href="{{route('admin.statistic.edit', $stat->id)}}">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Hozircha statistika mavjud emas.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
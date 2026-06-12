@extends('layouts.adminLayout')
@section('title')
    Admin - Statistic Details
@endsection
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <h5 class="card-header">Statistic Details</h5>
                        <div class="card-body">
                            <a href="{{ route('admin.statistic.index') }}" class="btn btn-success mb-3">Back to List</a>

                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $statistic->id }}</td>
                                </tr>
                                <tr>
                                    <th>Sinflar Soni</th>
                                    <td>{{ $statistic->classesCount }}</td>
                                </tr>
                                <tr>
                                    <th>O'quvchilar Soni</th>
                                    <td>{{ $statistic->studentsCount }}</td>
                                </tr>
                                <tr>
                                    <th>O'qituvchilar Soni</th>
                                    <td>{{ $statistic->teachersCount }}</td>
                                </tr>
                                <tr>
                                    <th>Bitiruvchilar Soni</th>
                                    <td>{{ $statistic->graduatesCount }}</td>
                                </tr>
                                <tr>
                                    <th>Yaratilgan vaqt</th>
                                    <td>{{ $statistic->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Yangilangan vaqt</th>
                                    <td>{{ $statistic->updated_at }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Номера</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Номера</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if($hotelId)
                            <a href="{{ route('room_type.create', ['hotel' => $hotelId]) }}" class="btn btn-primary">Добавить</a>
                            @endif
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Цена</th>
                                    <th>Тип кровати</th>
                                    <th>Вместимость, чел.</th>
                                    <th>Размер, кв.м</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($room_types as $room_type)
                                <tr>
                                    <td>{{ $room_type->id }}</td>
                                    <td><a href="{{ route('room_type.show', ['hotel' => $hotelId, $room_type->id]) }}">{{ $room_type->name }}</a></td>
                                    <td>{{ $room_type->price }}</td>
                                    <td>{{ $room_type->bed_type }}</td>
                                    <td>{{ $room_type->capacity }}</td>
                                    <td>{{ $room_type->size_sqm }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

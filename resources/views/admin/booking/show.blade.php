@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Бронирование #{{ $booking->id }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Главная</li>
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
                        <div class="card-header d-flex p-3">
                            <div class="mr-3">
                                <a href="{{ route('booking.edit', $booking->id ) }}" class="btn btn-primary">Редактировать</a>
                            </div>
                            <form action="{{ route('booking.delete', $booking->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-danger" value="Удалить">
                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $booking->id }}</td>
                                </tr>
                                <tr>
                                    <td>Пользователь</td>
                                    <td><a href="{{ route('user.show', $booking->user->id) }}">{{$booking->user->surname}} {{ $booking->user->name }} {{ $booking->user->last_name }}</a> </td>
                                </tr>
                                <tr>
                                    <td>Отель</td>
                                    <td>@if($booking->hotel_id)
                                            <a href="{{ route('hotel.show', $booking->hotel->id) }}"> {{ $booking->hotel->name }}</a>
                                        @else
                                            Без отеля
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Номер</td>
                                    <td>@if($booking->hotel_id)
                                            <a href="{{ route('room_type.show', ['hotel' => $booking->hotel->id, 'room_type' => $booking->room_type->id]) }}"> {{ $booking->room_type->name }}</a>
                                        @else
                                            Без отеля
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Тур</td>
                                    <td>@if($booking->tour_id)
                                            {{ $booking->tour->name }}
                                        @else
                                            Без тура
                                        @endif</td>
                                </tr>
                                <tr>
                                    <td>Статус</td>
                                    <td>{{ $booking->status->name }}</td>
                                </tr>
                                <tr>
                                    <td>Оплачен</td>
                                    <td>{{ $booking->is_paid }}</td>
                                </tr>
                                <tr>
                                    <td>Город</td>
                                    <td>{{ $booking->hotel->city->name }}</td>
                                </tr>
                                <tr>
                                    <td>Даты</td>
                                    <td>{{ $booking->date_from }} - {{ $booking->date_to }}</td>
                                </tr>
                                <tr>
                                    <td>Количество человек</td>
                                    <td>{{ $booking_tourist_count }}</td>
                                </tr>
                                <tr>
                                    <td>Информация о туристах</td>
                                    <td><a href="{{ route('booking_tourist.index', $booking->id) }}" class="btn btn-primary">Перейти</a></td>
                                </tr>
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

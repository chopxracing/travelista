@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать бронирование</h1>
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
                <form action="{{ route('booking.update', $booking->id) }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <select name="hotel_id" class="form-control select2" style="width: 100%;" required>
                            <option value="{{ $booking->hotel->id }}" selected="selected">{{ $booking->hotel->name }}</option>
                            @foreach($hotels as $hotel)
                                @if($booking->hotel->id != $hotel->id)
                                    <option value="{{ $hotel->id }}"
                                        {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                        {{ $hotel->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="room_type_id" class="form-control select2" style="width: 100%;" required>
                            <option value="{{ $booking->room_type->id }}" selected="selected">{{ $booking->room_type->name }}</option>
                            @foreach($room_types as $room_type)
                                @if($booking->room_type->id != $room_type->id)
                                    <option value="{{ $room_type->id }}"
                                        {{ old('room_type_id') == $room_type->id ? 'selected' : '' }}>
                                        {{ $room_type->name }} - {{$room_type->hotel->name}}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="status_id" class="form-control select2" style="width: 100%;" required>
                            <option value="{{ $booking->status->id }}" selected="selected">{{ $booking->status->name }}</option>
                            @foreach($statuses as $status)
                                @if($booking->status->id != $status->id)
                                    <option value="{{ $status->id }}"
                                        {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="date" name="date_from" value="{{ $booking->date_from }}" class="form-control mb-1"
                               placeholder="Дата начала">
                    </div>
                    <div class="form-group">
                        <input type="date" name="date_to" value="{{ $booking->date_to }}" class="form-control mb-1"
                               placeholder="Дата окончания">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Сохранить">
                </form>
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

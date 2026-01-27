@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Создать бронирование</h1>
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
                <form action="{{ route('booking.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <select name="user_id" class="form-control select2" style="width: 100%;" required>
                            <option value="" selected="selected">Выберите пользователя</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - ID:{{ $user->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="hotel_id" class="form-control select2" style="width: 100%;" required>
                            <option value="" selected="selected">Выберите отель</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}"
                                    {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                    {{ $hotel->name }} - ID:{{ $hotel->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="room_type_id" class="form-control select2" style="width: 100%;" required>
                            <option value="" selected="selected">Выберите номер</option>
                            @foreach($room_types as $room_type)
                                <option value="{{ $room_type->id }}"
                                    {{ old('room_type_id') == $room_type->id ? 'selected' : '' }}>
                                    {{ $room_type->name }} - {{ $room_type->hotel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="tour_id" class="form-control select2" style="width: 100%;">
                            <option value="" selected="selected">Выберите тур</option>
                            @foreach($tours as $tour)
                                <option value="{{ $tour->id }}"
                                    {{ old('tour_id') == $tour->id ? 'selected' : '' }}>
                                    {{ $tour->name }} - ID:{{ $tour->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="date" name="date_from" class="form-control mb-1"
                               placeholder="Дата начала">
                    </div>
                    <div class="form-group">
                        <input type="date" name="date_to" class="form-control mb-1"
                               placeholder="Дата окончания">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Добавить">
                    </div>

                </form>
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>

    <!-- /.content -->
@endsection

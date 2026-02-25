@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать номер {{$room->room_type->name}} отеля {{ $room->room_type->hotel->name }}</h1>
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
                <form action="{{ route('room.update', ['hotel' => $hotelId, 'room_type' => $room_type, $room->id]) }}"
                      method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <input type="text" name="room_number" value="{{ $room->room_number }}" class="form-control mb-1"
                               placeholder="Номер комнаты">
                    </div>
                    <div class="form-group">
                        <input type="text" name="floor" value="{{ $room->floor }}" class="form-control mb-1"
                               placeholder="Этаж">
                    </div>
                    <div class="form-group">
                        <input type="text" name="view_type" value="{{ $room->view_type }}" class="form-control mb-1"
                               placeholder="Вид на:">
                    </div>
                    <div class="form-group">
                        <select id="is_smoking_available" name="is_smoking_available"
                                class="form-control @error('is_smoking_available') is-invalid @enderror" required>
                            @if($room->is_smoking_available == 1)
                                <option value="1" selected {{ old('is_smoking_available') == 1 ? 'selected' : '' }}>
                                    Курение разрешено
                                </option>
                                <option value="0" {{ old('is_smoking_available') == 0 ? 'selected' : '' }}>Курение
                                    запрещено
                                </option>
                            @else()
                                <option value="1"  {{ old('is_smoking_available') == 1 ? 'selected' : '' }}>
                                    Курение разрешено
                                </option>
                                <option value="0" selected {{ old('is_smoking_available') == 0 ? 'selected' : '' }}>Курение
                                    запрещено
                                </option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="room_status_id" name="room_status_id"
                                class="form-control @error('room_status_id') is-invalid @enderror" required>
                            <option value="{{$room->room_status->id}}">{{$room->room_status->name}}</option>
                            @foreach($room_statuses as $room_status)
                                @if($room->room_status->id != $room_status->id)
                                    <option
                                        value="{{$room_status->id}}" {{ old('room_status_id') == 1 ? 'selected' : '' }}>{{ $room_status->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Сохранить">
                </form>
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

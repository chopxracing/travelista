@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить номер</h1>
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
                <form action="{{ route('room.store', ['hotel' => $hotelId, 'room_type' => $room_type]) }}" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="room_number" value="{{ old('room_number') }}" class="form-control mb-1"
                               placeholder="Номер комнаты">
                    </div>
                    <div class="form-group">
                        <input type="text" name="floor" value="{{ old('floor') }}" class="form-control mb-1"
                               placeholder="Этаж">
                    </div>
                    <div class="form-group">
                        <input type="text" name="view_type" value="{{ old('view_type') }}" class="form-control mb-1"
                               placeholder="Вид на:">
                    </div>
                    <div class="form-group">
                        <select id="is_smoking_available" name="is_smoking_available"
                                class="form-control @error('is_smoking_available') is-invalid @enderror" required>
                            <option value="1" {{ old('is_smoking_available') == 1 ? 'selected' : '' }}>Курение разрешено</option>
                            <option value="0" selected {{ old('is_smoking_available') == 0 ? 'selected' : '' }}>Курение запрещено</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="room_status_id" name="room_status_id"
                                class="form-control @error('room_status_id') is-invalid @enderror" required>
                            @foreach($room_statuses as $room_status)
                            <option value="{{$room_status->id}}" {{ old('room_status_id') == 1 ? 'selected' : '' }}>{{ $room_status->name }}</option>
                            @endforeach
                        </select>
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

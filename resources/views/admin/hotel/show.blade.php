@extends('admin.layout')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $hotel->name }}</h1>
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
                                <a href="{{ route('hotel.edit', $hotel->id ) }}" class="btn btn-primary">Редактировать</a>
                            </div>
                            <div class="mr-3">
                                <a href="{{ route('room_type.index', $hotel->id ) }}" class="btn btn-primary">Номера</a>
                            </div>
                            <div class="mr-3">
                                <a href="{{ route('hotel_photo.index', $hotel->id ) }}" class="btn btn-primary">Добавить фото</a>
                            </div>
                            <form action="{{ route('hotel.delete', $hotel->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-danger" value="Удалить">
                            </form>
                        </div>
                        <div class="form-group text-center">
                            <img src="{{ Storage::url($hotel->preview_image) }}"
                                 alt="{{ $hotel->name }}"
                                 class="img-fluid"
                                 style="max-width: 100%; height: auto; max-height: 400px; object-fit: cover; border-radius: 8px;">
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $hotel->id }}</td>
                                </tr>
                                <tr>
                                    <td>Название</td>
                                    <td>{{ $hotel->name }}</td>
                                </tr>
                                <tr>
                                    <td>Кол-во звезд</td>
                                    <td>{{ $hotel->stars }}</td>
                                </tr>
                                <tr>
                                    <td>Метров до моря</td>
                                    <td>@if($hotel->meters_to_sea){{ $hotel->meters_to_sea }}@endif</td>
                                </tr>
                                <tr>
                                    <td>Метров до центра</td>
                                    <td>@if($hotel->meters_to_center){{ $hotel->meters_to_center }}@endif</td>
                                </tr>
                                <tr>
                                    <td>Описание</td>
                                    <td>{{ $hotel->description }}</td>
                                </tr>
                                <tr>
                                    <td>Время заселения</td>
                                    <td>{{ $hotel->check_in_time }}</td>
                                </tr>
                                <tr>
                                    <td>Время выселения</td>
                                    <td>{{ $hotel->check_out_time }}</td>
                                </tr>
                                <tr>
                                    <td>Активен</td>
                                    <td>{{ $hotel->is_active }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $hotel->email }}</td>
                                </tr>
                                <tr>
                                    <td>Номер телефона</td>
                                    <td>{{ $hotel->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Город</td>
                                    <td>{{ $hotel->country->name }}</td>
                                </tr>
                                <tr>
                                    <td>Страна</td>
                                    <td>{{ $hotel->city->name }}</td>
                                </tr>
                                <tr>
                                    <td>Полный адрес</td>
                                    <td>{{ $hotel->address }}</td>
                                </tr>
                                <tr>
                                    <td>Удобства</td>
                                    <td>@foreach($hotel->amenities as $amenity){{$amenity->name}} @endforeach</td>
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

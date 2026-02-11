@extends('admin.layout')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $tour->name }}</h1>
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
                                <a href="{{ route('tour.edit', $tour->id ) }}" class="btn btn-primary">Редактировать</a>
                            </div>
                            <form action="{{ route('tour.delete', $tour->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-danger" value="Удалить">
                            </form>
                        </div>
                        <div class="form-group text-center">
                            <img src="{{ Storage::url($tour->hotel->preview_image) }}"
                                 alt="{{ $tour->name }}"
                                 class="img-fluid"
                                 style="max-width: 100%; height: auto; max-height: 400px; object-fit: cover; border-radius: 8px;">
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $tour->id }}</td>
                                </tr>
                                <tr>
                                    <td>Название</td>
                                    <td>{{ $tour->name }}</td>
                                </tr>
                                <tr>
                                    <td>Описание</td>
                                    <td>{{ $tour->description }}</td>
                                </tr>
                                <tr>
                                    <td>Тип тура</td>
                                    <td>{{ $tour->tour_type->name }}</td>
                                </tr>
                                <tr>
                                    <td>Метров до центра</td>
                                    <td>@if($tour->meters_to_center){{ $tour->meters_to_center }}@endif</td>
                                </tr>
                                <tr>
                                    <td>Город</td>
                                    <td>{{ $tour->country->name }}</td>
                                </tr>
                                <tr>
                                    <td>Страна</td>
                                    <td>{{ $tour->city->name }}</td>
                                </tr>
                                <tr>
                                    <td>Цена</td>
                                    <td>{{ $tour->price }}</td>
                                </tr>
                                <tr>
                                    <td>Отель</td>
                                    <td>{{ $tour->hotel }}</td>
                                </tr>
                                <tr>
                                    <td>Даты</td>
                                    <td>{{ $tour->date_from }} -- {{ $tour->date_to }}</td>
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

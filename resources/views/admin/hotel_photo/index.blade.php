@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Фотографии отеля {{$hotel->name}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Фотографии отеля</li>
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
                            <a href="{{ route('hotel_photo.create', ['hotel' => $hotel->id]) }}" class="btn btn-primary">Добавить</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID отеля</th>
                                    <th>Фото</th>
                                    <th>Удалить</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($photos as $photo)
                                <tr>
                                    <td>{{ $hotel->id }}</td>
                                    <td><img src="{{ Storage::url($photo->file_path) }}" alt=""
                                             class="img-fluid"
                                             style="max-width: 100%; height: auto; max-height: 100px; object-fit: cover; border-radius: 8px;">
                                    </td>
                                    <td><form action="{{ route('hotel_photo.delete', ['hotel' => $hotel->id, 'photo' => $photo->id]) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" class="btn btn-danger" value="Удалить">
                                        </form></td>
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

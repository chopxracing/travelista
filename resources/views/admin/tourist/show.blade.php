@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Турист #{{ $tourist->id }}</h1>
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
                                <a href="{{ route('tourist.edit', $tourist->id ) }}" class="btn btn-primary">Редактировать</a>
                            </div>
                            <form action="{{ route('tourist.delete', $tourist->id) }}" method="post">
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
                                    <td>{{ $tourist->id }}</td>
                                </tr>
                                <tr>
                                    <td>User ID</td>
                                    <td>{{ $tourist->user_id }}</td>
                                </tr>
                                <tr>
                                    <td>Фамилия</td>
                                    <td>{{ $tourist->surname }}</td>
                                </tr>
                                <tr>
                                    <td>Имя</td>
                                    <td>{{ $tourist->name }}</td>
                                </tr>
                                <tr>
                                    <td>Отчество</td>
                                    <td>{{ $tourist->last_name }}</td>
                                </tr>
                                <tr>
                                    <td>Паспорт - серия</td>
                                    <td>{{ $tourist->passport_series }}</td>
                                </tr>
                                <tr>
                                    <td>Паспорт - номер</td>
                                    <td>{{ $tourist->passport_number }}</td>
                                </tr>
                                <tr>
                                    <td>Паспорт - дата выдачи</td>
                                    <td>{{ $tourist->passport_date }}</td>
                                </tr>
                                <tr>
                                    <td>Паспорт - выдан</td>
                                    <td>{{ $tourist->passport_org }}</td>
                                </tr>
                                <tr>
                                    <td>Дата рождения</td>
                                    <td>{{ $tourist->birth_date }}</td>
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

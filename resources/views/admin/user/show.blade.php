@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Пользователь #{{ $user->id }}</h1>
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
                                <a href="{{ route('user.edit', $user->id ) }}" class="btn btn-primary">Редактировать</a>
                            </div>
                            <form action="{{ route('user.delete', $user->id) }}" method="post">
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
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td>Фамилия</td>
                                    <td>{{ $user->surname }}</td>
                                </tr>
                                <tr>
                                    <td>Имя</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Отчество</td>
                                    <td>{{ $user->last_name }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td>Номер телефона</td>
                                    <td>{{ $user->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Возраст</td>
                                    <td>{{ $user->age }}</td>
                                </tr>
                                <tr>
                                    <td>Пол</td>
                                    <td>{{ $user->getGenderTitle() }}</td>
                                </tr>
                                <tr>
                                    <td>Адрес</td>
                                    <td>{{ $user->address }}</td>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if($tourist)
                            <div class="card-body table-responsive p-0">
                                <b class="ml-3 mb-1">Данные туриста</b>
                                <table class="table table-hover text-nowrap">
                                    <tbody>
                                    <tr>
                                        <td>ID</td>
                                        <td>{{ $tourist->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Паспорт серия</td>
                                        <td>{{ $tourist->passport_series }}</td>
                                    </tr>
                                    <tr>
                                        <td>Паспорт номер</td>
                                        <td>{{ $tourist->passport_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата выдачи</td>
                                        <td>{{ $tourist->passport_date }}</td>
                                    </tr>
                                    <tr>
                                        <td>Выдан</td>
                                        <td>{{ $tourist->passport_org }}</td>
                                    </tr>
                                    <tr>
                                        <td>Гражданство</td>
                                        <td>{{ $tourist->country->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата рождения</td>
                                        <td>{{ $tourist->birth_date }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

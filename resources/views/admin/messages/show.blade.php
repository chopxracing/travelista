@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Обращение #{{ $message->id }}</h1>
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
                            <div>
                            </div>
                            <form action="{{ route('message.delete', $message->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-danger" value="Завершить">
                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $message->id }}</td>
                                </tr>
                                <tr>
                                    <td>Имя</td>
                                    <td>{{ $message->name }}</td>
                                </tr>
                                <tr>
                                    <td>Тема</td>
                                    <td>{{ $message->theme }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $message->email }}</td>
                                </tr>
                                <tr>
                                    <td>Сообщение</td>
                                    <td>{{ $message->message }}</td>
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
            <div class="info-box-content">
                <p class="text-gray">Необходимо дать обратную связьь на почту - {{ $message->email }}</p>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<style>
    .info-box-content {
        display: flex;
        justify-content: center;
    }
</style>
@endsection

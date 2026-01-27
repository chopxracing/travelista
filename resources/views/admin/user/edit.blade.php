@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать пользователя</h1>
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
                <form action="{{ route('user.update', $user->id) }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control mb-1"
                               placeholder="Имя">
                    </div>
                    <div class="form-group">
                        <input type="text" name="surname" value="{{ $user->surname }}" class="form-control mb-1"
                               placeholder="Фамилия">
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control mb-1"
                               placeholder="Отчество">
                    </div>
                    <div class="form-group">
                        <input type="text" name="phone" value="{{ $user->phone }}" class="form-control mb-1"
                               placeholder="Номер телефона">
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" value="{{ $user->email }}" class="form-control mb-1"
                               placeholder="Номер телефона">
                    </div>
                    <div class="form-group">
                        <input type="text" name="address" value="{{ $user->address }}" class="form-control mb-1"
                               placeholder="Адрес">
                    </div>
                    <div class="form-group">
                        <select name="gender" class="custom-select form-control" id="exampleSelectBorder">
                            <option disabled selected>Пол</option>
                            <option {{ old('gender') == 1 ? ' selected' : '' }} value="1">Мужской</option>
                            <option {{ old('gender') == 2 ? ' selected' : '' }} value="2">Женский</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="city_id" class="custom-select form-control" id="exampleSelectBorder">
                            <option disabled selected>Город</option>
                            @foreach($cities as $city)
                                <option value="{{ old($city->id) }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="country_id" class="custom-select form-control" id="exampleSelectBorder">
                            <option disabled selected>Страна</option>
                            @foreach($countries as $country)
                                <option value="{{ old($country->id) }}">{{ $country->name }}</option>
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

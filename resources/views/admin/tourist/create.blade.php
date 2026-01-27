@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить пользователя</h1>
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
                <form action="{{ route('user.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-1"
                               placeholder="Имя">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control mb-1"
                               placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" value="{{ old('password') }}" class="form-control mb-1"
                               placeholder="Пароль">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                               class="form-control mb-1" placeholder="Подтвердите пароль">
                    </div>
                    <div class="form-group">
                        <input type="text" name="surname" value="{{ old('surname') }}" class="form-control mb-1"
                               placeholder="Фамилия">
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control mb-1"
                               placeholder="Отчество">
                    </div>
                    <div class="form-group">
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control mb-1"
                               placeholder="Номер телефона">
                    </div>
                    <div class="form-group">
                        <input type="text" name="address" value="{{ old('address') }}" class="form-control mb-1"
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
                        <select name="country_id" class="form-control select2" style="width: 100%;" required>
                            <option value="" selected="selected">Выберите страну</option>
                            @foreach($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Город с поиском (независимый от страны) -->
                    <div class="form-group">
                        <select name="city_id" class="form-control select2" style="width: 100%;" required>
                            <option value="" selected="selected">Выберите город</option>
                            @foreach($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
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

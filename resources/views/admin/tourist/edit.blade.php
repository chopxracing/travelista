@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать данные туриста</h1>
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
                <form action="{{ route('tourist.update', $tourist->id) }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <input type="text" name="name" value="{{ $tourist->name }}" class="form-control mb-1"
                               placeholder="Имя">
                    </div>
                    <div class="form-group">
                        <input type="text" name="surname" value="{{ $tourist->surname }}" class="form-control mb-1"
                               placeholder="Фамилия">
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" value="{{ $tourist->last_name }}" class="form-control mb-1"
                               placeholder="Отчество">
                    </div>
                    <div class="form-group">
                        <input type="text" name="passport_series" value="{{ $tourist->passport_series }}" class="form-control mb-1"
                               placeholder="Паспорт - серия">
                    </div>
                    <div class="form-group">
                        <input type="text" name="passport_number" value="{{ $tourist->passport_number }}" class="form-control mb-1"
                               placeholder="Паспорт - номер">
                    </div>
                    <div class="form-group">
                        <input type="text" name="passport_org" value="{{ $tourist->passport_org }}" class="form-control mb-1"
                               placeholder="Паспорт - выдан">
                    </div>
                    <div class="form-group">
                        <input type="text" name="passport_date" value="{{ $tourist->passport_date }}" class="form-control mb-1"
                               placeholder="Паспорт - дата выдачи">
                    </div>
                    <div class="form-group">
                        <input type="text" name="birth_date" value="{{ $tourist->birth_date }}" class="form-control mb-1"
                               placeholder="Дата рождения">
                    </div>
                    <div class="form-group">
                        <select name="passport_country_id" class="form-control select2" style="width: 100%;" required>
                            <option value="{{ $tourist->country->id }}" selected="selected">{{ $tourist->country->name }}</option>
                            @foreach($countries as $country)
                                @if($tourist->country->id != $country->id)
                                    <option value="{{ $country->id }}"
                                        {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
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

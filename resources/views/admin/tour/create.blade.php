@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить тур</h1>
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
                <form action="{{ route('tour.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <select name="tour_operator_id" class="form-control select2" style="width: 100%;" required>
                            <option value="" selected="selected">Выберите туроператора</option>
                            @foreach($tour_operators as $tour_operator)
                                <option value="{{ $tour_operator->id }}"
                                    {{ old('tour_operator_id') == $tour_operator->id ? 'selected' : '' }}>
                                    {{ $tour_operator->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-1"
                               placeholder="Имя">
                    </div>
                    <div class="form-group">
                        <textarea name="description" rows="10" placeholder="Описание" class="form-control mb-1"
                                  cols="30"></textarea>
                    </div>
                    <div class="form-group">
                        <select name="tour_type_id" class="form-control select2" style="width: 100%;" required>
                            <option value="" selected="selected">Выберите тип тура</option>
                            @foreach($tour_types as $tour_type)
                                <option value="{{ $tour_type->id }}"
                                    {{ old('tour_type_id') == $tour_type->id ? 'selected' : '' }}>
                                    {{ $tour_type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="price" value="{{ old('price') }}"
                               class="form-control mb-1"
                               placeholder="Цена от (с учетом перелета, страховки и т.д.)">
                    </div>
                    <div class="form-group">
                        <input type="text" name="days" value="{{ old('days') }}"
                               class="form-control mb-1" placeholder="Количество дней (продолжительность)">
                    </div>
                    <div class="form-group">
                        <select name="hotel_id" class="form-control select2" style="width: 100%;" required>
                            <option value="" selected="selected">Выберите отель</option>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}"
                                    {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                    {{ $hotel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="date" name="date_from" value="{{ old('date_from') }}"
                               class="form-control mb-1" placeholder="Дата начала">
                    </div>
                    <div class="form-group">
                        <input type="date" name="date_to" value="{{ old('date_to') }}"
                               class="form-control mb-1" placeholder="Дата окончания">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Добавить">
                    </div>

                </form>
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
@endsection

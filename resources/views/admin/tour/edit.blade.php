@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать отель</h1>
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
                <form action="{{ route('hotel.update', $hotel->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
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
                            <option value="{{ $tour->tour_operator_id }}" selected="selected">{{ $tour->tour_operator_id->name }}</option>
                            @foreach($tour_operators as $tour_operator)
                                @if($tour->tour_operator->id != $tour_operator->id)
                                    <option value="{{ $tour_operator->id }}"
                                        {{ old('tour_operator_id') == $tour_operator->id ? 'selected' : '' }}>
                                        {{ $tour_operator->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" value="{{ $tour->name }}" class="form-control mb-1"
                               placeholder="Имя">
                    </div>
                    <div class="form-group">
                        <textarea name="description" rows="10" placeholder="Описание" class="form-control mb-1"
                                  cols="30"></textarea>
                    </div>
                    <div class="form-group">
                        <select name="tour_type_id" class="form-control select2" style="width: 100%;" required>
                            <option value="{{ $tour->tour_type_id }}" selected="selected">{{ $tour->tour_type_id->name }}</option>
                            @foreach($tour_types as $tour_type)
                                @if($tour->tour_type->id != $tour_type->id)
                                    <option value="{{ $tour_type->id }}"
                                        {{ old('tour_type_id') == $tour_type->id ? 'selected' : '' }}>
                                        {{ $tour_type->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="price" value="{{$tour->price}}"
                               class="form-control mb-1"
                               placeholder="Цена от (с учетом перелета, страховки и т.д.)">
                    </div>
                    <div class="form-group">
                        <input type="text" name="days" value="{{$tour->days}}"
                               class="form-control mb-1" placeholder="Количество дней (продолжительность)">
                    </div>
                    <div class="form-group">
                        <select name="hotel_id" class="form-control select2" style="width: 100%;" required>
                            <option value="{{ $tour->hotel->id }}" selected="selected">{{ $tour->hotel->name }}</option>
                            @foreach($hotels as $hotel)
                                @if($tour->hotel->id != $hotel->id)
                                    <option value="{{ $hotel->id }}"
                                        {{ old('hotel_id') == $hotel->id ? 'selected' : '' }}>
                                        {{ $hotel->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="date_from" value="{{$tour->date_from}}"
                               class="form-control mb-1" placeholder="Дата начала">
                    </div>
                    <div class="form-group">
                        <input type="text" name="date_to" value="{{$tour->date_to}}"
                               class="form-control mb-1" placeholder="Дата окончания">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Сохранить">
                </form>
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

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
                        <input type="text" name="name" value="{{ $hotel->name }}" class="form-control mb-1"
                               placeholder="Имя">
                    </div>
                    <div class="form-group">
                        <input type="text" name="stars" value="{{ $hotel->stars }}" class="form-control mb-1"
                               placeholder="Кол-во звезд">
                    </div>
                    <div class="form-group">
                        <input type="text" name="meters_to_sea" value="{{ $hotel->meters_to_sea }}" class="form-control mb-1"
                               placeholder="Метров до моря">
                    </div>
                    <div class="form-group">
                        <input type="text" name="meters_to_center" value="{{ $hotel->meters_to_center }}" class="form-control mb-1"
                               placeholder="Метров до центра">
                    </div>
                    <div class="form-group">
                        <textarea name="description" rows="10" placeholder="Описание" class="form-control mb-1"
                                  cols="30">{{ $hotel->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" name="check_in_time" value="{{ $hotel->check_in_time }}" class="form-control mb-1"
                               placeholder="Время заселения">
                    </div>
                    <div class="form-group">
                        <input type="text" name="check_out_time" value="{{ $hotel->check_out_time }}" class="form-control mb-1"
                               placeholder="Время выселения">
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" value="{{ $hotel->email }}" class="form-control mb-1"
                               placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="text" name="phone" value="{{ $hotel->phone }}" class="form-control mb-1"
                               placeholder="Номер телефона">
                    </div>
                    <div class="form-group">
                        <input type="text" name="address" value="{{ $hotel->address }}" class="form-control mb-1"
                               placeholder="Адрес">
                    </div>
                    <div class="form-group">
                        <select id="is_active" name="is_active"
                                class="form-control @error('is_active') is-invalid @enderror">
                            @if($hotel->is_active == 1)
                            <option selected value="1">Активен</option>
                                <option value="0">Не активен</option>
                            @else
                                <option selected value="0">Не активен</option>
                                <option value="1">Активен</option>
                            @endif


                        </select>
                    </div>


                    <div class="form-group text-center">
                        <img src="{{ Storage::url($hotel->preview_image) }}"
                             alt="{{ $hotel->name }}"
                             class="img-fluid"
                             style="max-width: 100%; height: auto; max-height: 200px; object-fit: cover; border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="preview_image" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Поменять превью</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="country_id" class="form-control select2" style="width: 100%;" required>
                            <option value="{{ $hotel->country->id }}" selected="selected">{{ $hotel->country->name }}</option>
                            @foreach($countries as $country)
                                @if($hotel->country->id != $country->id)
                                <option value="{{ $country->id }}"
                                    {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Город с поиском (независимый от страны) -->
                    <div class="form-group">
                        <select name="city_id" class="form-control select2" style="width: 100%;" required>
                            <option value="{{ $hotel->city->id }}" selected="selected">{{ $hotel->city->name }}</option>
                            @foreach($cities as $city)
                                @if($hotel->city->id != $city->id)
                                <option value="{{ $city->id }}"
                                    {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="amenities[]" class="select2" multiple
                                data-placeholder="Выберите удобства"
                                style="width: 100%;">
                            @foreach($hotel_amenities as $hotel_amenity)
                                <option value="{{ $hotel_amenity->id }}"
                                    {{ in_array(
                                        $hotel_amenity->id,
                                        old('amenities', $selectedAmenities)
                                    ) ? 'selected' : '' }}>
                                    {{ $hotel_amenity->name }}
                                </option>
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

@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить отель</h1>
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
                <form action="{{ route('hotel.store') }}" method="post" enctype="multipart/form-data">
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
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-1"
                               placeholder="Имя">
                    </div>
                    <div class="form-group">
                        <input type="text" name="stars" value="{{ old('stars') }}" class="form-control mb-1"
                               placeholder="Кол-во звёзд">
                    </div>
                    <div class="form-group">
                        <input type="text" name="meters_to_sea" value="{{ old('meters_to_sea') }}"
                               class="form-control mb-1"
                               placeholder="Метров до моря">
                    </div>
                    <div class="form-group">
                        <input type="text" name="meters_to_center" value="{{ old('meters_to_center') }}"
                               class="form-control mb-1" placeholder="Метров до центра">
                    </div>
                    <div class="form-group">
                        <input type="text" name="check_in_time" value="{{ old('check_in_time') }}"
                               class="form-control mb-1" placeholder="Время заезда в формате 12:00">
                    </div>
                    <div class="form-group">
                        <input type="text" name="check_out_time" value="{{ old('check_out_time') }}"
                               class="form-control mb-1" placeholder="Время выезда в формате 12:00">
                    </div>
                    <div class="form-group">
                        <textarea name="description" rows="10" placeholder="Описание" class="form-control mb-1"
                                  cols="30"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="preview_image" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Превью</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <select id="is_active" name="is_active"
                                class="form-control @error('is_active') is-invalid @enderror" required>
                            <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Активен</option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Не активен</option>
                        </select>
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
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control mb-1"
                               placeholder="Email">
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
                        <select name="amenities[]" class="select2" multiple="multiple"
                                data-placeholder="Выберите удобства" style="width: 100%;">
                            @foreach($hotel_amenities as $hotel_amenity)
                                <option value="{{ $hotel_amenity->id }}"
                                    {{ in_array($hotel_amenity->id, old('amenities', [])) ? 'selected' : '' }}>
                                    {{ $hotel_amenity->name }}
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
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function(e){
            var fileName = e.target.files[0].name;
            e.target.nextElementSibling.innerText = fileName;
        });
    </script>
    <!-- /.content -->
@endsection

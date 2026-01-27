@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить номер (тип)</h1>
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
                <form action="{{ route('room_type.store', ['hotel' => $hotelId]) }}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-1"
                               placeholder="Название">
                    </div>
                    <div class="form-group">
                        <textarea name="description" rows="10" placeholder="Описание" class="form-control mb-1"
                                  cols="30">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" name="price" value="{{ old('price') }}" class="form-control mb-1"
                               placeholder="Цена">
                    </div>
                    <div class="form-group">
                        <input type="text" name="bed_type" value="{{ old('bed_type') }}"
                               class="form-control mb-1"
                               placeholder="Тип кровати (односпальные две, двуспальная одна и тп)">
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
                        <input type="number" name="capacity" value="{{ old('capacity') }}" class="form-control mb-1"
                               placeholder="Вместимость, чел.">
                    </div>
                    <div class="form-group">
                        <input type="number" name="size_sqm" value="{{ old('size_sqm') }}" class="form-control mb-1"
                               placeholder="Размер, кв. м">
                    </div>
                    <div class="form-group">
                        <select name="amenities[]" class="select2" multiple="multiple"
                                data-placeholder="Выберите удобства" style="width: 100%;">
                            @foreach($room_amenities as $room_amenity)
                                <option value="{{ $room_amenity->id }}"
                                    {{ in_array($room_amenity->id, old('amenities', [])) ? 'selected' : '' }}>
                                    {{ $room_amenity->name }}
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
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var fileName = e.target.files[0].name;
            e.target.nextElementSibling.innerText = fileName;
        });
    </script>
    <!-- /.content -->
@endsection

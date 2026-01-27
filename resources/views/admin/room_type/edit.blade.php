@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать номер(тип)</h1>
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
                <form action="{{ route('room_type.update',['hotel' => $hotelId, $room_type->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <input type="text" name="name" value="{{ $room_type->name }}" class="form-control mb-1"
                               placeholder="Название">
                    </div>
                    <div class="form-group">
                        <textarea name="description" rows="10" placeholder="Описание" class="form-control mb-1"
                                  cols="30">{{ $room_type->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" name="price" value="{{ $room_type->price }}" class="form-control mb-1"
                               placeholder="Цена">
                    </div>
                    <div class="form-group">
                        <input type="text" name="bed_type" value="{{ $room_type->bed_type }}" class="form-control mb-1"
                               placeholder="Тип кровати">
                    </div>
                    <div class="form-group text-center">
                        <img src="{{ Storage::url($room_type->preview_image) }}"
                             alt="{{ $room_type->name }}"
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
                        <input type="text" name="capacity" value="{{ $room_type->capacity }}" class="form-control mb-1"
                               placeholder="Вместимость">
                    </div>
                    <div class="form-group">
                        <input type="text" name="size_sqm" value="{{ $room_type->size_sqm }}" class="form-control mb-1"
                               placeholder="Размер, кв. м.">
                    </div>

                    <div class="form-group">
                        <select name="amenities[]" class="select2" multiple
                                data-placeholder="Выберите удобства"
                                style="width: 100%;">
                            @foreach($room_amenities as $room_amenity)
                                <option value="{{ $room_amenity->id }}"
                                    {{ in_array(
                                        $room_amenity->id,
                                        old('amenities', $selectedAmenities)
                                    ) ? 'selected' : '' }}>
                                    {{ $room_amenity->name }}
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
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function(e){
            var fileName = e.target.files[0].name;
            e.target.nextElementSibling.innerText = fileName;
        });
    </script>
    <!-- /.content -->
@endsection

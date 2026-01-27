@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить фотографии</h1>
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
        <div class="card">
            <div class="card-body">

                <form id="hotel-dropzone"
                      action="{{ route('hotel_photo.store', $hotel) }}"
                      class="dropzone">
                    @csrf
                </form>

                <button id="uploadBtn" class="btn btn-primary mt-3">
                    Загрузить
                </button>

            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        Dropzone.autoDiscover = false;

        const dz = new Dropzone('#hotel-dropzone', {
            paramName: 'images', // Laravel ожидает images[]
            uploadMultiple: true,
            parallelUploads: 10,
            maxFilesize: 4, // MB
            acceptedFiles: 'image/*',
            autoProcessQueue: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dictDefaultMessage: 'Перетащите фото или кликните сюда'
        });

        document.getElementById('uploadBtn').addEventListener('click', function () {
            dz.processQueue();
        });
    </script>
@endsection

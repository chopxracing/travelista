@extends('admin.layout')

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="hotel-dropzone" action="{{ route('room_type_photo.store', $room_type) }}" class="dropzone" enctype="multipart/form-data">
                @csrf
            </form>

            <button id="uploadBtn" class="btn btn-primary mt-3">Загрузить</button>

            <div id="uploadedPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        Dropzone.autoDiscover = false;

        const dz = new Dropzone('#hotel-dropzone', {
            url: '{{ route('room_type_photo.store', $room_type) }}',
            paramName: 'images',      // ← обязательно с [] для массива
            uploadMultiple: true,        // несколько файлов
            parallelUploads: 10,
            maxFilesize: 4,              // MB
            acceptedFiles: 'image/*',
            autoProcessQueue: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            dictDefaultMessage: 'Перетащите фото или кликните сюда'
        });

        // кнопка загрузки
        document.getElementById('uploadBtn').addEventListener('click', function () {
            dz.processQueue();
        });

        // обработка ошибок
        dz.on('error', function(file, response) {
            console.log('Ошибка файла:', file);
            console.log('Ответ сервера:', response);
        });

        // можно добавить успешную загрузку
        dz.on('successmultiple', function(files, response) {
            console.log('Файлы загружены:', response);
        });

    </script>
@endsection

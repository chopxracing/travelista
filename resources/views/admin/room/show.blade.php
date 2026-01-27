@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $fullhotel->name }} - {{ $room_type->name }}</h1>
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
    <div class="form-group text-center">
        <img src="{{ Storage::url($room_type->preview_image) }}"
             alt="{{ $room_type->name }}"
             class="img-fluid"
             style="max-width: 100%; height: auto; max-height: 400px; object-fit: cover; border-radius: 8px;">
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex p-3">
                            <div class="mr-3">
                                <a href="{{ route('room_type.edit', ['hotel' => $hotelId, $room_type->id] ) }}" class="btn btn-primary">Редактировать</a>
                            </div>
                            <form action="{{ route('room_type.delete', ['hotel' => $hotelId, $room_type->id]) }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-danger" value="Удалить">
                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <tbody>
                                <tr>
                                    <td>ID</td>
                                    <td>{{ $room_type->id }}</td>
                                </tr>
                                <tr>
                                    <td>Описание</td>
                                    <td>{{ $room_type->description }}</td>
                                </tr>
                                <tr>
                                    <td>Цена</td>
                                    <td>{{ $room_type->price }}</td>
                                </tr>
                                <tr>
                                    <td>Тип кровати</td>
                                    <td>{{ $room_type->bed_type }}</td>
                                </tr>
                                <tr>
                                    <td>Размер</td>
                                    <td>{{ $room_type->size_sqm }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('room.create', ['hotel' => $hotelId, 'room_type' => $room_type]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Добавить номер
                                </a>

                                <!-- Простая форма поиска -->
                                <form action="{{ route('room_type.index', ['hotel' => $hotelId]) }}" method="GET" class="form-inline">
                                    <div class="input-group">
                                        <input type="text"
                                               name="search"
                                               class="form-control"
                                               placeholder="Поиск по названию..."
                                               value="{{ request('search') }}"
                                               style="width: 250px;">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            @if(request('search'))
                                                <a href="{{ route('room_type.index') }}" class="btn btn-secondary">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Информация о поиске -->
                        @if(request('search'))
                            <div class="card-body py-2">
                                <div class="alert alert-info mb-0 py-2">
                                    <i class="fas fa-search"></i>
                                    Поиск по названию: <strong>"{{ request('search') }}"</strong>
                                    <span class="badge badge-light ml-2">
                                        Найдено: {{ $rooms->total() }}
                                    </span>
                                    <a href="{{ route('room.index') }}" class="float-right text-primary">
                                        <i class="fas fa-times"></i> Очистить
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Номер комнаты</th>
                                    <th>Этаж</th>
                                    <th>Вид</th>
                                    <th>Курение</th>
                                    <th>Статус</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($rooms as $room)
                                    <tr>
                                        <td>{{ $room->id }}</td>
                                        <td>{{ $room->room_number }}</td>
                                        <td>{{ $room->floor }} ★</td>
                                        <td>{{ $room->view_type }}</td>
                                        <td>{{ $room->is_smoking_available }}</td>
                                        <td>{{ $room->room_statuses_name }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('room.edit', $room->id) }}"
                                                   class="btn btn-warning" title="Редактировать">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('room.delete', $room->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Удалить номер?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Удалить">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            @if(request('search'))
                                                <div class="text-muted">
                                                    <i class="fas fa-search fa-2x mb-3"></i>
                                                    <h5>Отели не найдены</h5>
                                                    <p>По запросу "{{ request('search') }}" ничего не найдено</p>
                                                    <a href="{{ route('room.index') }}" class="btn btn-outline-primary">
                                                        Показать все отели
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-muted">
                                                    <i class="fas fa-room fa-2x mb-3"></i>
                                                    <h5>Нет номеров</h5>
                                                    <p>Добавьте первый номер</p>
                                                    <a href="{{ route('room.create', ['hotel' => $hotelId, 'room_type' => $room_type]) }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Добавить номер
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Пагинация -->
                        @if($rooms->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-left">
                                    <span class="text-muted">
                                        Показано с {{ $rooms->firstItem() }} по {{ $rooms->lastItem() }} из {{ $rooms->total() }}
                                    </span>
                                </div>
                                <div class="float-right">
                                    {{ $rooms->appends(['search' => request('search')])->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection


@section('scripts')
    <script>
        // Автофокус на поле поиска
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        });

        // Отправка формы при нажатии Enter
        document.querySelector('input[name="search"]')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
        function doSearch() {
            const value = document.getElementById('search').value;
            window.location.href = `{{ route('room_type.show', ['hotel' => $hotelId, 'room_type' => $room_type]) }}?search=${encodeURIComponent(value)}`;
        }
    </script>
@endsection

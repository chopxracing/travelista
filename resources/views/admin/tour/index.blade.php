@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Туры</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Туры</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('tour.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Добавить тур
                                </a>

                                <!-- Простая форма поиска -->
                                <form action="{{ route('tour.index') }}" method="GET" class="form-inline">
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
                                                <a href="{{ route('tour.index') }}" class="btn btn-secondary">
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
                                        Найдено: {{ $tours->total() }}
                                    </span>
                                    <a href="{{ route('tour.index') }}" class="float-right text-primary">
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
                                    <th>Название</th>
                                    <th>Туроператор</th>
                                    <th>Отель</th>
                                    <th>Страна</th>
                                    <th>Город</th>
                                    <th>Длительность</th>
                                    <th>Цена</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($tours as $tour)
                                    <tr>
                                        <td>{{ $tour->id }}</td>
                                        <td>
                                            <a href="{{ route('tour.show', $tour->id) }}" class="font-weight-bold">
                                                {{ $tour->name }}
                                            </a>
                                        </td>
                                        <td>{{ $tour->tour_operator->name }}</td>
                                        <td>{{ $tour->hotel->name }}</td>
                                        <td>{{ $tour->country->name }}</td>
                                        <td>{{ $tour->city->name }}</td>
                                        <td>{{ $tour->days }} дней</td>
                                        <td> от {{ $tour->price }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('tour.show', $tour->id) }}"
                                                   class="btn btn-info" title="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('tour.edit', $tour->id) }}"
                                                   class="btn btn-warning" title="Редактировать">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('tour.delete', $tour->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Удалить тур?')">
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
                                                    <h5>Туры не найдены</h5>
                                                    <p>По запросу "{{ request('search') }}" ничего не найдено</p>
                                                    <a href="{{ route('tour.index') }}" class="btn btn-outline-primary">
                                                        Показать все туры
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-muted">
                                                    <i class="fas fa-tour fa-2x mb-3"></i>
                                                    <h5>Нет туров</h5>
                                                    <p>Добавьте первый тур</p>
                                                    <a href="{{ route('tour.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Добавить тур
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
                        @if($tours->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-left">
                                    <span class="text-muted">
                                        Показано с {{ $tours->firstItem() }} по {{ $tours->lastItem() }} из {{ $tours->total() }}
                                    </span>
                                </div>
                                <div class="float-right">
                                    {{ $tours->appends(['search' => request('search')])->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('styles')
    <style>
        .input-group {
            width: auto;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .btn-group .btn {
            border-radius: 0.25rem;
            margin-right: 2px;
        }

        .btn-group form {
            display: inline-block;
        }
    </style>
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
            window.location.href = `{{ route('tour.index') }}?search=${encodeURIComponent(value)}`;
        }
    </script>
@endsection

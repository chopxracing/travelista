@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Бронирования</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Бронирования</li>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('booking.create') }}" class="btn btn-primary">Добавить</a>
                                <form action="{{ route('booking.index') }}" method="GET" class="form-inline">
                                    <div class="input-group">
                                        <input type="text"
                                               name="search"
                                               class="form-control"
                                               placeholder="Поиск по ID, userID, Статусу..."
                                               value="{{ request('search') }}"
                                               style="width: 250px;">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            @if(request('search'))
                                                <a href="{{ route('booking.index') }}" class="btn btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @if(request('search'))
                            <div class="card-body py-2">
                                <div class="alert alert-info mb-0 py-2">
                                    <i class="fas fa-search"></i>
                                    Поиск по названию: <strong>"{{ request('search') }}"</strong>
                                    <span class="badge badge-light ml-2">
                                        Найдено: {{ $bookings->total() }}
                                    </span>
                                    <a href="{{ route('booking.index') }}" class="float-right text-primary">
                                        <i class="fas fa-times"></i> Очистить
                                    </a>
                                </div>
                            </div>
                        @endif
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ID Пользователя</th>
                                    <th>Отель</th>
                                    <th>Номер</th>
                                    <th>Тур</th>
                                    <th>Статус</th>
                                    <th>Оплачен</th>
{{--                                    <th>Страна</th>--}}
{{--                                    <th>Город</th>--}}
{{--                                    <th>Дата начала</th>--}}
{{--                                    <th>Дата окончания</th>--}}
{{--                                    <th>Количество человек</th>--}}
{{--                                    <th>Данные участников бронирования</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td><a href="{{ route('booking.show', $booking->id) }}">{{ $booking->id }}</a></td>
                                        <td>{{ $booking->user->id }} ({{$booking->user->email}})</td>
                                        <td>@if($booking->hotel_id)
                                                {{ $booking->hotel->name }}
                                            @else
                                                Без отеля
                                            @endif</td>
                                        <td>@if($booking->hotel_id)
                                                {{ $booking->room_type->name }}
                                            @else
                                                Без отеля
                                            @endif</td>
                                        <td>@if($booking->tour_id)
                                                {{ $booking->tour->name }}
                                            @else
                                                Без тура
                                            @endif</td>
                                        <td>{{ $booking->status->name }}</td>
                                        <td>{{ $booking->is_paid }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            @if(request('search'))
                                                <div class="text-muted">
                                                    <i class="fas fa-search fa-2x mb-3"></i>
                                                    <h5>Бронирования не найдены</h5>
                                                    <p>По запросу "{{ request('search') }}" ничего не найдено</p>
                                                    <a href="{{ route('booking.index') }}" class="btn btn-outline-primary">
                                                        Показать все
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-muted">
                                                    <i class="fas fa-hotel fa-2x mb-3"></i>
                                                    <h5>Нет бронирований</h5>
                                                    <p>Добавьте бронирование</p>
                                                    <a href="{{ route('booking.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Добавить
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- пагинация -->
                        @if($bookings->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-left">
                                    <span class="text-muted">
                                        Показано с {{ $bookings->firstItem() }} по {{ $bookings->lastItem() }} из {{ $bookings->total() }}
                                    </span>
                                </div>
                                <div class="float-right">
                                    {{ $bookings->appends(['search' => request('search')])->links() }}
                                </div>
                            </div>
                        @endif
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    <script>
        // Автофокус на поле поиска
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        });

        // Отправка формы при нажатии Enter
        document.querySelector('input[name="search"]')?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });

        function doSearch() {
            const value = document.getElementById('search').value;
            window.location.href = `{{ route('hotel.index') }}?search=${encodeURIComponent(value)}`;
        }
    </script>
@endsection

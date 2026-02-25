@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Пользователи</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Пользователи</li>
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
                                <a href="{{ route('user.create') }}" class="btn btn-primary">Добавить</a>
                                <form action="{{ route('user.index') }}" method="GET" class="form-inline">
                                    <div class="input-group">
                                        <input type="text"
                                               name="search"
                                               class="form-control"
                                               placeholder="Поиск по ID, номеру телефона или имени..."
                                               value="{{ request('search') }}"
                                               style="width: 250px;">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            @if(request('search'))
                                                <a href="{{ route('user.index') }}" class="btn btn-danger">
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
                                        Найдено: {{ $users->total() }}
                                    </span>
                                    <a href="{{ route('user.index') }}" class="float-right text-primary">
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
                                    <th>Фамилия</th>
                                    <th>Имя</th>
                                    <th>Отчество</th>
                                    <th>Email</th>
                                    <th>Номер телефона</th>
                                    <th>Пол</th>
                                    <th>Адрес</th>
                                    <th>Cтрана</th>
                                    <th>Город</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->surname }}</td>
                                        <td><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></td>
                                        <td>@if($user->last_name)
                                                {{ $user->last_name }}
                                            @else
                                                Отсутствует
                                            @endif</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->getGenderTitle() }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->country->name }}</td>
                                        <td>{{ $user->city->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            @if(request('search'))
                                                <div class="text-muted">
                                                    <i class="fas fa-search fa-2x mb-3"></i>
                                                    <h5>Пользователи не найдены</h5>
                                                    <p>По запросу "{{ request('search') }}" ничего не найдено</p>
                                                    <a href="{{ route('user.index') }}" class="btn btn-outline-primary">
                                                        Показать всех
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-muted">
                                                    <i class="fas fa-hotel fa-2x mb-3"></i>
                                                    <h5>Нет пользователей</h5>
                                                    <p>Добавьте пользователя</p>
                                                    <a href="{{ route('user.create') }}" class="btn btn-primary">
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
                        @if($users->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-left">
                                    <span class="text-muted">
                                        Показано с {{ $users->firstItem() }} по {{ $users->lastItem() }} из {{ $users->total() }}
                                    </span>
                                </div>
                                <div class="float-right">
                                    {{ $users->appends(['search' => request('search')])->links() }}
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

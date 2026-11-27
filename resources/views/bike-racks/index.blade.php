<x-layout title="Мої велопарковки">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
        <div>
            <h1 class="h3 mb-1">Мої велопарковки</h1>

            <p class="text-body-secondary mb-0">
                {{ auth()->user()->name }}
            </p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-outline-secondary">
                Вийти
            </button>
        </form>
    </div>

    <x-flash />

    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('bike-racks.create') }}" class="btn btn-primary">
            Додати велопарковку
        </a>

        <a href="{{ route('confirmation-receipts.index') }}" class="btn btn-outline-primary">
            Чеки підтвердження
        </a>

        <a href="{{ url('/app') }}" class="btn btn-outline-success">
            Vue-інтерфейс
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('bike-racks.index') }}" class="row g-2 align-items-end mb-4">

        <div class="col-sm-6 col-lg">
            <label for="search" class="form-label">
                Пошук
            </label>

            <input type="search" id="search" name="search" class="form-control"
                value="{{ $filters['search'] ?? '' }}" maxlength="120">
        </div>

        <div class="col-sm-6 col-lg">
            <label for="district_id" class="form-label">
                Квартал
            </label>

            <select id="district_id" name="district_id" class="form-select">

                <option value="">Усі квартали</option>

                @foreach ($districts as $district)
                    <option value="{{ $district->id }}" @selected((string) ($filters['district_id'] ?? '') === (string) $district->id)>
                        {{ $district->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-6 col-lg">
            <label for="status" class="form-label">
                Статус
            </label>

            <select id="status" name="status" class="form-select">

                <option value="">Усі статуси</option>

                <option value="draft" @selected(($filters['status'] ?? null) === 'draft')>
                    Чернетка
                </option>

                <option value="confirmed" @selected(($filters['status'] ?? null) === 'confirmed')>
                    Відкриття підтверджено
                </option>
            </select>
        </div>

        <div class="col-sm-6 col-lg">
            <label for="sort" class="form-label">
                Сортування
            </label>

            <select id="sort" name="sort" class="form-select">

                @foreach ([
        'opening_desc' => 'Дата відкриття ↓',
        'opening_asc' => 'Дата відкриття ↑',
        'title_asc' => 'Назва А–Я',
        'title_desc' => 'Назва Я–А',
        'slots_asc' => 'Кількість місць ↑',
        'slots_desc' => 'Кількість місць ↓',
        'created_desc' => 'Найновіші',
    ] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['sort'] ?? 'opening_desc') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-6 col-lg-auto">
            <label for="per_page" class="form-label">
                На сторінці
            </label>

            <input type="number" id="per_page" name="per_page" class="form-control" min="1" max="50"
                value="{{ $filters['per_page'] ?? 10 }}">
        </div>

        <div class="col-12 col-lg-auto">
            <button type="submit" class="btn btn-primary">
                Фільтрувати
            </button>

            <a href="{{ route('bike-racks.index') }}" class="btn btn-outline-secondary">
                Скинути
            </a>
        </div>
    </form>

    <p class="text-body-secondary">
        Знайдено {{ $bikeRacks->total() }} записів.
    </p>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th scope="col">Назва</th>
                    <th scope="col">Квартал</th>
                    <th scope="col">Місць</th>
                    <th scope="col">Запланована дата</th>
                    <th scope="col">Статус</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($bikeRacks as $bikeRack)
                    <tr>
                        <td>
                            <a
                                href="{{ route('bike-racks.show', [
                                    'bike_rack' => $bikeRack,
                                ]) }}">
                                {{ $bikeRack->title }}
                            </a>
                        </td>

                        <td>{{ $bikeRack->district->name }}</td>

                        <td>{{ $bikeRack->slot_count }}</td>

                        <td>
                            {{ $bikeRack->scheduled_opening_on->format('d.m.Y') }}
                        </td>

                        <td>
                            {{ $bikeRack->status === 'draft' ? 'Чернетка' : 'Відкриття підтверджено' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-body-secondary">
                            На цій сторінці немає велопарковок.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $bikeRacks->links() }}
</x-layout>

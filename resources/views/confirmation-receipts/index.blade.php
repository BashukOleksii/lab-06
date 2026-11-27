<x-layout title="Чеки підтвердження відкриття">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
        <div>
            <h1 class="h3 mb-1">
                Чеки підтвердження відкриття
            </h1>

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

    <a href="{{ route('bike-racks.index') }}" class="btn btn-outline-secondary mb-3">
        До велопарковок
    </a>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('confirmation-receipts.index') }}" class="row g-2 align-items-end mb-4">

        <div class="col-sm-6 col-lg">
            <label for="search" class="form-label">
                Пошук
            </label>

            <input type="search" id="search" name="search" class="form-control"
                placeholder="Назва парковки або текст чека" value="{{ $filters['search'] ?? '' }}" maxlength="120">
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

            <a href="{{ route('confirmation-receipts.index') }}" class="btn btn-outline-secondary">
                Скинути
            </a>
        </div>
    </form>

    <p class="text-body-secondary">
        Знайдено {{ $receipts->total() }} записів.
    </p>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th scope="col">№ чека</th>
                    <th scope="col">Велопарковка</th>
                    <th scope="col">Квартал</th>
                    <th scope="col">Місць</th>
                    <th scope="col">Підтвердження</th>
                    <th scope="col">Дата формування</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($receipts as $receipt)
                    <tr>
                        <td>
                            {{ $receipt->id }}
                        </td>

                        <td>
                            <a
                                href="{{ route('bike-racks.show', [
                                    'bike_rack' => $receipt->bikeRack,
                                ]) }}">
                                {{ $receipt->bikeRack->title }}
                            </a>
                        </td>

                        <td>
                            {{ $receipt->bikeRack->district->name }}
                        </td>

                        <td>
                            {{ $receipt->bikeRack->slot_count }}
                        </td>

                        <td>
                            <span class="badge text-bg-success mb-1">
                                Підтверджено
                            </span>

                            <div>
                                {{ $receipt->summary }}
                            </div>
                        </td>

                        <td>
                            {{ $receipt->created_at->timezone('Europe/Kyiv')->format('d.m.Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-body-secondary">
                            Чеки підтвердження відкриття відсутні.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $receipts->links() }}
</x-layout>

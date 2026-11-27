<x-layout :title="$bikeRack->title">
    <h1 class="h3 mb-3">{{ $bikeRack->title }}</h1>

    <x-flash />

    <dl class="row">
        <dt class="col-sm-4">Квартал</dt>
        <dd class="col-sm-8">{{ $bikeRack->district->name }}</dd>

        <dt class="col-sm-4">Кількість місць</dt>
        <dd class="col-sm-8">{{ $bikeRack->slot_count }}</dd>

        <dt class="col-sm-4">Запланована дата відкриття</dt>
        <dd class="col-sm-8">{{ $bikeRack->scheduled_opening_on->format('d.m.Y') }}</dd>

        <dt class="col-sm-4">Статус</dt>
        <dd class="col-sm-8">
            {{ $bikeRack->status === 'draft' ? 'Чернетка' : 'Відкриття підтверджено' }}
        </dd>

        <dt class="col-sm-4">Опис</dt>
        <dd class="col-sm-8">{{ $bikeRack->description ?? 'Не вказано' }}</dd>
    </dl>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h5 mb-3">
                Чек підтвердження відкриття
            </h2>

            @if ($bikeRack->status === 'draft')
                <p class="text-body-secondary mb-0">
                    Чек буде сформовано після відкриття
                    велопарковки.
                </p>
            @elseif ($bikeRack->confirmationReceipt === null)
                <div class="alert alert-warning mb-3">
                    Відкриття велопарковки підтверджено.
                    Очікується формування чека.
                </div>

                <a href="{{ route('bike-racks.show', [
                    'bike_rack' => $bikeRack,
                ]) }}"
                    class="btn btn-outline-secondary btn-sm">
                    Оновити інформацію
                </a>
            @else
                <span class="badge text-bg-success mb-3">
                    Чек сформовано
                </span>

                <dl class="row mb-0">
                    <dt class="col-sm-4">
                        Номер чека
                    </dt>

                    <dd class="col-sm-8">
                        {{ $bikeRack->confirmationReceipt->id }}
                    </dd>

                    <dt class="col-sm-4">
                        Підтвердження
                    </dt>

                    <dd class="col-sm-8">
                        {{ $bikeRack->confirmationReceipt->summary }}
                    </dd>

                    <dt class="col-sm-4">
                        Дата формування
                    </dt>

                    <dd class="col-sm-8">
                        {{ $bikeRack->confirmationReceipt->created_at->timezone('Europe/Kyiv')->format('d.m.Y H:i') }}
                    </dd>
                </dl>
            @endif
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('bike-racks.index') }}" class="btn btn-outline-secondary">
            До списку
        </a>

        <a href="{{ route('bike-racks.edit', ['bike_rack' => $bikeRack]) }}" class="btn btn-outline-primary">
            Редагувати
        </a>

        @if ($bikeRack->status === 'draft')
            <form method="POST" action="{{ route('bike-racks.open', ['bike_rack' => $bikeRack]) }}">
                @csrf

                <button type="submit" class="btn btn-success">
                    Відкрити паркування
                </button>
            </form>
        @endif

        <form method="POST" action="{{ route('bike-racks.destroy', ['bike_rack' => $bikeRack]) }}"
            onsubmit="return confirm('Ви дійсно бажаєте видалити велопарковку?')">

            <button type="submit" class="btn btn-outline-danger">
                Видалити
            </button>
        </form>
    </div>
</x-layout>

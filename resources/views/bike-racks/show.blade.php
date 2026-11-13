<x-layout :title="$bikeRack->title">
    <h1 class="h3 mb-3">{{ $bikeRack->title }}</h1>

    @if (session('success'))
        <div class="alert alert-success" role="status">
            {{ session('success') }}
        </div>
    @endif

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

    <a href="{{ route('bike-racks.index') }}" class="btn btn-outline-secondary">
        До списку
    </a>
</x-layout>

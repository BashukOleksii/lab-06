<x-layout :title="$note->title">
    <h1 class="h3 mb-1">
        {{ $note->title }}
    </h1>

    <p class="text-body-secondary mb-3">
        Велопарковка: {{ $bikeRack->title }}
    </p>

    <x-flash />

    <div class="card mb-4">
        <div class="card-body">
            <div style="white-space: pre-wrap;">{{ $note->body }}</div>

            <hr>

            <dl class="row mb-0">
                <dt class="col-sm-4">
                    Створено
                </dt>

                <dd class="col-sm-8">
                    {{ $note->created_at->timezone('Europe/Kyiv')->format('d.m.Y H:i') }}
                </dd>

                <dt class="col-sm-4">
                    Оновлено
                </dt>

                <dd class="col-sm-8">
                    {{ $note->updated_at->timezone('Europe/Kyiv')->format('d.m.Y H:i') }}
                </dd>
            </dl>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('bike-racks.show', [
            'bike_rack' => $bikeRack,
        ]) }}"
            class="btn btn-outline-secondary">
            До велопарковки
        </a>

        <a href="{{ route('bike-racks.notes.edit', [
            'bike_rack' => $bikeRack,
            'note' => $note,
        ]) }}"
            class="btn btn-outline-primary">
            Редагувати
        </a>

        <form method="POST"
            action="{{ route('bike-racks.notes.destroy', [
                'bike_rack' => $bikeRack,
                'note' => $note,
            ]) }}"
            onsubmit="return confirm('Видалити примітку?')">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger">
                Видалити
            </button>
        </form>
    </div>
</x-layout>

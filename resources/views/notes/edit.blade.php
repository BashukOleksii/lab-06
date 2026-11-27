<x-layout title="Редагування примітки">
    <h1 class="h3 mb-1">
        Редагування примітки
    </h1>

    <p class="text-body-secondary mb-4">
        Велопарковка: {{ $bikeRack->title }}
    </p>

    <form method="POST"
        action="{{ route('bike-racks.notes.update', [
            'bike_rack' => $bikeRack,
            'note' => $note,
        ]) }}">
        @csrf
        @method('PATCH')

        @include('notes._form', ['note' => $note])

        <div class="d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-primary">
                Зберегти зміни
            </button>

            <a href="{{ route('bike-racks.notes.show', [
                'bike_rack' => $bikeRack,
                'note' => $note,
            ]) }}"
                class="btn btn-outline-secondary">
                Скасувати
            </a>
        </div>
    </form>
</x-layout>

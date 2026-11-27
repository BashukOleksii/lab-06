<x-layout title="Створення примітки">
    <h1 class="h3 mb-1">
        Створення примітки
    </h1>

    <p class="text-body-secondary mb-4">
        Велопарковка: {{ $bikeRack->title }}
    </p>

    <form method="POST"
        action="{{ route('bike-racks.notes.store', [
            'bike_rack' => $bikeRack,
        ]) }}">
        @csrf

        @include('notes._form', ['note' => null])

        <div class="d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-primary">
                Створити
            </button>

            <a href="{{ route('bike-racks.show', [
                'bike_rack' => $bikeRack,
            ]) }}"
                class="btn btn-outline-secondary">
                Скасувати
            </a>
        </div>
    </form>
</x-layout>

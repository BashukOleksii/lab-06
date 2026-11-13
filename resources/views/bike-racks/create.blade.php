<x-layout title="Додати велопарковку">
    <h1 class="h3 mb-3">Додати велопарковку</h1>

    <form method="POST" action="{{ route('bike-racks.store') }}" class="col-lg-7">
        @csrf

        <x-bike-racks.fields :districts="$districts" />

        <button type="submit" class="btn btn-primary">Зберегти чернетку</button>
        <a href="{{ route('bike-racks.index') }}" class="btn btn-outline-secondary">
            Скасувати
        </a>
    </form>
</x-layout>

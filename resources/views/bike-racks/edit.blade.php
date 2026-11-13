<x-layout title="Редагувати велопарковку">
    <h1 class="h3 mb-3">Редагувати велопарковку</h1>

    <form method="POST" action="{{ route('bike-racks.update', ['bike_rack' => $bikeRack]) }}" class="col-lg-7">
        @csrf
        @method('PATCH')

        <x-bike-racks.fields :districts="$districts" :bike-rack="$bikeRack" />

        <button type="submit" class="btn btn-primary">Зберегти зміни</button>
        <a href="{{ route('bike-racks.show', ['bike_rack' => $bikeRack]) }}" class="btn btn-outline-secondary">
            Скасувати
        </a>
    </form>
</x-layout>

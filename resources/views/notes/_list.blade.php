<section class="card mb-4">
    <div class="card-body">
        <div
            class="d-flex flex-wrap
                    justify-content-between
                    align-items-center gap-2 mb-3">

            <h2 class="h5 mb-0">
                Примітки ({{ $bikeRack->notes->count() }})
            </h2>

            <a href="{{ route('bike-racks.notes.create', [
                'bike_rack' => $bikeRack,
            ]) }}"
                class="btn btn-primary btn-sm">
                Додати примітку
            </a>
        </div>

        @forelse ($bikeRack->notes as $note)
            <div class="border rounded p-3 mb-2">
                <h3 class="h6">
                    <a
                        href="{{ route('bike-racks.notes.show', [
                            'bike_rack' => $bikeRack,
                            'note' => $note,
                        ]) }}">
                        {{ $note->title }}
                    </a>
                </h3>

                <p class="mb-2">
                    {{ \Illuminate\Support\Str::limit($note->body, 160) }}
                </p>

                <p class="small text-body-secondary">
                    {{ $note->created_at->timezone('Europe/Kyiv')->format('d.m.Y H:i') }}
                </p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('bike-racks.notes.show', [
                        'bike_rack' => $bikeRack,
                        'note' => $note,
                    ]) }}"
                        class="btn btn-outline-secondary btn-sm">
                        Переглянути
                    </a>

                    <a href="{{ route('bike-racks.notes.edit', [
                        'bike_rack' => $bikeRack,
                        'note' => $note,
                    ]) }}"
                        class="btn btn-outline-primary btn-sm">
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

                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Видалити
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-body-secondary mb-0">
                До цієї велопарковки ще не додано приміток.
            </p>
        @endforelse
    </div>
</section>

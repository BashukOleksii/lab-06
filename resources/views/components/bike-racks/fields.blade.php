@props(['districts', 'bikeRack' => null])

@php
    $value = static function (string $field, mixed $default = null): string {
        $input = old($field, $default);

        return is_scalar($input) ? (string) $input : '';
    };

    $selectedDistrict = $value('district_id', $bikeRack?->district_id);
@endphp

<div class="mb-3">
    <label for="title" class="form-label">Назва</label>
    <input type="text" id="title" name="title" value="{{ $value('title', $bikeRack?->title) }}"
        class="form-control @error('title') is-invalid @enderror" maxlength="120" required>
    <x-field-error field="title" />
</div>

<div class="mb-3">
    <label for="district_id" class="form-label">Квартал</label>
    <select id="district_id" name="district_id" class="form-select @error('district_id') is-invalid @enderror" required>
        <option value="">Оберіть квартал</option>

        @foreach ($districts as $district)
            <option value="{{ $district->id }}" @selected($selectedDistrict === (string) $district->id)>
                {{ $district->name }}
            </option>
        @endforeach
    </select>
    <x-field-error field="district_id" />
</div>

<div class="mb-3">
    <label for="slot_count" class="form-label">Кількість місць</label>
    <input type="number" id="slot_count" name="slot_count" value="{{ $value('slot_count', $bikeRack?->slot_count) }}"
        class="form-control @error('slot_count') is-invalid @enderror" min="1" max="1000" step="1"
        required>
    <x-field-error field="slot_count" />
</div>

<div class="mb-3">
    <label for="scheduled_opening_on" class="form-label">Запланована дата відкриття</label>
    <input type="date" id="scheduled_opening_on" name="scheduled_opening_on"
        value="{{ $value('scheduled_opening_on', $bikeRack?->scheduled_opening_on?->toDateString()) }}"
        class="form-control @error('scheduled_opening_on') is-invalid @enderror" required>
    <x-field-error field="scheduled_opening_on" />
</div>

<div class="mb-3">
    <label for="description" class="form-label">Опис</label>
    <textarea id="description" name="description" rows="3"
        class="form-control @error('description') is-invalid @enderror" maxlength="1000">{{ $value('description', $bikeRack?->description) }}</textarea>
    <x-field-error field="description" />
</div>

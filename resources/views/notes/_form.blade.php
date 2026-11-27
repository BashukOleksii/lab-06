<div class="mb-3">
    <label for="title" class="form-label">
        Заголовок
    </label>

    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $note?->title) }}" maxlength="120" required>

    <x-field-error field="title" />
</div>

<div class="mb-3">
    <label for="body" class="form-label">
        Текст примітки
    </label>

    <textarea id="body" name="body" class="form-control @error('body') is-invalid @enderror" rows="6"
        maxlength="2000" required>{{ old('body', $note?->body) }}</textarea>

    <x-field-error field="body" />
</div>

<x-layout title="Велопарковки">
    <h1 class="h3 mb-3">Велопарковки</h1>

    <p>Ви увійшли як {{ auth()->user()->name }}.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary">Вийти</button>
    </form>
</x-layout>

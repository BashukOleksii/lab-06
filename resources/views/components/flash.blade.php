@if (session('success'))
    <div class="alert alert-success" role="status">
        {{ session('success') }}
    </div>
@endif

@if (session('opening_error'))
    <div class="alert alert-danger" role="alert">
        {{ session('opening_error') }}
    </div>
@endif

<x-layout title="Вхід">
    <div class="row justify-content-center">
        <div class="col-sm-8 col-md-6 col-lg-4">
            <h1 class="h3 mb-3">Вхід</h1>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Електронна пошта</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" autocomplete="username" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" autocomplete="current-password"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Увійти</button>
            </form>
        </div>
    </div>
</x-layout>

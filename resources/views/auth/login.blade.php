<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="status-message">
            <i class="fas fa-check-circle"></i>
            {{ session('status') }}
        </div>
    @endif

    <h2>Selamat Datang</h2>
    <p class="subtitle">Masuk ke akun admin Anda</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email Anda"
                required
                autofocus
                autocomplete="username"
            >
            @error('email')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="Masukkan password"
                required
                autocomplete="current-password"
            >
            @error('password')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="remember-row">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Ingat saya
            </label>
            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i>
            Masuk
        </button>
    </form>
</x-guest-layout>

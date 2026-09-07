<x-guest-layout>
    <h2>Reset Password</h2>
    <p class="subtitle">Masukkan password baru Anda</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
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
            <label for="password">Password Baru</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="Masukkan password baru"
                required
                autocomplete="new-password"
            >
            @error('password')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                placeholder="Ulangi password baru"
                required
                autocomplete="new-password"
            >
            @error('password_confirmation')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px; margin-bottom: 16px;">
            <a class="forgot-link" href="{{ route('login') }}">
                &larr; Kembali ke login
            </a>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-lock"></i>
            Reset Password
        </button>
    </form>
</x-guest-layout>

<x-guest-layout>
    <h2>Buat Akun</h2>
    <p class="subtitle">Daftar untuk mengakses sistem</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Masukkan nama Anda"
                required
                autofocus
                autocomplete="name"
            >
            @error('name')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

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
                placeholder="Buat password"
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
                placeholder="Ulangi password"
                required
                autocomplete="new-password"
            >
            @error('password_confirmation')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px; margin-bottom: 16px;">
            <a class="forgot-link" href="{{ route('login') }}">
                Sudah punya akun? Masuk
            </a>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-user-plus"></i>
            Daftar
        </button>
    </form>
</x-guest-layout>

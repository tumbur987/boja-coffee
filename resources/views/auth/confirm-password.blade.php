<x-guest-layout>
    <h2>Konfirmasi Password</h2>
    <p class="subtitle">Ini adalah area aman. Masukkan password Anda untuk melanjutkan.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="Masukkan password Anda"
                required
                autocomplete="current-password"
            >
            @error('password')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-check-circle"></i>
            Konfirmasi
        </button>
    </form>
</x-guest-layout>

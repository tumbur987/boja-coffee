<x-guest-layout>
    <h2>Lupa Password?</h2>
    <p class="subtitle">Masukkan email Anda untuk menerima link reset password</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="status-message">
            <i class="fas fa-check-circle"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
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
            >
            @error('email')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px; margin-bottom: 16px;">
            <a class="forgot-link" href="{{ route('login') }}">
                &larr; Kembali ke login
            </a>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-paper-plane"></i>
            Kirim Link Reset
        </button>
    </form>
</x-guest-layout>

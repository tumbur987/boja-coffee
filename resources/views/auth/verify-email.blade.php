<x-guest-layout>
    <h2>Verifikasi Email</h2>
    <p class="subtitle">Terima kasih telah mendaftar! Silakan verifikasi email Anda dengan mengklik link yang kami kirimkan.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="status-message">
            <i class="fas fa-check-circle"></i>
            Link verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px; margin-bottom: 20px;">
        <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn-login" style="width: auto; padding: 14px 24px;">
                <i class="fas fa-redo"></i>
                Kirim Ulang
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="forgot-link" style="background: none; border: none; cursor: pointer; font-family: inherit; font-size: inherit;">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>

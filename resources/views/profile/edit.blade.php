@extends('layouts.admin.app')

@section('title', 'Profil Saya')

@section('content')

    {{-- ===== PROFILE INFO ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-user mr-2" style="color:var(--coffee);"></i>Informasi Profil</h5>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="alert alert-warning" style="border-radius:var(--radius-sm); font-size:13px;">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Email Anda belum diverifikasi.
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0" style="font-size:13px; font-weight:600; text-decoration:underline;">
                                Kirim ulang link verifikasi
                            </button>
                        </form>
                        @if (session('status') === 'verification-link-sent')
                            <br><small class="text-success">Link verifikasi telah dikirim ke email Anda.</small>
                        @endif
                    </div>
                @endif

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 28px;">
                        <i class="fas fa-save mr-1"></i> Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== UPDATE PASSWORD ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-lock mr-2" style="color:var(--coffee);"></i>Ubah Password</h5>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="current_password">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                   id="current_password" name="current_password" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                   id="password" name="password" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 28px;">
                        <i class="fas fa-save mr-1"></i> Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== DELETE ACCOUNT ===== --}}
    <div class="card mb-4" style="border-left: 4px solid var(--danger);">
        <div class="card-header">
            <h5 class="card-title" style="color:var(--danger);"><i class="fas fa-trash-alt mr-2"></i>Hapus Akun</h5>
        </div>
        <div class="card-body">
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">
                Setelah akun dihapus, semua data akan hilang secara permanen. Tindakan ini tidak dapat dibatalkan.
            </p>
            <button type="button" class="btn btn-danger" id="btnDeleteAccount">
                <i class="fas fa-trash-alt mr-1"></i> Hapus Akun
            </button>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color:var(--danger);"><i class="fas fa-exclamation-triangle mr-2"></i>Hapus Akun?</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p style="color:var(--text-muted); font-size:14px; margin-bottom:16px;">
                            Masukkan password Anda untuk mengonfirmasi penghapusan akun secara permanen.
                        </p>
                        <div class="form-group mb-0">
                            <label for="delete-password">Password</label>
                            <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                   id="delete-password" name="password" placeholder="Masukkan password" required>
                            @error('password', 'userDeletion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt mr-1"></i> Ya, Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    document.getElementById('btnDeleteAccount').addEventListener('click', function() {
        $('#deleteAccountModal').modal('show');
    });

    @if (session('status') === 'profile-updated')
        alertSuccess('Profil berhasil diperbarui.');
    @endif
    @if (session('status') === 'password-updated')
        alertSuccess('Password berhasil diperbarui.');
    @endif
    @if ($errors->userDeletion->isNotEmpty())
        $('#deleteAccountModal').modal('show');
    @endif
</script>
@endsection

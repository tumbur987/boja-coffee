@extends('layouts.admin.app')

@section('title', 'Meja')

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Tambah Meja</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>No Meja</th>
                        <th>Kode</th>
                        <th>QR Code</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tables as $table)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-weight:600;">Meja {{ $table->number }}</td>
                        <td><code style="background:var(--cream-lighter); padding:4px 10px; border-radius:4px; font-size:12px; font-weight:600;">{{ $table->code }}</code></td>
                        <td>
                            <button class="btn btn-info btn-sm btn-qr" data-id="{{ $table->id }}" data-number="{{ $table->number }}">
                                <i class="fas fa-qrcode"></i> Lihat
                            </button>
                            <a href="{{ route('table.qr-download', $table) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $table->id }}" data-number="{{ $table->number }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('table.destroy', $table) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding:30px; color:var(--text-muted);">
                            <i class="fas fa-inbox" style="font-size:32px; opacity:0.3; display:block; margin-bottom:8px;"></i>
                            Belum ada meja
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('table.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus-circle mr-2" style="color:var(--coffee);"></i>Tambah Meja</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>Nomor Meja</label>
                            <input type="text" class="form-control" name="number" placeholder="Contoh: 1" required>
                            <small class="text-muted">Kode akan digenerate otomatis (MJA001, MJA002, dst).</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit mr-2" style="color:var(--warning);"></i>Edit Meja</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label>Nomor Meja</label>
                            <input type="text" class="form-control" id="edit-number" name="number" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Perbarui</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- QR Preview Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalTitle">QR Code</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center">
                    <img id="qrImage" src="" alt="QR Code" style="width:100%; max-width:280px; border-radius:8px;">
                    <p class="mt-2" style="font-size:12px; color:var(--text-muted);" id="qrUrl"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <a id="qrDownloadBtn" href="" class="btn btn-success"><i class="fas fa-download mr-1"></i> Download</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.btn-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('edit-number').value = this.dataset.number;
            document.getElementById('editForm').action = '{{ url("table") }}/' + this.dataset.id;
            $('#editModal').modal('show');
        });
    });
    document.querySelectorAll('.btn-qr').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            var number = this.dataset.number;
            document.getElementById('qrModalTitle').textContent = 'QR - Meja ' + number;
            document.getElementById('qrImage').src = '{{ url("table") }}/' + id + '/qr-preview';
            document.getElementById('qrDownloadBtn').href = '{{ url("table") }}/' + id + '/qr-download';
            $('#qrModal').modal('show');
        });
    });
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = this.closest('form');
            confirmDelete(function() { form.submit(); });
        });
    });
</script>
@endsection

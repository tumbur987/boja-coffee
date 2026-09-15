@extends('layouts.admin.app')

@section('title', 'Transaksi')

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Tambah Transaksi</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Meja</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
                        <td><span class="badge badge-primary">Meja {{ $transaction->table->number }}</span></td>
                        <td style="font-weight:600;">{{ $transaction->customer_name ?? '-' }}</td>
                        <td>
                            <ul class="mb-0 pl-3" style="font-size:13px;">
                                @foreach ($transaction->items as $item)
                                    <li>{{ $item->product->name }} x{{ $item->quantity }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="font-weight:700; color:var(--coffee);">Rp.{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        <td>
                            @if ($transaction->status == 'lunas')
                                <span class="badge badge-success">Lunas</span>
                            @elseif ($transaction->status == 'selesai')
                                <span class="badge badge-info">Selesai</span>
                            @elseif ($transaction->status == 'cancelled')
                                <span class="badge badge-danger">Batal</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if ($transaction->status == 'lunas')
                                <form action="{{ route('transaction.selesai', $transaction) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm btn-selesai" title="Tandai Selesai"><i class="fas fa-check"></i></button>
                                </form>
                            @endif
                            <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $transaction->id }}"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('transaction.destroy', $transaction) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding:30px; color:var(--text-muted);">
                            <i class="fas fa-inbox" style="font-size:32px; opacity:0.3; display:block; margin-bottom:8px;"></i>
                            Belum ada transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($transactions->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 px-2 pagination-wrap">
                <small class="text-muted" style="font-size:13px;">Menampilkan {{ $transactions->firstItem() }}-{{ $transactions->lastItem() }} dari {{ $transactions->total() }} data</small>
                {{ $transactions->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('transaction.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus-circle mr-2" style="color:var(--coffee);"></i>Tambah Transaksi</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label><i class="fas fa-chair mr-1"></i>Meja</label>
                            <select class="form-control" name="table_id" required>
                                <option value="">-- Pilih Meja --</option>
                                @foreach ($tables as $table)
                                    <option value="{{ $table->id }}">Meja {{ $table->number }} ({{ $table->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <hr style="border-color:rgba(44,24,16,0.08); margin:16px 0;">
                        <label style="font-weight:700; color:var(--coffee-dark); font-size:13px;"><i class="fas fa-list-ul mr-1" style="color:var(--coffee);"></i> Item Pesanan</label>
                        <div id="create-items-container" class="mt-2">
                            <div class="item-card mb-2">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <select name="items[0][product_id]" class="form-control" required>
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} - Rp{{ number_format($product->price) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="items[0][quantity]" class="form-control" placeholder="Jumlah" min="1" max="50" value="1" required>
                                    </div>
                                    <div class="col-md-3 d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-2" id="create-add-item"><i class="fas fa-plus mr-1"></i> Tambah Item</button>
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
        <div class="modal-dialog modal-lg" role="document">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit mr-2" style="color:var(--warning);"></i>Edit Transaksi</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label><i class="fas fa-chair mr-1"></i>Meja</label>
                                    <select class="form-control" id="edit-table_id" name="table_id" required>
                                        <option value="">-- Pilih Meja --</option>
                                        @foreach ($tables as $table)
                                            <option value="{{ $table->id }}">Meja {{ $table->number }} ({{ $table->code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label><i class="fas fa-flag mr-1"></i>Status</label>
                                    <select class="form-control" id="edit-status" name="status" required>
                                        <option value="pending">Pending</option>
                                        <option value="lunas">Lunas</option>
                                        <option value="selesai">Selesai</option>
                                        <option value="cancelled">Dibatalkan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr style="border-color:rgba(44,24,16,0.08); margin:16px 0;">
                        <label style="font-weight:700; color:var(--coffee-dark); font-size:13px;"><i class="fas fa-list-ul mr-1" style="color:var(--coffee);"></i> Item Pesanan</label>
                        <div id="edit-items-container" class="mt-2"></div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-2" id="edit-add-item"><i class="fas fa-plus mr-1"></i> Tambah Item</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Perbarui</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    var products = @json($products);
    var createItemIndex = 1;
    var editItemIndex = 0;

    function addNewItem(containerId, prefix, index) {
        var container = document.getElementById(containerId);
        var card = document.createElement('div');
        card.className = 'item-card mb-2';
        var options = '<option value="">-- Pilih Produk --</option>';
        products.forEach(function(p) {
            options += '<option value="' + p.id + '">' + p.name + ' - Rp' + new Intl.NumberFormat('id-ID').format(p.price) + '</option>';
        });
        card.innerHTML =
            '<div class="row align-items-center">' +
                '<div class="col-md-6"><select name="' + prefix + '[' + index + '][product_id]" class="form-control" required>' + options + '</select></div>' +
                '<div class="col-md-3"><input type="number" name="' + prefix + '[' + index + '][quantity]" class="form-control" placeholder="Jumlah" min="1" max="50" value="1" required></div>' +
                '<div class="col-md-3 d-flex justify-content-end"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-item"><i class="fas fa-trash-alt"></i></button></div>' +
            '</div>';
        container.appendChild(card);
    }

    document.getElementById('create-add-item').addEventListener('click', function() { addNewItem('create-items-container', 'items', createItemIndex++); });
    document.getElementById('edit-add-item').addEventListener('click', function() { addNewItem('edit-items-container', 'items', editItemIndex++); });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-item') || e.target.closest('.btn-remove-item')) {
            var btn = e.target.closest('.btn-remove-item');
            btn.closest('.item-card').remove();
        }
    });

    document.querySelectorAll('.btn-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            fetch('{{ url("transaction") }}/' + id, { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                document.getElementById('edit-table_id').value = data.table_id;
                document.getElementById('edit-status').value = data.status;
                document.getElementById('editForm').action = '{{ url("transaction") }}/' + id;
                var container = document.getElementById('edit-items-container');
                container.innerHTML = '';
                editItemIndex = 0;
                data.items.forEach(function(item) {
                    addNewItem('edit-items-container', 'items', editItemIndex);
                    container.lastElementChild.querySelector('select').value = item.product_id;
                    container.lastElementChild.querySelector('input[type="number"]').value = item.quantity;
                    editItemIndex++;
                });
                $('#editModal').modal('show');
            });
        });
    });

    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = this.closest('form');
            confirmDelete(function() { form.submit(); });
        });
    });

    document.querySelectorAll('.btn-selesai').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');
            Swal.fire({
                title: 'Tandai Selesai?',
                text: 'Pesanan akan ditandai sebagai selesai dan pelanggan akan mendapat notifikasi.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(r) { if (r.isConfirmed) form.submit(); });
        });
    });
</script>
@endsection

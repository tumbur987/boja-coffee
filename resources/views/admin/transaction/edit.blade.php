@extends('layouts.admin.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Edit Transaksi</h3>
    </div>
    <form action="{{ route('transaction.update', $transaction) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="form-group">
                <label for="table_id">Meja</label>
                <select class="form-control" id="table_id" name="table_id" required>
                    <option value="">-- Pilih Meja --</option>
                    @foreach ($tables as $table)
                        <option value="{{ $table->id }}" {{ old('table_id', $transaction->table_id) == $table->id ? 'selected' : '' }}>Meja {{ $table->number }} ({{ $table->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending" {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ old('status', $transaction->status) == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="cancelled" {{ old('status', $transaction->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="card-header p-0 mt-3 mb-2">
                <h5>Item Pesanan</h5>
            </div>
            <div id="items-container">
                @foreach ($transaction->items as $idx => $item)
                <div class="row mb-2 item-row">
                    <div class="col-md-6">
                        <select name="items[{{ $idx }}][product_id]" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }} - Rp.{{ number_format($product->price) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="items[{{ $idx }}][quantity]" class="form-control" placeholder="Jumlah" min="1" value="{{ $item->quantity }}" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-remove-item">Hapus</button>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-success" id="add-item">+ Tambah Item</button>
        </div>
        <div class="card-footer">
            <a href="{{ route('transaction.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
    </form>
</div>

@section('scripts')
<script>
    let itemIndex = {{ count($transaction->items) }};
    const products = @json($products);

    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const row = document.createElement('div');
        row.className = 'row mb-2 item-row';
        row.innerHTML = `
            <div class="col-md-6">
                <select name="items[${itemIndex}][product_id]" class="form-control" required>
                    <option value="">-- Pilih Produk --</option>
                    ${products.map(p => `<option value="${p.id}">${p.name} - Rp.${new Intl.NumberFormat('id-ID').format(p.price)}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Jumlah" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-remove-item">Hapus</button>
            </div>
        `;
        container.appendChild(row);
        itemIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
</script>
@endsection
@endsection

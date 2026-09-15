@extends('layouts.admin.app')

@section('title', 'Produk')

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Tambah Produk</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr>
                        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                        <td style="font-weight:600;">{{ $product->name }}</td>
                        <td><span class="badge badge-primary">{{ $product->category->name }}</span></td>
                        <td>{{ $product->stock }}</td>
                        <td style="font-weight:700; color:var(--coffee);">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="40" height="40" style="border-radius:8px; object-fit:cover;">
                            @else
                                <span style="color:var(--text-muted); font-size:12px;">-</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit"
                                data-id="{{ $product->id }}"
                                data-category-id="{{ $product->category_id }}"
                                data-name="{{ $product->name }}"
                                data-stock="{{ $product->stock }}"
                                data-price="{{ $product->price }}"
                                data-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('product.destroy', $product) }}" method="POST" class="d-inline delete-form">
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
                            Belum ada produk
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($products->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 px-2 pagination-wrap">
                <small class="text-muted" style="font-size:13px;">Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari {{ $products->total() }} data</small>
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus-circle mr-2" style="color:var(--coffee);"></i>Tambah Produk</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-folder mr-1"></i>Kategori</label>
                                    <select class="form-control" name="category_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-coffee mr-1"></i>Nama Menu</label>
                                    <input type="text" class="form-control" name="name" placeholder="Contoh: Gula Aren" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-box mr-1"></i>Stok</label>
                                    <input type="number" class="form-control" name="stock" min="0" value="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-money-bill mr-1"></i>Harga (Rp)</label>
                                    <input type="number" class="form-control" name="price" min="0" placeholder="23000" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label><i class="fas fa-image mr-1"></i>Gambar</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
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
        <div class="modal-dialog modal-lg" role="document">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit mr-2" style="color:var(--warning);"></i>Edit Produk</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-folder mr-1"></i>Kategori</label>
                                    <select class="form-control" id="edit-category_id" name="category_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-coffee mr-1"></i>Nama Menu</label>
                                    <input type="text" class="form-control" id="edit-name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-box mr-1"></i>Stok</label>
                                    <input type="number" class="form-control" id="edit-stock" name="stock" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-money-bill mr-1"></i>Harga (Rp)</label>
                                    <input type="number" class="form-control" id="edit-price" name="price" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label><i class="fas fa-image mr-1"></i>Gambar <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <div id="edit-image-preview" class="mt-2" style="display:none;">
                                <img id="edit-image-img" src="" alt="" width="80" height="80" style="border-radius:8px; object-fit:cover;">
                            </div>
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
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.btn-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('edit-category_id').value = this.dataset.categoryId;
            document.getElementById('edit-name').value = this.dataset.name;
            document.getElementById('edit-stock').value = this.dataset.stock;
            document.getElementById('edit-price').value = this.dataset.price;
            var preview = document.getElementById('edit-image-preview');
            var img = document.getElementById('edit-image-img');
            if (this.dataset.image) { img.src = this.dataset.image; preview.style.display = 'block'; }
            else { preview.style.display = 'none'; }
            document.getElementById('editForm').action = '{{ url("product") }}/' + this.dataset.id;
            $('#editModal').modal('show');
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

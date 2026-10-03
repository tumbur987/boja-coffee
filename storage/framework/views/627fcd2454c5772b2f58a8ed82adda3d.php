<?php $__env->startSection('title', 'Produk'); ?>

<?php $__env->startSection('content'); ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e(($products->currentPage() - 1) * $products->perPage() + $loop->iteration); ?></td>
                        <td style="font-weight:600;"><?php echo e($product->name); ?></td>
                        <td><span class="badge badge-primary"><?php echo e($product->category->name); ?></span></td>
                        <td><?php echo e($product->stock); ?></td>
                        <td style="font-weight:700; color:var(--coffee);">Rp<?php echo e(number_format($product->price, 0, ',', '.')); ?></td>
                        <td>
                            <?php if($product->image): ?>
                                <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" width="40" height="40" style="border-radius:8px; object-fit:cover;">
                            <?php else: ?>
                                <span style="color:var(--text-muted); font-size:12px;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit"
                                data-id="<?php echo e($product->id); ?>"
                                data-category-id="<?php echo e($product->category_id); ?>"
                                data-name="<?php echo e($product->name); ?>"
                                data-stock="<?php echo e($product->stock); ?>"
                                data-price="<?php echo e($product->price); ?>"
                                data-image="<?php echo e($product->image ? asset('storage/' . $product->image) : ''); ?>"
                                data-description="<?php echo e($product->description); ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="<?php echo e(route('product.destroy', $product)); ?>" method="POST" class="d-inline delete-form">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="button" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center" style="padding:30px; color:var(--text-muted);">
                            <i class="fas fa-inbox" style="font-size:32px; opacity:0.3; display:block; margin-bottom:8px;"></i>
                            Belum ada produk
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if($products->hasPages()): ?>
            <div class="d-flex justify-content-between align-items-center mt-3 px-2 pagination-wrap">
                <small class="text-muted" style="font-size:13px;">Menampilkan <?php echo e($products->firstItem()); ?>-<?php echo e($products->lastItem()); ?> dari <?php echo e($products->total()); ?> data</small>
                <?php echo e($products->links('pagination::bootstrap-4')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form action="<?php echo e(route('product.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus-circle mr-2" style="color:var(--coffee);"></i>Tambah Produk</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select class="form-control" name="category_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Menu</label>
                                    <input type="text" class="form-control" name="name" placeholder="Contoh: Gula Aren" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stok</label>
                                    <input type="number" class="form-control" name="stock" min="0" value="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Harga (Rp)</label>
                                    <input type="number" class="form-control" name="price" min="0" placeholder="23000" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                        <div class="form-group mb-0">
                            <label>Deskripsi <small class="text-muted">(Maks. 500 karakter)</small></label>
                            <textarea class="form-control" name="description" rows="3" maxlength="500" placeholder="Contoh: Es kopi susu gula aren dengan susu segar dan es batu pilihan."></textarea>
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
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit mr-2" style="color:var(--warning);"></i>Edit Produk</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select class="form-control" id="edit-category_id" name="category_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Menu</label>
                                    <input type="text" class="form-control" id="edit-name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stok</label>
                                    <input type="number" class="form-control" id="edit-stock" name="stock" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Harga (Rp)</label>
                                    <input type="number" class="form-control" id="edit-price" name="price" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gambar <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <div id="edit-image-preview" class="mt-2" style="display:none;">
                                <img id="edit-image-img" src="" alt="" width="80" height="80" style="border-radius:8px; object-fit:cover;">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label>Deskripsi <small class="text-muted">(Maks. 500 karakter)</small></label>
                            <textarea class="form-control" id="edit-description" name="description" rows="3" maxlength="500" placeholder="Contoh: Es kopi susu gula aren dengan susu segar dan es batu pilihan."></textarea>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.querySelectorAll('.btn-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('edit-category_id').value = this.dataset.categoryId;
            document.getElementById('edit-name').value = this.dataset.name;
            document.getElementById('edit-stock').value = this.dataset.stock;
            document.getElementById('edit-price').value = this.dataset.price;
            document.getElementById('edit-description').value = this.dataset.description || '';
            var preview = document.getElementById('edit-image-preview');
            var img = document.getElementById('edit-image-img');
            if (this.dataset.image) { img.src = this.dataset.image; preview.style.display = 'block'; }
            else { preview.style.display = 'none'; }
            document.getElementById('editForm').action = '<?php echo e(url("product")); ?>/' + this.dataset.id;
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\GitHub\boja-coffee\resources\views/admin/product/index.blade.php ENDPATH**/ ?>
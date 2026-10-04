<?php $__env->startSection('title', 'Feedback Pelanggan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card" style="border-left: 4px solid var(--coffee);">
                <div class="card-body" style="padding: 18px 20px;">
                    <p style="font-size:12px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin:0;">Total Feedback</p>
                    <h3 style="font-size:26px; font-weight:800; color:var(--coffee-dark); margin:4px 0 0;"><?php echo e($total); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card" style="border-left: 4px solid var(--warning);">
                <div class="card-body" style="padding: 18px 20px;">
                    <p style="font-size:12px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin:0;">Rata-rata Rating</p>
                    <h3 style="font-size:26px; font-weight:800; color:var(--coffee-dark); margin:4px 0 0;">
                        <?php echo e($average); ?> <small style="font-size:14px; color:var(--text-muted); font-weight:600;">/ 5</small>
                    </h3>
                    <div style="font-size:14px; margin-top:2px;">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star" style="color: <?php echo e($i <= round($average) ? '#f59e0b' : '#e5e0d8'); ?>;"></i>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card" style="border-left: 4px solid var(--success);">
                <div class="card-body" style="padding: 18px 20px;">
                    <p style="font-size:12px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin:0;">Pelanggan Puas (4-5 Bintang)</p>
                    <h3 style="font-size:26px; font-weight:800; color:var(--coffee-dark); margin:4px 0 0;"><?php echo e($satisfiedPercent); ?>%</h3>
                    <small style="font-size:12px; color:var(--text-muted);"><?php echo e($satisfied); ?> dari <?php echo e($total); ?> feedback</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card" style="border-left: 4px solid var(--info);">
                <div class="card-body" style="padding: 18px 20px;">
                    <p style="font-size:12px; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin:0;">Komentar Masuk</p>
                    <h3 style="font-size:26px; font-weight:800; color:var(--coffee-dark); margin:4px 0 0;"><?php echo e($withComment); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2" style="color:var(--coffee);"></i>Sebaran Rating</h3>
                </div>
                <div class="card-body">
                    <?php for($i = 5; $i >= 1; $i--): ?>
                        <?php $count = $distribution[$i] ?? 0; ?>
                        <div class="d-flex align-items-center mb-2">
                            <span style="font-size:13px; font-weight:700; width:52px; color:var(--text-muted);"><?php echo e($i); ?> <i class="fas fa-star" style="color:#f59e0b; font-size:11px;"></i></span>
                            <div style="flex:1; height:10px; background:var(--cream-lighter); border-radius:10px; overflow:hidden;">
                                <div style="height:100%; width:<?php echo e($total ? round($count / $total * 100) : 0); ?>%; background:var(--coffee); border-radius:10px;"></div>
                            </div>
                            <span style="font-size:13px; font-weight:700; width:40px; text-align:right; color:var(--coffee-dark);"><?php echo e($count); ?></span>
                        </div>
                    <?php endfor; ?>
                    <?php if($total === 0): ?>
                        <p class="text-center mb-0" style="font-size:13px; color:var(--text-muted); padding:20px 0;">
                            <i class="fas fa-inbox" style="font-size:26px; opacity:0.3; display:block; margin-bottom:8px;"></i>
                            Belum ada feedback
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-star mr-2" style="color:var(--warning);"></i>Daftar Feedback</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex flex-wrap" style="gap:8px;">
                        <a href="<?php echo e(route('feedback.index')); ?>" class="btn btn-sm <?php echo e($ratingFilter === null ? 'btn-dark' : 'btn-outline-dark'); ?>">
                            <i class="fas fa-list mr-1"></i> Semua
                        </a>
                        <?php for($i = 5; $i >= 1; $i--): ?>
                            <a href="<?php echo e(route('feedback.index', ['rating' => $i])); ?>" class="btn btn-sm <?php echo e($ratingFilter === $i ? 'btn-warning' : 'btn-outline-warning'); ?>">
                                <?php echo e($i); ?> <i class="fas fa-star" style="font-size:10px;"></i>
                            </a>
                        <?php endfor; ?>
                        <?php if($ratingFilter): ?>
                            <a href="<?php echo e(route('feedback.index')); ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times mr-1"></i> Reset
                            </a>
                        <?php endif; ?>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width:40px;">No</th>
                                <th>Tanggal</th>
                                <th>Meja</th>
                                <th>Pelanggan</th>
                                <th style="width:110px;">Rating</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e(($feedbacks->currentPage() - 1) * $feedbacks->perPage() + $loop->iteration); ?></td>
                                    <td style="white-space:nowrap; font-size:13px;">
                                        <div style="font-weight:600;"><?php echo e($feedback->created_at->format('d/m/Y')); ?></div>
                                        <div style="color:var(--text-muted);"><?php echo e($feedback->created_at->format('H:i')); ?> WIB</div>
                                    </td>
                                    <td><span class="badge badge-primary">Meja <?php echo e($feedback->transaction->table->number ?? '-'); ?></span></td>
                                    <td style="font-weight:600;"><?php echo e($feedback->transaction->customer_name ?? '-'); ?></td>
                                    <td style="white-space:nowrap;">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star" style="color: <?php echo e($i <= $feedback->rating ? '#f59e0b' : '#e5e0d8'); ?>; font-size:13px;"></i>
                                        <?php endfor; ?>
                                    </td>
                                    <td style="font-size:13px;"><?php echo e($feedback->comment ?: '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center" style="padding:30px; color:var(--text-muted);">
                                        <i class="fas fa-inbox" style="font-size:32px; opacity:0.3; display:block; margin-bottom:8px;"></i>
                                        <?php echo e($ratingFilter ? 'Tidak ada feedback dengan rating tersebut' : 'Belum ada feedback dari pelanggan'); ?>

                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if($feedbacks->hasPages()): ?>
                        <div class="d-flex justify-content-between align-items-center mt-3 px-2 pagination-wrap">
                            <small class="text-muted" style="font-size:13px;">Menampilkan <?php echo e($feedbacks->firstItem()); ?>-<?php echo e($feedbacks->lastItem()); ?> dari <?php echo e($feedbacks->total()); ?> data</small>
                            <?php echo e($feedbacks->links('pagination::bootstrap-4')); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\Documents\GitHub\boja-coffee\resources\views/admin/feedback/index.blade.php ENDPATH**/ ?>
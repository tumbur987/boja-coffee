@extends('layouts.admin.app')

@section('title', 'Pengaturan Website')

@section('content')
<form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- ===== GENERAL ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-globe mr-2" style="color:var(--coffee);"></i>Informasi Umum</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Website</label>
                        <input type="text" class="form-control" name="site_name" value="{{ $settings['site_name'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tagline</label>
                        <input type="text" class="form-control" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea class="form-control" name="site_description" rows="3">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Logo <small class="text-muted">(opsional, ganti jika upload baru)</small></label>
                        <input type="file" class="form-control" name="site_logo" accept="image/*">
                        @if (!empty($settings['site_logo']))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" height="40" style="border-radius:8px;">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Favicon <small class="text-muted">(opsional)</small></label>
                        <input type="file" class="form-control" name="site_favicon" accept="image/*">
                        @if (!empty($settings['site_favicon']))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" height="32">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== CONTACT ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-address-card mr-2" style="color:var(--coffee);"></i>Kontak & Sosial Media</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-envelope mr-1"></i> Email</label>
                        <input type="email" class="form-control" name="site_email" value="{{ $settings['site_email'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-phone mr-1"></i> Telepon</label>
                        <input type="text" class="form-control" name="site_phone" value="{{ $settings['site_phone'] ?? '' }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fab fa-whatsapp mr-1"></i> WhatsApp <small class="text-muted">(kode negara + nomor)</small></label>
                        <input type="text" class="form-control" name="site_whatsapp" value="{{ $settings['site_whatsapp'] ?? '' }}" placeholder="6281234567890">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt mr-1"></i> Alamat</label>
                        <input type="text" class="form-control" name="site_address" value="{{ $settings['site_address'] ?? '' }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fab fa-instagram mr-1"></i> Instagram</label>
                        <input type="text" class="form-control" name="site_instagram" value="{{ $settings['site_instagram'] ?? '' }}" placeholder="@username">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fab fa-tiktok mr-1"></i> TikTok</label>
                        <input type="text" class="form-control" name="site_tiktok" value="{{ $settings['site_tiktok'] ?? '' }}" placeholder="@username">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fab fa-facebook mr-1"></i> Facebook</label>
                        <input type="text" class="form-control" name="site_facebook" value="{{ $settings['site_facebook'] ?? '' }}" placeholder="Nama Halaman">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== HERO ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-image mr-2" style="color:var(--coffee);"></i>Hero Section (Landing Page)</h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Badge Text</label>
                <input type="text" class="form-control" name="hero_badge" value="{{ $settings['hero_badge'] ?? '' }}">
            </div>
            <div class="form-group">
                <label>Judul Hero <small class="text-muted">(gunakan &lt;em&gt; untuk highlight)</small></label>
                <input type="text" class="form-control" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}">
            </div>
            <div class="form-group">
                <label>Subtitle Hero</label>
                <textarea class="form-control" name="hero_subtitle" rows="2">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- ===== ABOUT ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-info-circle mr-2" style="color:var(--coffee);"></i>Tentang Kami</h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Judul</label>
                <input type="text" class="form-control" name="about_title" value="{{ $settings['about_title'] ?? '' }}">
            </div>
            <div class="form-group mb-0">
                <label>Deskripsi</label>
                <textarea class="form-control" name="about_description" rows="2">{{ $settings['about_description'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- ===== CTA ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-bullhorn mr-2" style="color:var(--coffee);"></i>Call to Action</h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Judul CTA</label>
                <input type="text" class="form-control" name="cta_title" value="{{ $settings['cta_title'] ?? '' }}">
            </div>
            <div class="form-group mb-0">
                <label>Deskripsi CTA</label>
                <textarea class="form-control" name="cta_description" rows="2">{{ $settings['cta_description'] ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- ===== FOOTER & SEO ===== --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-search mr-2" style="color:var(--coffee);"></i>Footer & SEO</h5>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Footer Text</label>
                <input type="text" class="form-control" name="footer_text" value="{{ $settings['footer_text'] ?? '' }}">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" class="form-control" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-0">
                        <label>Meta Description</label>
                        <input type="text" class="form-control" name="meta_description" value="{{ $settings['meta_description'] ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-right mb-4">
        <button type="submit" class="btn btn-primary btn-lg" style="padding: 12px 40px;">
            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
        </button>
    </div>
</form>
@endsection

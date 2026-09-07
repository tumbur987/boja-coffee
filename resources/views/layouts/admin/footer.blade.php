<footer class="main-footer" style="text-align: center;">
    <span style="font-weight: 600;">☕ {{ \App\Models\Setting::get('site_name', 'SiBoja Coffee') }}</span> &copy; {{ date('Y') }}
    {{ \App\Models\Setting::get('footer_text', 'All rights reserved.') }}
    <div class="float-right d-none d-sm-inline-block">
        <span style="font-size: 11px; opacity: 0.6;">v1.0</span>
    </div>
</footer>

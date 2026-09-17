<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::allKeyValue();

        return view('admin.setting.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $old = Setting::get('site_logo');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $data['site_logo'] = $request->file('site_logo')->store('settings', 'public');
        }

        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $old = Setting::get('site_favicon');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $data['site_favicon'] = $request->file('site_favicon')->store('settings', 'public');
        }

        Setting::setMany($data);

        return redirect()->route('setting.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    use HandlesUploads;

    public function edit()
    {
        return view('admin.settings', ['settings' => SiteSetting::firstOrCreate(['id' => 1], ['site_title' => 'Happy Birthday'])]);
    }

    public function update(Request $request)
    {
        $settings = SiteSetting::firstOrCreate(['id' => 1], ['site_title' => 'Happy Birthday']);
        $data = $request->validate([
            'site_title' => ['required', 'string', 'max:150'],
            'primary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'secondary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'footer_message' => ['nullable', 'string'],
            'final_message' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,ico', 'max:2048'],
            'final_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        foreach (['logo', 'favicon', 'final_photo'] as $field) {
            if ($request->hasFile($field)) {
                $this->removeUpload($settings->{$field});
                $data[$field] = $this->upload($request->file($field), 'birthday/settings');
            }
        }
        foreach (['enable_hearts', 'enable_confetti', 'enable_music'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }
        $settings->update($data);

        return back()->with('success', 'Site settings saved.');
    }
}

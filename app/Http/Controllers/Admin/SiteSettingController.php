<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CloudinaryImageException;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;
use Throwable;

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
            'story_eyebrow' => ['nullable', 'string', 'max:100'],
            'story_title' => ['nullable', 'string', 'max:100'],
            'story_title_emphasis' => ['nullable', 'string', 'max:100'],
            'story_description' => ['nullable', 'string', 'max:500'],
            'footer_message' => ['nullable', 'string'],
            'final_message' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,ico', 'max:2048'],
            'final_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $newAssets = [];
        $oldAssets = [];
        $failedField = 'final_photo';

        try {
            foreach (['logo', 'favicon', 'final_photo'] as $field) {
                if ($request->hasFile($field)) {
                    $failedField = $field;
                    $asset = $this->upload($request->file($field), 'birthday/settings');
                    $newAssets[] = $asset;
                    $oldAssets[$field] = [$settings->{$field}, $settings->{$field.'_public_id'}];
                    $data[$field] = $asset['url'];
                    $data[$field.'_public_id'] = $asset['public_id'];
                }
            }

            foreach (['enable_hearts', 'enable_confetti', 'enable_music'] as $flag) {
                $data[$flag] = $request->boolean($flag);
            }

            $settings->update($data);
        } catch (CloudinaryImageException $exception) {
            $this->cleanupUploadedAssets($newAssets);

            $label = str_replace('_', ' ', $failedField);

            return back()->withInput()->withErrors([
                $failedField => "The {$label} could not be uploaded. Please try again.",
            ]);
        } catch (Throwable $exception) {
            $this->cleanupUploadedAssets($newAssets);
            report($exception);

            return back()->withInput()->withErrors([
                $failedField => 'The site settings could not be saved. Please try again.',
            ]);
        }

        try {
            foreach ($oldAssets as [$path, $publicId]) {
                $this->removeUpload($path, $publicId);
            }
        } catch (CloudinaryImageException $exception) {
            return back()->withErrors([
                'final_photo' => 'The previous site image could not be removed. Please try again.',
            ]);
        }

        return back()->with('success', 'Site settings saved.');
    }
}

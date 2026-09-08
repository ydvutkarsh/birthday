<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CloudinaryImageException;
use App\Http\Controllers\Controller;
use App\Models\BirthdayProfile;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;
use Throwable;

class BirthdayProfileController extends Controller
{
    use HandlesUploads;

    public function edit()
    {
        return view('admin.profile', ['profile' => BirthdayProfile::firstOrCreate(['id' => 1], ['name' => 'My Love'])]);
    }

    public function update(Request $request)
    {
        $profile = BirthdayProfile::firstOrCreate(['id' => 1], ['name' => 'My Love']);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'birthday_date' => ['nullable', 'date'],
            'hero_title' => ['required', 'string', 'max:150'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'intro_message' => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $newAssets = [];
        $oldAssets = [];

        try {
            foreach (['profile_image', 'cover_image'] as $field) {
                if ($request->hasFile($field)) {
                    $asset = $this->upload($request->file($field), 'birthday/profile');
                    $newAssets[] = $asset;
                    $oldAssets[$field] = [$profile->{$field}, $profile->{$field.'_public_id'}];
                    $data[$field] = $asset['url'];
                    $data[$field.'_public_id'] = $asset['public_id'];
                }
            }

            $profile->update($data);
        } catch (CloudinaryImageException $exception) {
            $this->cleanupUploadedAssets($newAssets);

            return back()->withInput()->withErrors(['profile_image' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            $this->cleanupUploadedAssets($newAssets);
            throw $exception;
        }

        try {
            foreach ($oldAssets as [$path, $publicId]) {
                $this->removeUpload($path, $publicId);
            }
        } catch (CloudinaryImageException $exception) {
            return back()->withErrors(['profile_image' => $exception->getMessage()]);
        }

        return back()->with('success', 'Birthday profile saved.');
    }
}

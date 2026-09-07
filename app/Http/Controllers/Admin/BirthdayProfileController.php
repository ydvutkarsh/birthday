<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthdayProfile;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

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
            'name' => ['required', 'string', 'max:100'], 'nickname' => ['nullable', 'string', 'max:100'], 'birthday_date' => ['nullable', 'date'],
            'hero_title' => ['required', 'string', 'max:150'], 'hero_subtitle' => ['nullable', 'string', 'max:255'], 'intro_message' => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], 'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        foreach (['profile_image', 'cover_image'] as $field) {
            if ($request->hasFile($field)) {
                $this->removeUpload($profile->{$field});
                $data[$field] = $this->upload($request->file($field), 'birthday/profile');
            }
        }
        $profile->update($data);

        return back()->with('success', 'Birthday profile saved.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CloudinaryImageException;
use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class GalleryController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.gallery.index', ['photos' => GalleryImage::orderBy('sort_order')->latest()->get()]);
    }

    public function create()
    {
        return view('admin.gallery.form', ['photo' => new GalleryImage]);
    }

    public function store(Request $request)
    {
        $photo = new GalleryImage;

        try {
            $this->save($request, $photo);
        } catch (CloudinaryImageException $exception) {
            return back()->withInput()->withErrors(['image' => $exception->getMessage()]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Photo uploaded.');
    }

    public function edit(GalleryImage $gallery)
    {
        return view('admin.gallery.form', ['photo' => $gallery]);
    }

    public function update(Request $request, GalleryImage $gallery)
    {
        try {
            $this->save($request, $gallery);
        } catch (CloudinaryImageException $exception) {
            return back()->withInput()->withErrors(['image' => $exception->getMessage()]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Photo updated.');
    }

    public function destroy(GalleryImage $gallery)
    {
        try {
            $this->removeUpload($gallery->image, $gallery->image_public_id);
        } catch (CloudinaryImageException $exception) {
            return back()->withErrors(['image' => $exception->getMessage()]);
        }

        $gallery->delete();

        return back()->with('success', 'Photo removed.');
    }

    private function save(Request $request, GalleryImage $photo): void
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'caption' => ['nullable', 'string', 'max:255'],
            'memory_date' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'image' => [$photo->exists ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $newAsset = null;
        $oldImage = [$photo->image, $photo->image_public_id];

        try {
            if ($request->hasFile('image')) {
                $newAsset = $this->upload($request->file('image'), 'birthday/gallery');
                $data['image'] = $newAsset['url'];
                $data['image_public_id'] = $newAsset['public_id'];
            }

            $data['is_featured'] = $request->boolean('is_featured');
            DB::transaction(fn () => $photo->fill($data)->save());
        } catch (Throwable $exception) {
            if ($newAsset) {
                $this->cleanupUploadedAssets([$newAsset]);
            }
            throw $exception;
        }

        if ($newAsset) {
            $this->removeUpload($oldImage[0], $oldImage[1]);
        }
    }
}

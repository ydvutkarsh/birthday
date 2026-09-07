<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    use HandlesUploads;

    public function index() { return view('admin.gallery.index', ['photos' => GalleryImage::orderBy('sort_order')->latest()->get()]); }
    public function create() { return view('admin.gallery.form', ['photo' => new GalleryImage]); }
    public function store(Request $request) { $photo = new GalleryImage; $this->save($request, $photo); return redirect()->route('admin.gallery.index')->with('success', 'Photo uploaded.'); }
    public function edit(GalleryImage $gallery) { return view('admin.gallery.form', ['photo' => $gallery]); }
    public function update(Request $request, GalleryImage $gallery) { $this->save($request, $gallery); return redirect()->route('admin.gallery.index')->with('success', 'Photo updated.'); }
    public function destroy(GalleryImage $gallery) { $this->removeUpload($gallery->image); $gallery->delete(); return back()->with('success', 'Photo removed.'); }

    private function save(Request $request, GalleryImage $photo): void
    {
        $data = $request->validate(['title' => ['nullable', 'string', 'max:150'], 'caption' => ['nullable', 'string', 'max:255'], 'memory_date' => ['nullable', 'date'], 'sort_order' => ['nullable', 'integer', 'min:0'], 'is_featured' => ['nullable', 'boolean'], 'image' => [$photo->exists ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);
        if ($request->hasFile('image')) { $this->removeUpload($photo->image); $data['image'] = $this->upload($request->file('image'), 'birthday/gallery'); }
        $data['is_featured'] = $request->boolean('is_featured');
        $photo->fill($data)->save();
    }
}

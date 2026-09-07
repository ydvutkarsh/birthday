<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Memory;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;

class MemoryController extends Controller
{
    use HandlesUploads;

    public function index() { return view('admin.memories.index', ['memories' => Memory::with('images')->orderBy('sort_order')->latest()->get()]); }
    public function create() { return view('admin.memories.form', ['memory' => new Memory]); }
    public function store(Request $request) { $memory = new Memory; $this->save($request, $memory); return redirect()->route('admin.memories.index')->with('success', 'Memory created.'); }
    public function edit(Memory $memory) { return view('admin.memories.form', compact('memory')); }
    public function update(Request $request, Memory $memory) { $this->save($request, $memory); return redirect()->route('admin.memories.index')->with('success', 'Memory updated.'); }
    public function destroy(Memory $memory) { $this->removeUpload($memory->cover_image); foreach ($memory->images as $image) { $this->removeUpload($image->image); } $memory->delete(); return back()->with('success', 'Memory deleted.'); }

    private function save(Request $request, Memory $memory): void
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'], 'memory_date' => ['nullable', 'date'], 'short_description' => ['nullable', 'string', 'max:255'], 'full_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'], 'status' => ['required', 'in:published,draft'], 'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_featured' => ['nullable', 'boolean'], 'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        if ($request->hasFile('cover_image')) { $this->removeUpload($memory->cover_image); $data['cover_image'] = $this->upload($request->file('cover_image'), 'birthday/memories'); }
        $data['is_featured'] = $request->boolean('is_featured');
        $memory->fill($data)->save();
        foreach ($request->file('images', []) as $file) { $memory->images()->create(['image' => $this->upload($file, 'birthday/memories'), 'sort_order' => $memory->images()->count()]); }
    }
}

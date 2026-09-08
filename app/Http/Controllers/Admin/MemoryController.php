<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CloudinaryImageException;
use App\Http\Controllers\Controller;
use App\Models\Memory;
use App\Support\HandlesUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class MemoryController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        return view('admin.memories.index', ['memories' => Memory::with('images')->orderBy('sort_order')->latest()->get()]);
    }

    public function create()
    {
        return view('admin.memories.form', ['memory' => new Memory]);
    }

    public function store(Request $request)
    {
        $memory = new Memory;

        try {
            $this->save($request, $memory);
        } catch (CloudinaryImageException $exception) {
            return back()->withInput()->withErrors([
                'cover_image' => 'The memory image could not be uploaded. Please try again.',
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput()->withErrors([
                'cover_image' => 'The memory could not be saved. Please try again.',
            ]);
        }

        return redirect()->route('admin.memories.index')->with('success', 'Memory created.');
    }

    public function edit(Memory $memory)
    {
        return view('admin.memories.form', compact('memory'));
    }

    public function update(Request $request, Memory $memory)
    {
        try {
            $this->save($request, $memory);
        } catch (CloudinaryImageException $exception) {
            return back()->withInput()->withErrors([
                'cover_image' => 'The memory image could not be uploaded. Please try again.',
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput()->withErrors([
                'cover_image' => 'The memory could not be saved. Please try again.',
            ]);
        }

        return redirect()->route('admin.memories.index')->with('success', 'Memory updated.');
    }

    public function destroy(Memory $memory)
    {
        try {
            $this->removeUpload($memory->cover_image, $memory->cover_image_public_id);
            foreach ($memory->images as $image) {
                $this->removeUpload($image->image, $image->image_public_id);
            }
        } catch (CloudinaryImageException $exception) {
            return back()->withErrors([
                'cover_image' => 'The memory images could not be removed. Please try again.',
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'cover_image' => 'The memory images could not be removed. Please try again.',
            ]);
        }

        $memory->delete();

        return back()->with('success', 'Memory deleted.');
    }

    private function save(Request $request, Memory $memory): void
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'memory_date' => ['nullable', 'date'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'full_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:published,draft'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_featured' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $newAssets = [];
        $coverAsset = null;
        $additionalAssets = [];
        $oldCover = [$memory->cover_image, $memory->cover_image_public_id];

        try {
            if ($request->hasFile('cover_image')) {
                $coverAsset = $this->upload($request->file('cover_image'), 'birthday/memories');
                $newAssets[] = $coverAsset;
                $data['cover_image'] = $coverAsset['url'];
                $data['cover_image_public_id'] = $coverAsset['public_id'];
            }

            foreach ($request->file('images', []) as $file) {
                $asset = $this->upload($file, 'birthday/memories');
                $newAssets[] = $asset;
                $additionalAssets[] = $asset;
            }

            $data['is_featured'] = $request->boolean('is_featured');

            DB::transaction(function () use ($memory, $data, $additionalAssets): void {
                $memory->fill($data)->save();

                foreach ($additionalAssets as $asset) {
                    $memory->images()->create([
                        'image' => $asset['url'],
                        'image_public_id' => $asset['public_id'],
                        'sort_order' => $memory->images()->count(),
                    ]);
                }
            });
        } catch (Throwable $exception) {
            $this->cleanupUploadedAssets($newAssets);
            throw $exception;
        }

        if ($coverAsset) {
            $this->removeUpload($oldCover[0], $oldCover[1]);
        }
    }
}

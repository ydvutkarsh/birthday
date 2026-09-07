<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MusicPlaylist;
use Illuminate\Http\Request;

class MusicPlaylistController extends Controller
{
    public function index() { return view('admin.music.index', ['songs' => MusicPlaylist::orderBy('sort_order')->latest()->get()]); }
    public function create() { return view('admin.music.form', ['song' => new MusicPlaylist]); }
    public function store(Request $request) { $song = new MusicPlaylist; $this->save($request, $song); return redirect()->route('admin.music.index')->with('success', 'Track added.'); }
    public function edit(MusicPlaylist $music) { return view('admin.music.form', ['song' => $music]); }
    public function update(Request $request, MusicPlaylist $music) { $this->save($request, $music); return redirect()->route('admin.music.index')->with('success', 'Track updated.'); }
    public function destroy(MusicPlaylist $music) { $music->delete(); return back()->with('success', 'Track deleted.'); }
    private function save(Request $request, MusicPlaylist $song): void { $data = $request->validate(['title' => ['required', 'string', 'max:150'], 'youtube_url' => ['required', 'url', 'max:500'], 'sort_order' => ['nullable', 'integer', 'min:0'], 'is_active' => ['nullable', 'boolean']]); $data = array_merge($data, MusicPlaylist::prepareUrl($data['youtube_url'])); $data['is_active'] = $request->boolean('is_active'); $song->fill($data)->save(); }
}

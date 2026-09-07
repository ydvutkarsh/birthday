<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthdayMessage;
use App\Models\GalleryImage;
use App\Models\Memory;
use App\Models\MusicPlaylist;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'counts' => [
                'memories' => Memory::count(),
                'photos' => GalleryImage::count(),
                'messages' => BirthdayMessage::count(),
                'songs' => MusicPlaylist::count(),
            ],
            'latestMemories' => Memory::latest()->take(5)->get(),
            'recentPhotos' => GalleryImage::latest()->take(6)->get(),
        ]);
    }
}

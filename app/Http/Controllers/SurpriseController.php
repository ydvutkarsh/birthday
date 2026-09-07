<?php

namespace App\Http\Controllers;

use App\Models\BirthdayMessage;
use App\Models\BirthdayProfile;
use App\Models\GalleryImage;
use App\Models\LoveLetter;
use App\Models\Memory;
use App\Models\MusicPlaylist;
use App\Models\SiteSetting;

class SurpriseController extends Controller
{
    public function index()
    {
        return view('surprise', [
            'profile' => BirthdayProfile::first(),
            'memories' => Memory::with('images')->where('status', 'published')->orderBy('sort_order')->orderBy('memory_date')->get(),
            'gallery' => GalleryImage::orderBy('sort_order')->latest()->get(),
            'messages' => BirthdayMessage::where('status', 'published')->orderBy('sort_order')->get(),
            'songs' => MusicPlaylist::where('is_active', true)->orderBy('sort_order')->get(),
            'letter' => LoveLetter::where('status', 'published')->latest()->first(),
            'settings' => SiteSetting::first(),
        ]);
    }
}

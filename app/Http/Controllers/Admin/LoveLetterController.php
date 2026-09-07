<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoveLetter;
use Illuminate\Http\Request;

class LoveLetterController extends Controller
{
    public function edit() { return view('admin.letter', ['letter' => LoveLetter::firstOrCreate(['id' => 1], ['content' => 'Write your heart out here…'])]); }
    public function update(Request $request) { $letter = LoveLetter::firstOrCreate(['id' => 1], ['content' => '']); $letter->update($request->validate(['title' => ['required', 'string', 'max:200'], 'content' => ['required', 'string'], 'signature' => ['nullable', 'string', 'max:100'], 'status' => ['required', 'in:published,draft']])); return back()->with('success', 'Love letter saved.'); }
}

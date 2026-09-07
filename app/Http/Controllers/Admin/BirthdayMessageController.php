<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BirthdayMessage;
use Illuminate\Http\Request;

class BirthdayMessageController extends Controller
{
    public function index() { return view('admin.messages.index', ['messages' => BirthdayMessage::orderBy('sort_order')->latest()->get()]); }
    public function create() { return view('admin.messages.form', ['message' => new BirthdayMessage]); }
    public function store(Request $request) { BirthdayMessage::create($this->data($request)); return redirect()->route('admin.messages.index')->with('success', 'Message added.'); }
    public function edit(BirthdayMessage $message) { return view('admin.messages.form', compact('message')); }
    public function update(Request $request, BirthdayMessage $message) { $message->update($this->data($request)); return redirect()->route('admin.messages.index')->with('success', 'Message updated.'); }
    public function destroy(BirthdayMessage $message) { $message->delete(); return back()->with('success', 'Message deleted.'); }
    private function data(Request $request): array { return $request->validate(['title' => ['required', 'string', 'max:100'], 'message' => ['required', 'string'], 'icon' => ['nullable', 'string', 'max:10'], 'sort_order' => ['nullable', 'integer', 'min:0'], 'status' => ['required', 'in:published,draft']]); }
}

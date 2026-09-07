@extends('layouts.admin')
@section('content')
    <div class="studio-top">
        <div>
            <p class="eyebrow">From the heart</p>
            <h1>Love <em>letter</em></h1>
            <p class="muted">The words she can open when she is ready.</p>
        </div>
    </div>@include('admin.partials.flash')
    <form method="POST" action="{{ route('admin.letter.update') }}" class="studio-card form-card">@csrf
        @method('PUT')<label>Letter title</label><input name="title" value="{{ old('title', $letter->title) }}"
            required><label>Your letter</label><textarea name="content" rows="12"
            required>{{ old('content', $letter->content) }}</textarea><label>Signature</label><input name="signature"
            value="{{ old('signature', $letter->signature) }}"><label>Status</label><select name="status">
            <option value="published" @selected($letter->status === 'published')>Published</option>
            <option value="draft" @selected($letter->status === 'draft')>Draft</option>
</select><button class="btn btn-dark-gold mt-4">Save love letter</button></form>@endsection
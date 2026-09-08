@extends('layouts.admin')
@section('content')
<div class="studio-top"><div><p class="eyebrow">The atmosphere</p><h1>Site <em>settings</em></h1><p class="muted">Small details that keep the experience feeling intentional.</p></div></div>
@include('admin.partials.flash')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="studio-card form-card">
@csrf @method('PUT')
<label>Site title</label><input name="site_title" value="{{ old('site_title',$settings->site_title) }}" required>
<div class="row g-3"><div class="col-md-6"><label>Primary color</label><input type="color" name="primary_color" value="{{ $settings->primary_color }}"></div><div class="col-md-6"><label>Secondary color</label><input type="color" name="secondary_color" value="{{ $settings->secondary_color }}"></div></div>
<div class="settings-section-heading"><span class="eyebrow">Timeline section</span><h2>Our story</h2><p class="muted">Update the heading and introduction shown above the memory chapters.</p></div>
<label>Section eyebrow</label><input name="story_eyebrow" value="{{ old('story_eyebrow',$settings->story_eyebrow ?? '02 · the chapters') }}" maxlength="100">
<div class="row g-3"><div class="col-md-6"><label>Section title</label><input name="story_title" value="{{ old('story_title',$settings->story_title ?? 'Our story,') }}" maxlength="100"></div><div class="col-md-6"><label>Highlighted title</label><input name="story_title_emphasis" value="{{ old('story_title_emphasis',$settings->story_title_emphasis ?? 'so far.') }}" maxlength="100"></div></div>
<label>Section description</label><textarea name="story_description" rows="3" maxlength="500">{{ old('story_description',$settings->story_description ?? 'Every ordinary moment became something I wanted to remember.') }}</textarea>
<label>Footer message</label><textarea name="footer_message" rows="2">{{ old('footer_message',$settings->footer_message) }}</textarea>
<label>Final surprise message</label><textarea name="final_message" rows="4">{{ old('final_message',$settings->final_message) }}</textarea>
<label>Final surprise photo</label><input type="file" name="final_photo" accept="image/*">@if($settings->final_photo)<img class="form-preview" src="{{ image_url($settings->final_photo) }}">@endif
<div class="check-stack"><label><input type="checkbox" name="enable_hearts" value="1" @checked($settings->enable_hearts)> Floating hearts</label><label><input type="checkbox" name="enable_confetti" value="1" @checked($settings->enable_confetti)> Final confetti</label><label><input type="checkbox" name="enable_music" value="1" @checked($settings->enable_music)> Music player</label></div>
<button class="btn btn-dark-gold mt-4">Save settings</button>
</form>
@endsection

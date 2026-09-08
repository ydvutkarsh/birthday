@extends('layouts.admin')
@section('content')
<div class="studio-top"><div><p class="eyebrow">The atmosphere</p><h1>Site <em>settings</em></h1><p class="muted">Small details that keep the experience feeling intentional.</p></div></div>
@include('admin.partials.flash')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="studio-card form-card">
@csrf @method('PUT')
<label>Site title</label><input name="site_title" value="{{ old('site_title',$settings->site_title) }}" required>
<div class="row g-3"><div class="col-md-6"><label>Primary color</label><input type="color" name="primary_color" value="{{ $settings->primary_color }}"></div><div class="col-md-6"><label>Secondary color</label><input type="color" name="secondary_color" value="{{ $settings->secondary_color }}"></div></div>
<label>Footer message</label><textarea name="footer_message" rows="2">{{ old('footer_message',$settings->footer_message) }}</textarea>
<label>Final surprise message</label><textarea name="final_message" rows="4">{{ old('final_message',$settings->final_message) }}</textarea>
<label>Final surprise photo</label><input type="file" name="final_photo" accept="image/*">@if($settings->final_photo)<img class="form-preview" src="{{ image_url($settings->final_photo) }}">@endif
<div class="check-stack"><label><input type="checkbox" name="enable_hearts" value="1" @checked($settings->enable_hearts)> Floating hearts</label><label><input type="checkbox" name="enable_confetti" value="1" @checked($settings->enable_confetti)> Final confetti</label><label><input type="checkbox" name="enable_music" value="1" @checked($settings->enable_music)> Music player</label></div>
<button class="btn btn-dark-gold mt-4">Save settings</button>
</form>
@endsection

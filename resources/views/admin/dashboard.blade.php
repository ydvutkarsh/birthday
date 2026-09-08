@extends('layouts.admin')
@section('content')
    <div class="studio-top">
        <div>
            <p class="eyebrow">Good to see you</p>
            <h1>Dashboard <em>overview</em></h1>
            <p class="muted">Everything that makes your story feel like yours.</p>
        </div>
        <div class="studio-date">{{ now()->format('D, d M Y') }}</div>
    </div>
    @if(session('success'))
    <div class="alert studio-alert">{{ session('success') }}</div>@endif
    <div class="row g-3 mb-4">
        @foreach([['memories', 'Memories', '◌'], ['photos', 'Photos', '▧'], ['messages', 'Messages', '♡'], ['songs', 'Songs', '♫']] as $card)
            <div class="col-6 col-xl-3">
                <div class="stat-card"><span class="stat-icon">{{ $card[2] }}</span>
                    <div class="stat-number">{{ $counts[$card[0]] }}</div>
                    <div class="stat-label">{{ $card[1] }}</div>
                </div>
        </div>@endforeach
    </div>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="studio-card">
                <div class="card-heading">
                    <div><span class="eyebrow">The latest chapters</span>
                        <h2>Recent memories</h2>
                    </div><a class="text-link" href="{{ route('admin.memories.index') }}">Manage all ↗</a>
                </div>@forelse($latestMemories as $memory)
                    <div class="list-row">
                        <div class="list-avatar">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                        <div><strong>{{ $memory->title }}</strong><small>{{ $memory->memory_date?->format('d M Y') ?? 'No date' }}
                                · {{ $memory->status }}</small></div><span class="row-arrow">↗</span>
                </div>@empty<p class="muted py-4">Your first chapter is waiting to be added.</p>@endforelse
            </div>
        </div>
        <div class="col-lg-5">
            <div class="studio-card">
                <div class="card-heading">
                    <div><span class="eyebrow">The visual diary</span>
                        <h2>Recent photos</h2>
                    </div><a class="text-link" href="{{ route('admin.gallery.index') }}">Manage ↗</a>
                </div>
                <div class="admin-photo-grid">@forelse($recentPhotos as $photo)<img
                src="{{ image_url($photo->image) }}" alt="{{ $photo->title }}">@empty<div
                        class="empty-photo">No photos yet</div>@endforelse</div>
            </div>
        </div>
    </div>
@endsection

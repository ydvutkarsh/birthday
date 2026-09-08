@php
    $profile = $profile ?: new \App\Models\BirthdayProfile(['name' => 'My Love', 'nickname' => 'My Love']);
    $settings = $settings ?: new \App\Models\SiteSetting(['site_title' => 'Happy Birthday', 'enable_hearts' => true, 'enable_confetti' => true, 'enable_music' => true]);
    $memories = $memories ?: collect();
    $gallery = $gallery ?: collect();
    $messages = $messages ?: collect();
    $songs = $songs ?: collect();
    $firstSong = $songs->first();
    $coverImage = $profile->cover_image ? image_url($profile->cover_image) : asset('storage/birthday/memories/lakeside-demo.png');
    $heroName = $profile->nickname ?: $profile->name ?: 'My Love';
    $heroDate = $profile->birthday_date?->format('F d, Y') ?? 'A day worth celebrating';
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#ece2d4">
    <title>{{ $settings->site_title ?? 'Happy Birthday' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@400;500;600&family=Italianno&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/birthday.css') }}">
    <link rel="stylesheet" href="{{ asset('css/floral.css') }}">
</head>
<body class="surprise-page floral-page" style="--accent: {{ $settings->primary_color ?? '#8a6940' }}; --gold: {{ $settings->secondary_color ?? '#8a6940' }}; --cover-image: url('{{ $coverImage }}')">
    <div class="opening-screen" data-opening>
        <div class="opening-word">for <em>you</em></div>
        <div class="opening-line"></div>
        <p>an entire universe, made from our little moments</p>
    </div>

    @if($settings->enable_hearts)
        <div class="petal-field" aria-hidden="true"><span>✦</span><span>✿</span><span>✦</span><span>❋</span><span>✦</span></div>
    @endif

    <div class="site-frame" id="top">
        <nav class="floral-nav" aria-label="Main navigation">
            <a class="floral-logo" href="#top">O<span>♡</span>A</a>
            <div class="floral-nav-links"><a class="active" href="#top">Home</a><a href="#timeline">Memories</a><a href="#gallery">Gallery</a><a href="#letter">Letter</a></div>
            <button class="floral-nav-toggle" type="button" data-nav-toggle aria-label="Open navigation" aria-expanded="false"><span></span><span></span><span></span></button>
            <a class="floral-nav-home" href="#top" aria-label="Back to home">⌂</a>
        </nav>

        <main>
            <section class="floral-hero">
                <img class="hero-floral-frame" src="{{ asset('images/floral-hero-frame-v2.png') }}" alt="" aria-hidden="true">
                <div class="hero-glow"></div>
                <div class="floral-copy">
                    <span class="floral-date">{{ $heroDate }}</span>
                    <h1>Happy Birthday<br><strong>{{ strtoupper($heroName) }}</strong></h1>
                    <div class="floral-divider"><span></span><b>✦</b><span></span></div>
                    <p class="hero-subtitle">Today is all about you.</p>
                    <button class="floral-primary" type="button" data-start-story>Open our story <span>→</span></button>
                </div>
                <div class="hero-memory-portrait">
                    <div class="portrait-image"><img src="{{ $coverImage }}" alt="A memory of us"></div>
                    <div class="portrait-caption">a little world<br><em>made for you</em></div>
                </div>
                <div class="floral-note-card note-left"><span>our memories</span><b>✦</b><p>Little moments,<br>big memories,<br>endless love.</p></div>
                <div class="floral-note-card note-right"><span>My Love,</span><p>Every moment with you<br>is my favorite.</p><b>with all my love</b></div>
                <div class="hero-polaroids">
                    <figure><img src="{{ asset('storage/birthday/memories/lakeside-demo.png') }}" alt="A day by the lake"><figcaption>you &amp; me</figcaption></figure>
                    <figure><img src="{{ asset('storage/birthday/memories/cafe-demo.png') }}" alt="Our cafe ritual"><figcaption>beautiful moments</figcaption></figure>
                    <figure><img src="{{ asset('storage/birthday/memories/rain-demo.png') }}" alt="Dancing in the rain"><figcaption>always us</figcaption></figure>
                </div>
                <a class="floral-scroll" href="#intro">scroll to begin <span>↓</span></a>
            </section>

            <section id="intro" class="floral-section floral-intro">
                <div class="floral-section-label">01 · today</div>
                <div class="floral-intro-grid"><div><p class="floral-eyebrow">A note before we begin</p><h2>Today is not just<br><em>a birthday.</em></h2></div><div><p class="floral-lead">{{ $profile->intro_message ?: "Today isn't just your birthday... it's the day my favourite person came into this world." }}</p><div class="floral-signature">with all my love <span>♡</span></div></div></div>
            </section>

            <section id="timeline" class="floral-section floral-story">
                <div class="floral-heading"><p class="floral-eyebrow">02 · the chapters</p><h2>Our story, <em>so far.</em></h2><p>Every ordinary moment became something I wanted to remember.</p></div>
                <div class="floral-timeline">
                    @forelse($memories as $memory)
                        <article class="floral-timeline-item reveal"><div class="floral-timeline-dot">✦</div><div class="floral-timeline-card"><div class="floral-timeline-image">@if($memory->cover_image)<img loading="lazy" src="{{ image_url($memory->cover_image) }}" alt="{{ $memory->title }}">@else<div>✦</div>@endif</div><div><span class="floral-date">{{ $memory->memory_date?->format('d M Y') ?? 'a beautiful day' }}</span><h3>{{ $memory->title }}</h3><p>{{ $memory->short_description }}</p><button class="floral-text-button" type="button" data-bs-toggle="modal" data-bs-target="#memory-{{ $memory->id }}">Read this chapter ↗</button></div></div></article>
                        <div class="modal fade dark-modal" id="memory-{{ $memory->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close">×</button>@if($memory->cover_image)<img src="{{ image_url($memory->cover_image) }}" alt="{{ $memory->title }}">@endif<div class="modal-body"><span class="memory-date">{{ $memory->memory_date?->format('d M Y') }}</span><h3>{{ $memory->title }}</h3><p>{!! nl2br(e($memory->full_description ?: $memory->short_description)) !!}</p></div></div></div></div>
                    @empty
                        <div class="floral-empty">✦<p>Your story is waiting for its first chapter.</p></div>
                    @endforelse
                </div>
            </section>

            <section class="floral-section floral-featured"><div class="floral-heading"><p class="floral-eyebrow">03 · the highlights</p><h2>Some days feel<br><em>like keepsakes.</em></h2></div><div class="floral-featured-grid">@foreach($memories->where('is_featured', true)->take(3) as $memory)<article><div class="floral-featured-image">@if($memory->cover_image)<img loading="lazy" src="{{ image_url($memory->cover_image) }}" alt="{{ $memory->title }}">@endif</div><span>{{ $memory->memory_date?->format('M Y') }}</span><h3>{{ $memory->title }}</h3></article>@endforeach</div></section>

            <section id="gallery" class="floral-section floral-gallery"><div class="floral-heading centered"><p class="floral-eyebrow">04 · the visual diary</p><h2>Little frames of <em>us.</em></h2><p>Proof that the best parts of life are usually the unplanned ones.</p></div><div class="floral-gallery-grid">@forelse($gallery as $photo)<button class="floral-gallery-tile" type="button" data-lightbox data-src="{{ image_url($photo->image) }}" data-caption="{{ $photo->caption }}" data-date="{{ $photo->memory_date?->format('d M Y') }}"><img loading="lazy" src="{{ image_url($photo->image) }}" alt="{{ $photo->title ?: 'A memory of us' }}"><span>View frame ↗</span></button>@empty<div class="floral-empty">✦<p>Your gallery is waiting for its first frame.</p></div>@endforelse</div></section>

            <section class="floral-section floral-messages"><div class="floral-heading centered"><p class="floral-eyebrow">05 · the little things</p><h2>Things I love<br><em>about you.</em></h2><p>Tap a card. I could write a thousand more.</p></div><div class="floral-message-grid">@forelse($messages as $message)<button class="floral-message-card message-card" type="button"><span class="floral-message-front"><b>{{ $message->icon ?: '✦' }}</b><strong>{{ $message->title }}</strong><small>tap to reveal</small></span><span class="floral-message-back"><b>{{ $message->icon ?: '✦' }}</b><span>{{ $message->message }}</span></span></button>@empty<p class="floral-empty">The sweetest details are still being written.</p>@endforelse</div></section>

            <section id="music" class="floral-section floral-music"><div><p class="floral-eyebrow">06 · our soundtrack</p><h2>Some songs<br><em>sound like you.</em></h2><p class="floral-muted-copy">A little soundtrack for all the moments still waiting for us.</p></div><div class="floral-music-art"><div class="floral-flower-disc">✿</div><button class="floral-outline" type="button" data-start-story>Play our song <span>♡</span></button></div></section>

            <section id="letter" class="floral-section floral-letter"><div class="floral-heading centered"><p class="floral-eyebrow">07 · from me to you</p><h2>A little something<br><em>I wanted to tell you.</em></h2></div><div class="floral-envelope-wrap"><div class="floral-envelope envelope" data-envelope><div class="floral-envelope-flap"></div><div class="floral-envelope-paper"><span>{{ $letter?->title ?? 'For you, always...' }}</span><button type="button" data-open-letter>Open my letter ♡</button></div><div class="floral-envelope-front"></div></div><div class="floral-letter-reveal letter-reveal" data-letter-reveal><span class="floral-eyebrow">{{ $letter?->title }}</span><p>{!! nl2br(e($letter?->content ?? 'Every day with you is my favourite kind of day.')) !!}</p><div class="floral-signature">{{ $letter?->signature ?? 'Yours, always ♡' }}</div></div></div></section>

            <section class="floral-final"><div><p class="floral-eyebrow">08 · one last thing</p><h2>One last <em>thing...</em></h2><p>I saved the best part for the very end.</p><button class="floral-primary" type="button" data-final>Open final surprise <span>♡</span></button><div class="floral-final-message final-message" data-final-message><div class="confetti-canvas" data-confetti></div>@if($settings->final_photo)<img src="{{ image_url($settings->final_photo) }}" alt="One last memory">@endif<p>{{ $settings->final_message ?: 'You are, and will always be, my favourite person in the world.' }}</p><strong>Happy birthday, {{ $heroName }}. ♡</strong></div></div></section>
        </main>
        <footer class="floral-footer"><div class="floral-logo">O<span>♡</span>A</div><p>{{ $settings->footer_message ?: 'Made with every little piece of my heart.' }}</p><small>just us two · {{ date('Y') }}</small></footer>
    </div>

    @if($firstSong && $settings->enable_music)<div class="floral-player music-player" data-player data-video="{{ $firstSong->youtube_video_id }}" data-playlist="{{ $firstSong->youtube_playlist_id }}"><div class="player-meta"><span class="floral-player-thumb">♫</span><span><small>now playing</small><strong data-song-title>{{ $firstSong->title }}</strong><span class="player-time"><span data-current-time>0:00</span><span data-total-time>0:00</span></span></span></div><div class="player-controls"><button type="button" data-prev aria-label="Previous">◀</button><button class="play-button" type="button" data-play aria-label="Play">▶</button><button type="button" data-next aria-label="Next">▶</button></div><div class="player-progress"><span></span><input class="player-seek" type="range" min="0" max="0" step="0.1" value="0" data-seek aria-label="Seek through song"></div><div class="youtube-player" data-youtube aria-hidden="true"></div></div>@endif
    <div class="lightbox" data-lightbox-modal><button type="button" data-lightbox-close aria-label="Close">×</button><img data-lightbox-image alt=""><div><span data-lightbox-date></span><p data-lightbox-caption></p></div></div>
    <script>window.storySongs = @json($songs->map(fn($song) => ['title' => $song->title, 'video' => $song->youtube_video_id, 'playlist' => $song->youtube_playlist_id])->values());</script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/birthday.js') }}"></script>
</body>
</html>

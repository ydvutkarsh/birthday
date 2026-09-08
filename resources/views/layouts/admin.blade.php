<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $title ?? 'Studio · Our Story' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/birthday.css') }}">
</head>

<body class="studio-body">
    <div class="studio-shell">
        <aside class="studio-sidebar">
            <div class="studio-logo">Om<span>♡</span>ja <small>STUDIO</small></div>
            <div class="sidebar-label">Curate the feeling</div>
            <nav class="studio-nav">
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}"><span>⌂</span> Dashboard</a>
                <a class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
                    href="{{ route('admin.profile.edit') }}"><span>✦</span> Birthday Profile</a>
                <a class="{{ request()->routeIs('admin.memories.*') ? 'active' : '' }}"
                    href="{{ route('admin.memories.index') }}"><span>◌</span> Memories</a>
                <a class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}"
                    href="{{ route('admin.gallery.index') }}"><span>▧</span> Gallery</a>
                <a class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}"
                    href="{{ route('admin.messages.index') }}"><span>♡</span> Messages</a>
                <a class="{{ request()->routeIs('admin.music.*') ? 'active' : '' }}"
                    href="{{ route('admin.music.index') }}"><span>♫</span> Music Playlist</a>
                <a class="{{ request()->routeIs('admin.letter.*') ? 'active' : '' }}"
                    href="{{ route('admin.letter.edit') }}"><span>✉</span> Love Letter</a>
                <a class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                    href="{{ route('admin.settings.edit') }}"><span>⚙</span> Site Settings</a>
            </nav>
            <div class="sidebar-bottom"><a href="{{ route('surprise') }}" target="_blank">View surprise ↗</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Log out</button></form>
            </div>
        </aside>
        <main class="studio-main">
            <div class="studio-mobile-head"><button class="sidebar-toggle" type="button"
                    data-sidebar-toggle>☰</button><span>OUR STORY · STUDIO</span></div>@yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/birthday.js') }}"></script>
</body>

</html>

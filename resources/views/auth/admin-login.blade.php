@extends('layouts.auth')
@section('content')
<main class="unlock-shell admin-unlock">
    <div class="unlock-panel">
        <div class="brand-mark">Omja<span>♡</span></div><p class="eyebrow">Private studio</p>
        <h1>Welcome<br><em>back, creator.</em></h1>
        <p class="unlock-copy">Shape the little universe before it is opened.</p>
        @if ($errors->any()) <div class="unlock-error">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('admin.login.attempt') }}" class="unlock-form">
            @csrf
            <label for="identity">Username or email</label><input id="identity" type="text" name="identity" value="{{ old('identity') }}" required autofocus>
            <label for="password">Password</label><input id="password" type="password" name="password" required>
            <button class="btn btn-gold w-100" type="submit">Enter studio <span>↗</span></button>
        </form>
        <a class="back-link" href="{{ route('login') }}">← Back to the surprise</a>
    </div>
</main>
@endsection

@extends('layouts.auth')
@section('content')
<main class="unlock-shell">
    <div class="unlock-orbit orbit-one"></div><div class="unlock-orbit orbit-two"></div>
    <div class="unlock-panel">
        <div class="brand-mark">Omja<span>♡</span></div>
        <p class="eyebrow">A private little universe</p>
        <h1>There’s something<br><em>waiting for you.</em></h1>
        <p class="unlock-copy">This is a little corner of the world made just for you. Enter your details to unlock it.</p>
        @if ($errors->any()) <div class="unlock-error">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('login.attempt') }}" class="unlock-form">
            @csrf
            <label for="identity">Username or email</label>
            <input id="identity" type="text" name="identity" value="{{ old('identity') }}" placeholder="your name or email" required autofocus>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="your secret password" required>
            <label class="check-line"><input type="checkbox" name="remember"> <span>Keep me close</span></label>
            <button class="btn btn-gold w-100" type="submit">Unlock My Surprise <span>♥</span></button>
        </form>
        <p class="unlock-note">For one very special person, with love.</p>
    </div>
    <div class="unlock-footer">© {{ date('Y') }} · just us two</div>
</main>
@endsection

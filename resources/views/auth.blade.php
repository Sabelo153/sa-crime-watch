@extends('layout')
@section('title', $register ? 'Join your community · SA Crime Watch' : 'Sign in · SA Crime Watch')
@section('content')
<div class="auth-layout">
    <aside class="auth-story">
        <p class="eyebrow"><span class="tiny-rule"></span> Community starts with you</p>
        <h1>Better information.<br><span>Stronger communities.</span></h1>
        <p class="lead">Be part of a more informed South Africa. Help your neighbours recognise stolen vehicles and share information responsibly.</p>
        <figure class="auth-photo"><img src="/images/community-evening.png" alt="AI illustration of a South African residential street at dusk" loading="lazy"><figcaption>AI-generated neighbourhood illustration</figcaption></figure>
        <div class="trust-feature"><span class="feature-icon"><x-icon name="shield"/></span><div><h3>Reviewed before publication</h3><p>Every public vehicle report goes through moderation.</p></div></div>
        <div class="trust-feature"><span class="feature-icon"><x-icon name="lock"/></span><div><h3>Private information stays private</h3><p>Your email stays off reports. Tips are visible only to moderators.</p></div></div>
    </aside>
    <section class="panel auth-form">
        <div class="form-heading"><span class="form-symbol"><x-icon name="{{ $register ? 'people' : 'lock' }}"/></span><p class="eyebrow">{{ $register ? 'Get involved' : 'Your community account' }}</p><h2>{{ $register ? 'Join your community' : 'Welcome back' }}</h2><p>{{ $register ? 'Create an account to submit reports and join a local community.' : 'Sign in to follow your reports and connect with your community.' }}</p></div>
        <form method="post" action="{{ $register ? '/register' : '/login' }}">@csrf
            @if($register)<label>Full name<input name="name" required maxlength="100" autocomplete="name" value="{{ old('name') }}" placeholder="Your name"></label>@endif
            <label>Email address<input type="email" name="email" required maxlength="254" autocomplete="email" value="{{ old('email') }}" placeholder="you@example.com"></label>
            <label>Password<input type="password" name="password" required @if($register) minlength="12" aria-describedby="password-help" @endif autocomplete="{{ $register ? 'new-password' : 'current-password' }}" placeholder="{{ $register ? 'Choose a strong password' : 'Enter your password' }}"></label>
            @if($register)<p class="field-help" id="password-help">Use at least 12 characters.</p><label>Confirm password<input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password"></label>@endif
            <button class="full-button">{{ $register ? 'Create account' : 'Sign in' }} <x-icon name="arrow"/></button>
        </form>
        <p class="form-switch">{{ $register ? 'Already part of the community?' : 'New to SA Crime Watch?' }} <a href="{{ $register ? '/login' : '/register' }}">{{ $register ? 'Sign in' : 'Create an account' }}</a></p>
        <p class="form-privacy"><x-icon name="lock"/> Your email is never displayed on public reports.</p>
    </section>
</div>
@endsection

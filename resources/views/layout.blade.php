<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#0b111b">
    <title>@yield('title', 'SA Crime Watch · Community safety')</title>
    <link rel="stylesheet" href="/watch.css?v={{ filemtime(public_path('watch.css')) }}">
    <script src="/watch.js?v={{ filemtime(public_path('watch.js')) }}" defer></script>
</head>
<body>
<a class="skip-link" href="#main-content">Skip to content</a>
<div class="utility-bar"><div><span>South Africa · Community safety</span><span class="utility-note"><x-icon name="shield"/> Reviewed before publication</span></div></div>
<header>
    <a class="brand" href="/" aria-label="SA Crime Watch home"><span class="brand-mark"><x-icon/></span><span class="brand-text">SA <span>Crime Watch</span><small>Awareness starts with community.</small></span></a>
    <nav aria-label="Main navigation">
        <a href="/" @if(request()->is('/')) aria-current="page" @endif>Find vehicles</a>
        <a href="/communities" @if(request()->is('communities')) aria-current="page" @endif>Communities</a>
        @auth
        <a href="/my-reports" @if(request()->is('my-reports')) aria-current="page" @endif>My reports</a>
        @if(auth()->user()->is_moderator)<a href="/moderation" @if(request()->is('moderation')) aria-current="page" @endif>Moderation</a><a href="/moderation/sponsors">Sponsors</a>@endif
        <form action="/logout" method="post">@csrf<button class="quiet">Sign out</button></form>
        @else
        <a href="/login" @if(request()->is('login')) aria-current="page" @endif>Sign in</a>
        <a class="nav-join" href="/register" @if(request()->is('register')) aria-current="page" @endif>Join community</a>
        @endauth
        <a class="button nav-report" href="/reports/create">Report a vehicle <x-icon name="arrow"/></a>
    </nav>
</header>
<main id="main-content">
    @if(session('status'))<p class="notice" role="status">{{ session('status') }}</p>@endif
    @if($errors->any())<div class="errors" role="alert"><strong>Please check your details.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @yield('content')
</main>
<footer>
    <div class="footer-top"><div><strong>SA Crime Watch</strong><p>Local awareness. Responsible reporting.</p></div><div class="footer-links"><a href="/">Find vehicles</a><a href="/communities">Communities</a><a href="/reports/create">Submit a report</a><a href="/advertise">Advertise with us</a></div></div>
    <div class="footer-bottom"><p>Independent community reports, subject to verification. Not a police or emergency service.</p><p>Do not confront anyone or attempt a recovery yourself. Contact local police for emergencies. Never post identity documents, home addresses, or accusations about named individuals.</p></div>
</footer>
</body>
</html>

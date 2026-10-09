@extends('layout')
@section('content')
<p class="eyebrow">Local connections</p><h1>Geographic communities</h1><p>Browse and join a local community. Only the community name appears on reports.</p><div class="grid">@foreach($communities as $c)<article class="panel"><h2>{{ $c->name }}</h2><p>{{ $c->municipality }} · {{ $c->province }}</p><a href="/?community={{ $c->id }}">View vehicle reports</a>@auth<form method="post" action="/communities/{{ $c->id }}/join">@csrf<button>Join community</button></form>@endauth</article>@endforeach</div>
@endsection

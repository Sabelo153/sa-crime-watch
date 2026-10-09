@extends('layout')
@section('content')
<h1>Private tips · {{ $report->registration }}</h1><p>Use only for reviewing this report. Do not copy personal details into public listings.</p>
@forelse($tips as $tip)<article class="panel"><p class="description">{{ $tip->message }}</p><p>Contact: {{ $tip->contact ?: 'Not supplied' }}</p><p>Expires {{ $tip->expires_at->format('d M Y') }}</p></article>@empty<p>No current tips.</p>@endforelse
@endsection

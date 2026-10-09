@extends('layout')
@section('content')
@if(isset($mine))
<section class="page-heading"><p class="eyebrow">Your community account</p><h1>Your vehicle reports</h1><p>Track the review status of your submissions.</p><a class="button" href="/reports/create">Submit a report <x-icon name="arrow"/></a></section>
@else
<section class="dashboard-hero">
    <img class="hero-scene" src="/images/community-evening.png" alt="" fetchpriority="high" width="1672" height="941">
    <span class="hero-image-label">AI-generated neighbourhood illustration</span>
    <div class="hero-copy"><p class="eyebrow"><span class="tiny-rule"></span> Community-led vehicle reporting</p><h1>Local information.<br><span>Safer communities.</span></h1><p class="lead">Find stolen-vehicle reports in your area. Recognise a vehicle. Share what you know, privately and responsibly.</p><div class="hero-actions"><a class="button" href="#vehicle-search"><x-icon name="search"/> Find a vehicle</a><a class="button secondary" href="/reports/create">Submit a report <x-icon name="arrow"/></a></div><p class="hero-note"><x-icon name="shield"/> Public reports are reviewed by moderators.</p></div>
    <aside class="overview-panel"><div class="overview-heading"><x-icon name="people"/><span>Platform overview</span></div><div class="overview-primary"><strong>{{ number_format($summary['missing']) }}</strong><span>Vehicles reported missing</span></div><div class="overview-secondary"><div><strong>{{ number_format($summary['recovered']) }}</strong><span>Marked recovered</span></div><div><strong>{{ number_format($summary['communities']) }}</strong><span>Available communities</span></div></div><p>Counts reflect this platform's reports, not national crime statistics.</p></aside>
</section>
<section class="search-panel panel" id="vehicle-search" aria-labelledby="search-heading">
    <div class="section-heading"><div><p class="eyebrow">Find information that matters</p><h2 id="search-heading">Search vehicle reports</h2></div><span class="subtle-label"><x-icon name="shield"/> Moderated listings</span></div>
    <form method="get">
        <div class="search-primary"><label>Registration<input name="registration" value="{{ request('registration') }}" maxlength="20" placeholder="e.g. AB12CDGP"></label><label>Community<select name="community"><option value="">All communities</option>@foreach($communities as $c)<option value="{{ $c->id }}" @selected(request('community') == $c->id)>{{ $c->name }} · {{ $c->province }}</option>@endforeach</select></label><button><x-icon name="search"/> Search reports</button></div>
        <details class="advanced-filters" @if(request()->filled('province') || request()->filled('make') || request()->filled('colour') || request()->filled('from') || request()->filled('to')) open @endif><summary>More filters <span>Province, vehicle details & dates</span></summary><div class="filters"><label>Province<select name="province"><option value="">All provinces</option>@foreach(['Eastern Cape','Free State','Gauteng','KwaZulu-Natal','Limpopo','Mpumalanga','North West','Northern Cape','Western Cape'] as $p)<option @selected(request('province') == $p)>{{ $p }}</option>@endforeach</select></label><label>Make<input name="make" maxlength="60" value="{{ request('make') }}" placeholder="e.g. Toyota"></label><label>Colour<input name="colour" maxlength="40" value="{{ request('colour') }}" placeholder="e.g. White"></label><label>Stolen from<input type="date" name="from" value="{{ request('from') }}"></label><label>Stolen until<input type="date" name="to" value="{{ request('to') }}"></label></div></details>
        <div class="search-foot"><span>Search by registration or browse your local community.</span><a href="/#vehicle-search">Clear filters</a></div>
        <p id="search-status" class="search-status" role="status" aria-live="polite"></p>
    </form>
</section>
<x-ad-slot placement="banner"/>
@endif
<div class="report-dashboard">
<section>
    <div class="section-heading report-heading"><div><p class="eyebrow">{{ isset($mine) ? 'Submission status' : 'Community reports' }}</p><h2>{{ isset($mine) ? 'Your submissions' : 'Vehicles still missing' }}</h2></div><span class="result-count">{{ $reports->total() }} {{ $reports->total() === 1 ? 'report' : 'reports' }}</span></div>
    @unless(isset($mine))<div class="view-toggle" hidden aria-label="Report layout"><button class="quiet" type="button" data-report-layout="grid" aria-pressed="true">Cards</button><button class="quiet" type="button" data-report-layout="list" aria-pressed="false">List</button></div>@endunless
    <div class="grid report-grid">
    @forelse($reports as $report)
        <article class="panel report-card">
            <a class="report-image" href="{{ route('reports.show',$report) }}" tabindex="-1" aria-hidden="true">@if($report->photos->first())<img src="/photos/{{ $report->photos->first()->id }}" alt="" loading="lazy">@else<div class="photo-placeholder"><x-icon name="car"/><span>No photo supplied</span></div>@endif</a>
            <div class="report-card-body"><div class="card-topline"><span class="badge status-{{ $report->status }}">{{ $report->status === 'approved' ? 'Reported missing' : ucfirst($report->status) }}</span><span class="report-reference">#{{ $report->id }}</span></div><h3><a href="{{ route('reports.show',$report) }}">{{ $report->make }} {{ $report->model }}</a></h3><p class="plate">{{ $report->registration }}</p><p class="card-location"><x-icon name="pin"/> {{ $report->community->name }} · {{ $report->community->province }}</p><div class="card-foot"><span>{{ $report->colour }} · {{ $report->stolen_on->format('d M Y') }}</span><a href="{{ route('reports.show',$report) }}">View <x-icon name="arrow"/></a></div></div>
        </article>
    @empty
        <div class="panel empty-state"><span class="empty-icon"><x-icon name="search"/></span><h3>No reports found</h3><p>{{ isset($mine) ? 'Your submitted reports will appear here with their review status.' : 'No moderated reports match this search. Try another community or adjust your filters.' }}</p><a class="button secondary" href="/reports/create">Submit a vehicle report <x-icon name="arrow"/></a></div>
    @endforelse
    </div>
    <div class="pagination">{{ $reports->links('pagination::simple-default') }}</div>
</section>
@unless(isset($mine))
<aside class="dashboard-sidebar">
    <x-ad-slot placement="sidebar"/>
    <section class="panel community-shortcuts"><p class="eyebrow">Explore locally</p><h3>Find your community</h3>@foreach($communities->take(4) as $c)<a href="/?community={{ $c->id }}#vehicle-search"><span class="shortcut-icon"><x-icon name="pin"/></span><span><strong>{{ $c->name }}</strong><small>{{ $c->province }}</small></span><x-icon name="arrow"/></a>@endforeach<a class="all-communities" href="/communities">All communities <x-icon name="arrow"/></a></section>
    <section class="panel safety-panel"><img class="safety-image" src="/images/vehicle-essentials.png" alt="AI illustration of car keys and a phone" loading="lazy"><span class="feature-icon"><x-icon name="shield"/></span><h3>Information helps.<br>Your safety comes first.</h3><p>If you recognise a reported vehicle, use its private tip form. Do not approach anyone or try to recover it yourself.</p><a href="/#vehicle-search">Find the vehicle report <x-icon name="arrow"/></a></section>
</aside>
@endunless
</div>
@unless(isset($mine))
<section class="how-it-works"><div class="section-heading"><div><p class="eyebrow">Simple steps. Responsible action.</p><h2>How the community helps</h2></div></div><div class="steps-grid"><article><span class="step-number">01</span><h3>Share the details</h3><p>Submit the vehicle information and photos, without personal documents or exact addresses.</p></article><article><span class="step-number">02</span><h3>Let moderators review</h3><p>Reports are checked before becoming visible to the community.</p></article><article><span class="step-number">03</span><h3>Pass on information privately</h3><p>Helpful tips go to moderators. Keep yourself safe and leave recovery to the authorities.</p></article></div></section>
@endunless
@endsection

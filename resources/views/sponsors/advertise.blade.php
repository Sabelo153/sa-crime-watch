@extends('layout')
@section('title','Advertise with us · SA Crime Watch')
@section('content')
<section class="page-heading"><p class="eyebrow">Local business. Local impact.</p><h1>Help keep community<br><span class="accent-text">safety free.</span></h1><p class="lead">Support SA Crime Watch with a clearly labelled sponsor placement. Residents can continue browsing reports, submitting vehicles and sharing tips without a subscription.</p></section>
<div class="sponsor-page">
<section><img class="sponsor-scene" src="/images/community-evening.png" alt="AI illustration of a South African neighbourhood at dusk"><p class="image-credit">AI-generated illustration · not an incident photograph</p><div class="placement-options"><article class="panel"><span class="eyebrow">01 · Banner</span><h3>A visible introduction</h3><p>A responsive placement below homepage search. Ideal for introducing your business.</p></article><article class="panel"><span class="eyebrow">02 · Sidebar</span><h3>Alongside local browsing</h3><p>A compact sponsor card beside the public report results. It moves below the results on phones.</p></article></div><p class="field-help">Sponsor content is reviewed before publication. Rates and availability are agreed directly; submitting an enquiry does not create a paid booking. Sponsorship is not an endorsement, and does not influence report moderation.</p></section>
<section class="panel"><p class="eyebrow">Start a conversation</p><h2>Become a local sponsor</h2><form method="post" action="/advertise">@csrf
<label>Business name<input name="business" required maxlength="100" value="{{ old('business') }}"></label>
<label>Business email<input name="email" type="email" required maxlength="254" value="{{ old('email') }}"></label>
<label>Preferred placement<select name="placement"><option value="either">Help me choose</option><option value="banner" @selected(old('placement')==='banner')>Homepage banner</option><option value="sidebar" @selected(old('placement')==='sidebar')>Report sidebar</option></select></label>
<label>Tell us about your business<textarea name="message" rows="5" required minlength="20" maxlength="2000"></textarea></label>
<label class="check"><input type="checkbox" name="consent" value="1" required>I agree that the site team may use these details to respond to my enquiry. Enquiries are kept for up to 90 days.</label>
<button class="full-button">Send sponsor enquiry <x-icon name="arrow"/></button><p class="field-help">Your enquiry is private. Nothing is published automatically.</p></form></section>
</div>
@endsection

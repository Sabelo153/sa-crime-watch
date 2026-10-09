@extends('layout')
@section('content')
<section class="panel narrow"><p class="eyebrow">Community reporting</p><h1>Report a stolen vehicle</h1><p>Share vehicle details only. Use an approximate community, not a precise address. Submissions are reviewed before publication.</p>
<form method="post" action="/reports" enctype="multipart/form-data">@csrf
<label>Community<select name="community_id" required><option value="">Choose a community</option>@foreach($communities as $c)<option value="{{ $c->id }}" @selected(old('community_id') == $c->id)>{{ $c->name }} · {{ $c->province }}</option>@endforeach</select></label>
@foreach(['registration'=>'Registration','make'=>'Make','model'=>'Model','colour'=>'Colour'] as $key=>$label)<label>{{ $label }}<input name="{{ $key }}" required maxlength="{{ $key === 'registration' ? 20 : ($key === 'colour' ? 40 : 60) }}" value="{{ old($key) }}"></label>@endforeach
<label>Date stolen<input type="date" name="stolen_on" max="{{ now()->toDateString() }}" required value="{{ old('stolen_on') }}"></label>
<label>Vehicle description<textarea name="description" required minlength="20" maxlength="2000" rows="5">{{ old('description') }}</textarea></label>
<label>Photos (optional, up to four)<input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp"></label><p>Maximum 5 MB and 12 megapixels each. Photos are re-encoded to remove location metadata. Remove faces and documents before upload; moderators review for personal information.</p>
<label class="check"><input type="checkbox" name="consent" value="1" required @checked(old('consent'))>I am authorised to submit this report and understand that approved vehicle details and photos will be public.</label><button>Submit for review</button></form></section>
@endsection

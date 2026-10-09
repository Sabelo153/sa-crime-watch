@extends('layout')
@section('content')
<h1>Moderation desk</h1><p>Verify the vehicle report and inspect photos for faces, personal documents and location metadata before approval. Record a reason for every decision.</p>
@foreach($reports as $report)<article class="panel"><span class="badge">{{ $report->status }}</span><h2><a href="/reports/{{ $report->id }}">{{ $report->registration }} · {{ $report->make }} {{ $report->model }}</a></h2><p>{{ $report->community->name }}</p><a href="/moderation/{{ $report->id }}/tips">Read private tips</a><form class="filters" method="post" action="/moderation/{{ $report->id }}">@csrf<label>Decision<select name="status"><option value="approved">Approve</option><option value="rejected">Reject / remove</option><option value="recovered">Mark recovered</option></select></label><label>Reason<input name="reason" required minlength="5" maxlength="500"></label><button>Record decision</button></form></article>@endforeach
{{ $reports->links('pagination::simple-default') }}
@endsection

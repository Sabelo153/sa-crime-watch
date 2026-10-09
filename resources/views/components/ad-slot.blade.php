<aside class="ad-slot ad-{{ $placement }}" aria-label="Advertising">
 <div class="ad-label">Advertisement <span>Helps keep SA Crime Watch free</span></div>
 @if($sponsor)
 <a class="sponsor-creative" href="{{ $sponsor->url }}" target="_blank" rel="sponsored noopener noreferrer">
 @if($sponsor->image_path)<img src="/sponsors/{{ $sponsor->id }}/image" alt="" loading="lazy">@endif
 <div><small>{{ $sponsor->business }}</small><h3>{{ $sponsor->headline }}</h3><p>{{ $sponsor->description }}</p><span class="sponsor-action">Visit sponsor <x-icon name="arrow"/></span></div>
 </a>
 @else
 <div class="ad-available"><span class="ad-space-icon"><x-icon name="people"/></span><div><h3>Your business. Their community.</h3><p>Support a free community service and introduce your business to local visitors.</p><a href="/advertise">Advertise here <x-icon name="arrow"/></a></div></div>
 @endif
</aside>

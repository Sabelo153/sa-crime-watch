<?php

namespace App\View\Components;

use App\Models\Sponsor;
use Illuminate\View\Component;

class AdSlot extends Component
{
    public $sponsor;

    public function __construct(public string $placement = 'banner')
    {
        $this->sponsor = Sponsor::published()->where('placement', $placement)->latest('id')->first();
    }

    public function render()
    {
        return view('components.ad-slot');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Models\SponsorInquiry;
use App\Services\PhotoSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    private function moderator(Request $r): void
    {
        abort_unless($r->user()?->is_moderator, 403);
    }

    public function inquire(Request $r)
    {
        $d = $r->validate(['business' => 'required|string|max:100', 'email' => 'required|email|max:254', 'message' => 'required|string|min:20|max:2000', 'placement' => 'required|in:banner,sidebar,either', 'consent' => 'accepted']);
        unset($d['consent']);
        SponsorInquiry::create($d + ['expires_at' => now()->addDays(90)]);

        return back()->with('status', 'Sponsorship enquiry received. The site team can review it in their sponsor dashboard.');
    }

    public function manage(Request $r)
    {
        $this->moderator($r);

        return view('sponsors.manage', ['sponsors' => Sponsor::latest()->paginate(20), 'inquiries' => SponsorInquiry::where('expires_at', '>', now())->latest()->limit(30)->get()]);
    }

    public function store(Request $r)
    {
        $this->moderator($r);
        $d = $r->validate(['business' => 'required|string|max:100', 'headline' => 'required|string|max:100', 'description' => 'required|string|max:240', 'url' => 'required|url:https|max:2048', 'placement' => 'required|in:banner,sidebar', 'starts_on' => 'required|date', 'ends_on' => 'required|date|after_or_equal:starts_on', 'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120', 'active' => 'nullable|boolean']);
        unset($d['image']);
        $path = null;
        try {
            if ($r->hasFile('image')) {
                $path = app(PhotoSanitizer::class)->store($r->file('image'));
            }
            Sponsor::create($d + ['created_by' => $r->user()->id, 'image_path' => $path]);
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk('local')->delete($path);
            } throw $e;
        }

        return back()->with('status', 'Sponsor placement saved.');
    }

    public function toggle(Request $r, Sponsor $sponsor)
    {
        $this->moderator($r);
        $sponsor->update(['active' => ! $sponsor->active]);

        return back()->with('status', 'Sponsor visibility updated.');
    }

    public function image(Sponsor $sponsor)
    {
        abort_unless(Sponsor::published()->whereKey($sponsor->id)->exists() && $sponsor->image_path, 404);

        return response()->file(Storage::disk('local')->path($sponsor->image_path), ['Content-Type' => 'image/jpeg', 'X-Content-Type-Options' => 'nosniff']);
    }
}

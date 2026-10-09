<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\ReportPhoto;
use App\Models\VehicleReport;
use App\Services\PhotoSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WatchController extends Controller
{
    private function moderator(Request $r): void
    {
        abort_unless($r->user()?->is_moderator, 403);
    }

    private function visible(Request $r, VehicleReport $report): void
    {
        abort_unless(in_array($report->status, ['approved', 'recovered']) || $r->user()?->id === $report->user_id || $r->user()?->is_moderator, 404);
    }

    public function index(Request $r)
    {
        $f = $r->validate(['community' => 'nullable|integer', 'province' => 'nullable|string|max:40', 'registration' => 'nullable|string|max:20', 'make' => 'nullable|string|max:60', 'colour' => 'nullable|string|max:40', 'from' => 'nullable|date', 'to' => $r->filled('from') ? 'nullable|date|after_or_equal:from' : 'nullable|date']);
        $q = VehicleReport::with('community', 'photos')->where('status', 'approved');
        foreach (['make', 'colour'] as $key) {
            if (! empty($f[$key])) {
                $q->where($key, $f[$key]);
            }
        }
        if (! empty($f['registration'])) {
            $q->where('registration', strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $f['registration'])));
        }
        if (! empty($f['community'])) {
            $q->where('community_id', $f['community']);
        }
        if (! empty($f['province'])) {
            $q->whereHas('community', fn ($q) => $q->where('province', $f['province']));
        }
        if (! empty($f['from'])) {
            $q->whereDate('stolen_on', '>=', $f['from']);
        }
        if (! empty($f['to'])) {
            $q->whereDate('stolen_on', '<=', $f['to']);
        }

        return view('reports.index', [
            'reports' => $q->latest()->paginate(12)->withQueryString(),
            'communities' => Community::orderBy('name')->get(),
            'summary' => [
                'missing' => VehicleReport::where('status', 'approved')->count(),
                'recovered' => VehicleReport::where('status', 'recovered')->count(),
                'communities' => Community::count(),
            ],
        ]);
    }

    public function create()
    {
        return view('reports.create', ['communities' => Community::orderBy('name')->get()]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['community_id' => 'required|exists:communities,id', 'registration' => 'required|string|max:20|regex:/^(?=.*[A-Za-z0-9])[A-Za-z0-9 -]+$/', 'make' => 'required|string|max:60', 'model' => 'required|string|max:60', 'colour' => 'required|string|max:40', 'stolen_on' => 'required|date|before_or_equal:today', 'description' => 'required|string|min:20|max:2000', 'photos' => 'nullable|array|max:4', 'photos.*' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120', 'consent' => 'accepted']);
        unset($d['photos'],$d['consent']);
        $d['registration'] = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $d['registration']));
        $d['user_id'] = $r->user()->id;
        $paths = [];
        try {
            $report = DB::transaction(function () use ($d, $r, &$paths) {
                $report = VehicleReport::create($d);
                foreach ($r->file('photos', []) as $file) {
                    $path = app(PhotoSanitizer::class)->store($file);
                    $paths[] = $path;
                    $report->photos()->create(['path' => $path, 'mime' => 'image/jpeg']);
                }

                return $report;
            });
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($paths);
            throw $e;
        }

        return redirect()->route('reports.show', $report)->with('status', 'Report submitted for moderation.');
    }

    public function show(Request $r, VehicleReport $report)
    {
        $this->visible($r, $report);

        return view('reports.show', ['report' => $report->load('community', 'photos')]);
    }

    public function photo(Request $r, ReportPhoto $photo)
    {
        $this->visible($r, $photo->report);

        return response()->file(Storage::disk('local')->path($photo->path), ['Content-Type' => $photo->mime, 'X-Content-Type-Options' => 'nosniff', 'Cache-Control' => 'private, no-store']);
    }

    public function tip(Request $r, VehicleReport $report)
    {
        abort_unless($report->status === 'approved', 404);
        $d = $r->validate(['message' => 'required|string|min:10|max:2000', 'contact' => 'nullable|string|max:254', 'consent' => 'accepted']);
        unset($d['consent']);
        DB::transaction(function () use ($report, $d) {
            $locked = VehicleReport::lockForUpdate()->findOrFail($report->id);
            abort_unless($locked->status === 'approved', 404);
            $locked->tips()->create($d + ['expires_at' => now()->addDays(90)]);
        });

        return back()->with('status', 'Tip received privately by moderators.');
    }

    public function communities()
    {
        return view('communities', ['communities' => Community::orderBy('province')->orderBy('name')->get()]);
    }

    public function join(Request $r, Community $community)
    {
        DB::table('community_user')->insertOrIgnore(['user_id' => $r->user()->id, 'community_id' => $community->id]);

        return back()->with('status', 'Community joined.');
    }

    public function mine(Request $r)
    {
        return view('reports.index', ['reports' => VehicleReport::with('community', 'photos')->where('user_id', $r->user()->id)->latest()->paginate(12), 'communities' => Community::all(), 'mine' => true]);
    }

    public function moderation(Request $r)
    {
        $this->moderator($r);

        return view('moderation', ['reports' => VehicleReport::with('community')->latest()->paginate(20)]);
    }

    public function moderate(Request $r, VehicleReport $report)
    {
        $this->moderator($r);
        $d = $r->validate(['status' => 'required|in:approved,rejected,recovered', 'reason' => 'required|string|min:5|max:500']);
        DB::transaction(function () use ($r, $report, $d) {
            $locked = VehicleReport::lockForUpdate()->findOrFail($report->id);
            DB::table('moderation_actions')->insert(['vehicle_report_id' => $locked->id, 'moderator_id' => $r->user()->id, 'from_status' => $locked->status, 'to_status' => $d['status'], 'reason' => $d['reason'], 'created_at' => now(), 'updated_at' => now()]);
            $locked->update(['status' => $d['status']]);
        });

        return back()->with('status', 'Moderation recorded.');
    }

    public function tips(Request $r, VehicleReport $report)
    {
        $this->moderator($r);

        return view('tips', ['report' => $report, 'tips' => $report->tips()->where('expires_at', '>', now())->latest()->get()]);
    }
}

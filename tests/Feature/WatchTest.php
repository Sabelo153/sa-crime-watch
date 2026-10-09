<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\Tip;
use App\Models\User;
use App\Models\VehicleReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WatchTest extends TestCase
{
    use RefreshDatabase;

    private function report(string $status = 'pending', array $extra = []): VehicleReport
    {
        $u = User::factory()->create();
        $c = Community::firstOrCreate(['province' => 'Gauteng', 'municipality' => 'Johannesburg', 'name' => 'Soweto']);

        return VehicleReport::create($extra + ['user_id' => $u->id, 'community_id' => $c->id, 'registration' => 'AB12CDGP', 'make' => 'Toyota', 'model' => 'Corolla', 'colour' => 'White', 'stolen_on' => '2026-01-01', 'description' => 'A white vehicle with a dent on the left door.', 'status' => $status]);
    }

    public function test_registration_login_and_logout(): void
    {
        $this->post('/register', ['name' => 'Resident', 'email' => 'resident@example.test', 'password' => 'strong-password-123', 'password_confirmation' => 'strong-password-123', 'is_moderator' => true])->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertFalse((bool) User::first()->is_moderator);
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->post('/login', ['email' => 'resident@example.test', 'password' => 'bad'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/login', ['email' => 'resident@example.test', 'password' => 'strong-password-123'])->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_pending_reports_and_photos_are_not_public(): void
    {
        Storage::fake('local');
        $r = $this->report();
        Storage::disk('local')->put('reports/private.jpg', 'private');
        $p = $r->photos()->create(['path' => 'reports/private.jpg', 'mime' => 'image/jpeg']);
        $this->get('/')->assertOk()->assertSee('No reports found')->assertDontSee('/reports/'.$r->id.'"', false);
        $this->get('/reports/'.$r->id)->assertNotFound();
        $this->get('/photos/'.$p->id)->assertNotFound();
        $this->actingAs(User::factory()->create())->get('/reports/'.$r->id)->assertNotFound();
        $this->actingAs(User::find($r->user_id))->get('/reports/'.$r->id)->assertOk();
    }

    public function test_search_filters_only_return_approved_matches(): void
    {
        $r = $this->report('approved');
        $other = $this->report('approved', ['registration' => 'OTHERGP', 'make' => 'Ford']);
        $this->report();
        $this->get('/?registration=AB-12-CD-GP&make=Toyota&colour=White&province=Gauteng&community='.$r->community_id.'&from=2025-12-01&to=2026-02-01')->assertOk()->assertSee($r->registration)->assertDontSee($other->registration);
        $this->get('/?from=invalid')->assertSessionHasErrors('from');
    }

    public function test_report_submission_uses_pending_status_and_sanitized_photos(): void
    {
        Storage::fake('local');
        $r = $this->report();
        $user = User::factory()->create();
        $this->actingAs($user)->post('/reports', ['community_id' => $r->community_id, 'registration' => 'xy 12 gp', 'make' => 'VW', 'model' => 'Polo', 'colour' => 'Blue', 'stolen_on' => '2026-01-01', 'description' => 'A blue vehicle with distinctive wheel covers.', 'consent' => '1', 'status' => 'approved', 'user_id' => $r->user_id, 'photos' => [UploadedFile::fake()->image('vehicle.png', 100, 100)]])->assertRedirect();
        $new = VehicleReport::where('registration', 'XY12GP')->firstOrFail();
        $this->assertSame('pending', $new->status);
        $this->assertSame($user->id, $new->user_id);
        $photo = $new->photos()->firstOrFail();
        $this->assertSame('image/jpeg', $photo->mime);
        Storage::disk('local')->assertExists($photo->path);
        $this->get('/photos/'.$photo->id)->assertOk()->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_invalid_photo_rolls_back_report(): void
    {
        $r = $this->report();
        $this->actingAs(User::find($r->user_id))->post('/reports', ['community_id' => $r->community_id, 'registration' => 'INVALIDGP', 'make' => 'VW', 'model' => 'Polo', 'colour' => 'Blue', 'stolen_on' => '2026-01-01', 'description' => 'A description long enough for validation.', 'consent' => 1, 'photos' => [UploadedFile::fake()->create('evil.svg', 1, 'image/svg+xml')]])->assertSessionHasErrors('photos.0');
        $this->assertDatabaseMissing('vehicle_reports', ['registration' => 'INVALIDGP']);
    }

    public function test_moderation_is_restricted_and_audited(): void
    {
        $r = $this->report();
        $u = User::factory()->create();
        $this->actingAs($u)->get('/moderation')->assertForbidden();
        $this->post('/moderation/'.$r->id, ['status' => 'approved', 'reason' => 'Verified report'])->assertForbidden();
        $u->is_moderator = true;
        $u->save();
        $this->get('/moderation')->assertOk();
        $this->post('/moderation/'.$r->id, ['status' => 'approved', 'reason' => 'Verified vehicle details'])->assertRedirect();
        $this->assertDatabaseHas('moderation_actions', ['vehicle_report_id' => $r->id, 'moderator_id' => $u->id, 'from_status' => 'pending', 'to_status' => 'approved']);
        $this->assertSame('approved', $r->fresh()->status);
    }

    public function test_tips_are_encrypted_and_only_visible_to_moderators(): void
    {
        $r = $this->report('approved');
        $this->post('/reports/'.$r->id.'/tips', ['message' => 'Seen near the main road.', 'contact' => 'private@example.test', 'consent' => 1])->assertRedirect();
        $raw = DB::table('tips')->first();
        $this->assertStringNotContainsString('main road', $raw->message);
        $this->assertStringNotContainsString('private@example.test', $raw->contact);
        $tip = Tip::first();
        $this->assertSame('Seen near the main road.', $tip->message);
        $this->get('/reports/'.$r->id)->assertDontSee('Seen near the main road.')->assertDontSee('private@example.test');
        $u = User::factory()->create();
        $this->actingAs($u)->get('/moderation/'.$r->id.'/tips')->assertForbidden();
        $u->is_moderator = true;
        $u->save();
        $this->get('/moderation/'.$r->id.'/tips')->assertOk()->assertSee('Seen near the main road.');
    }

    public function test_invalid_tips_are_not_flashed_into_the_session(): void
    {
        $r = $this->report('approved');
        $this->post('/reports/'.$r->id.'/tips', ['message' => 'Private sensitive message', 'contact' => 'secret@example.test'])->assertSessionHasErrors('consent')->assertSessionMissing('_old_input.message')->assertSessionMissing('_old_input.contact');
    }

    public function test_tip_retention_and_recovered_report_behaviour(): void
    {
        $r = $this->report('recovered');
        $this->get('/reports/'.$r->id)->assertOk();
        $this->post('/reports/'.$r->id.'/tips', ['message' => 'A sufficiently long tip', 'consent' => 1])->assertNotFound();
        $r->tips()->create(['message' => 'Expired private tip', 'expires_at' => now()->subDay()]);
        $r->tips()->create(['message' => 'Current private tip', 'expires_at' => now()->addDay()]);
        $this->artisan('watch:purge-tips')->assertSuccessful();
        $this->assertDatabaseCount('tips', 1);
    }

    public function test_community_membership_is_idempotent(): void
    {
        $r = $this->report();
        $this->actingAs(User::find($r->user_id));
        for ($i = 0; $i < 2; $i++) {
            $this->post('/communities/'.$r->community_id.'/join')->assertRedirect();
        }
        $this->assertDatabaseCount('community_user', 1);
        $this->get('/communities')->assertOk();
        $this->get('/my-reports')->assertOk()->assertSee($r->registration);
    }

    public function test_security_headers_and_guest_report_access(): void
    {
        $this->get('/')->assertHeader('X-Frame-Options', 'DENY')->assertHeader('Referrer-Policy', 'no-referrer');
        $this->get('/reports/create')->assertRedirect('/login');
        $this->post('/reports', [])->assertRedirect('/login');
    }
}

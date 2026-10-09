<?php
namespace Tests\Feature;
use App\Models\{User,Sponsor,SponsorInquiry,Community,VehicleReport};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
class SponsorTest extends TestCase {
 use RefreshDatabase;
 private function booking(array $extra=[]): Sponsor {
  return Sponsor::create($extra+['created_by'=>User::factory()->create()->id,'business'=>'Example garage','headline'=>'Local vehicle services','description'=>'Independent sponsor booking.','url'=>'https://example.test','placement'=>'banner','active'=>true,'starts_on'=>today()->subDay(),'ends_on'=>today()->addDay()]);
 }
 public function test_only_active_current_ads_appear_on_homepage(): void {
  $ad=$this->booking(); $this->get('/')->assertOk()->assertSee($ad->headline)->assertSee('rel="sponsored noopener noreferrer"',false);
  $ad->update(['active'=>false]); $this->get('/')->assertDontSee($ad->headline);
  $ad->update(['active'=>true,'starts_on'=>today()->addDays(2),'ends_on'=>today()->addDays(3)]); $this->get('/')->assertDontSee($ad->headline);
  $ad->update(['starts_on'=>today()->subDays(3),'ends_on'=>today()->subDay()]); $this->get('/')->assertDontSee($ad->headline);
 }
 public function test_enquiries_are_private_encrypted_and_expire(): void {
  $this->post('/advertise',['business'=>'A business','email'=>'contact@example.test','message'=>'Please send information about sponsor placement.','placement'=>'either','consent'=>1])->assertRedirect();
  $raw=DB::table('sponsor_inquiries')->first(); $this->assertStringNotContainsString('contact@example.test',$raw->email); $this->assertStringNotContainsString('Please send',$raw->message);
  $this->get('/advertise')->assertOk()->assertDontSee('contact@example.test');
  $inquiry=SponsorInquiry::first(); $this->assertSame('contact@example.test',$inquiry->email);
  $inquiry->update(['expires_at'=>now()->subDay()]); $this->artisan('watch:purge-sponsor-inquiries')->assertSuccessful(); $this->assertDatabaseCount('sponsor_inquiries',0);
 }
 public function test_sponsor_management_requires_a_moderator(): void {
  $this->get('/moderation/sponsors')->assertRedirect('/login');
  $u=User::factory()->create(); $this->actingAs($u)->get('/moderation/sponsors')->assertForbidden();
  $this->post('/moderation/sponsors',[])->assertForbidden();
  $ad=$this->booking(); $this->post('/moderation/sponsors/'.$ad->id.'/toggle')->assertForbidden();
  $u->is_moderator=true; $u->save(); $this->get('/moderation/sponsors')->assertOk();
  $this->post('/moderation/sponsors/'.$ad->id.'/toggle')->assertRedirect(); $this->assertFalse($ad->fresh()->active);
 }
 public function test_ad_creation_rejects_script_urls_and_accepts_reviewed_https_bookings(): void {
  $u=User::factory()->create(); $u->is_moderator=true; $u->save(); $this->actingAs($u);
  $data=['business'=>'Garage','headline'=>'Vehicle services','description'=>'Sponsor description','placement'=>'sidebar','starts_on'=>today()->toDateString(),'ends_on'=>today()->addDay()->toDateString(),'active'=>1];
  $this->post('/moderation/sponsors',$data+['url'=>'javascript:alert(1)'])->assertSessionHasErrors('url'); $this->assertDatabaseCount('sponsors',0);
  $this->post('/moderation/sponsors',$data+['url'=>'https://example.test'])->assertRedirect(); $this->assertDatabaseHas('sponsors',['business'=>'Garage','created_by'=>$u->id]);
 }
 public function test_no_ads_are_rendered_on_private_or_tip_pages(): void {
  $ad=$this->booking(); $u=User::factory()->create(); $c=Community::create(['province'=>'Gauteng','municipality'=>'Johannesburg','name'=>'Soweto']);
  $report=VehicleReport::create(['user_id'=>$u->id,'community_id'=>$c->id,'registration'=>'TIPTESTGP','make'=>'Toyota','model'=>'Corolla','colour'=>'White','stolen_on'=>today(),'description'=>'A sufficiently long vehicle description.','status'=>'approved']);
  $this->get('/reports/'.$report->id)->assertOk()->assertDontSee($ad->headline)->assertDontSee('class="ad-slot',false);
  $this->get('/register')->assertOk()->assertDontSee('class="ad-slot',false);
  $this->actingAs($u)->get('/my-reports')->assertOk()->assertDontSee('class="ad-slot',false);
 }
 public function test_inquiry_consent_and_private_message_flashing(): void {
  $this->post('/advertise',['business'=>'Company','email'=>'contact@example.test','message'=>'Private message with a sufficiently long enquiry.','placement'=>'either'])->assertSessionHasErrors('consent')->assertSessionMissing('_old_input.message');
  $this->assertDatabaseCount('sponsor_inquiries',0);
 }
}

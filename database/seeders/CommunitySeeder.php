<?php

namespace Database\Seeders;

use App\Models\Community;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([['Gauteng', 'City of Johannesburg', 'Soweto'], ['Gauteng', 'City of Tshwane', 'Mamelodi'], ['Western Cape', 'City of Cape Town', 'Mitchells Plain'], ['KwaZulu-Natal', 'eThekwini', 'Umlazi']] as [$province,$municipality,$name]) {
            Community::firstOrCreate(compact('province', 'municipality', 'name'));
        }
    }
}

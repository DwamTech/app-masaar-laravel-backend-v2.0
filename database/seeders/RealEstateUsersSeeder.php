<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\RealEstate;
use App\Models\RealEstateOfficesDetail;
use App\Models\RealEstateIndividualsDetail;

class RealEstateUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('real_estates')) {
            return;
        }

        $officeUsers = User::where('user_type', 'real_estate_office')->get();
        foreach ($officeUsers as $user) {
            $re = RealEstate::firstOrCreate([
                'user_id' => $user->id,
                'type' => 'office',
            ]);

            if (Schema::hasTable('real_estate_offices_details')) {
                RealEstateOfficesDetail::firstOrCreate([
                    'real_estate_id' => $re->id,
                ], [
                    'office_name' => 'Demo Office',
                    'office_address' => '123 Main St',
                    'office_phone' => '+201000000000',
                    'logo_image' => 'logo.png',
                    'owner_id_front_image' => 'owner_front.png',
                    'owner_id_back_image' => 'owner_back.png',
                    'office_image' => 'office.png',
                    'commercial_register_front_image' => 'cr_front.png',
                    'commercial_register_back_image' => 'cr_back.png',
                    'tax_enabled' => false,
                ]);
            }
        }

        $individualUsers = User::where('user_type', 'real_estate_individual')->get();
        foreach ($individualUsers as $user) {
            $re = RealEstate::firstOrCreate([
                'user_id' => $user->id,
                'type' => 'individual',
            ]);

            if (Schema::hasTable('real_estate_individuals_details')) {
                RealEstateIndividualsDetail::firstOrCreate([
                    'real_estate_id' => $re->id,
                ], [
                    'profile_image' => 'profile.png',
                    'agent_name' => 'Demo Agent',
                    'agent_id_front_image' => 'agent_front.png',
                    'agent_id_back_image' => 'agent_back.png',
                    'tax_card_front_image' => 'tax_front.png',
                    'tax_card_back_image' => 'tax_back.png',
                ]);
            }
        }
    }
}
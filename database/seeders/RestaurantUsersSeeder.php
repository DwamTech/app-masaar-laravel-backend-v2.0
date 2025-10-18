<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\RestaurantDetail;
use App\Models\MenuSection;
use App\Models\MenuItem;

class RestaurantUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('restaurant_details')) {
            return;
        }

        $users = User::where('user_type', 'restaurant')->get();
        foreach ($users as $user) {
            $detail = RestaurantDetail::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'profile_image' => 'restaurant.png',
                'restaurant_name' => 'Demo Restaurant',
                'logo_image' => 'logo.png',
                'owner_id_front_image' => 'owner_front.png',
                'owner_id_back_image' => 'owner_back.png',
                'license_front_image' => 'license_front.png',
                'license_back_image' => 'license_back.png',
                'commercial_register_front_image' => 'cr_front.png',
                'commercial_register_back_image' => 'cr_back.png',
                'vat_included' => false,
                'vat_image_front' => null,
                'vat_image_back' => null,
                'cuisine_types' => ['grill', 'pizza'],
                'branches' => ['Main Branch'],
                'delivery_available' => true,
                'delivery_cost_per_km' => 5.00,
                'table_reservation_available' => true,
                'max_people_per_reservation' => 6,
                'reservation_notes' => null,
                'deposit_required' => false,
                'deposit_amount' => null,
                'working_hours' => [
                    'mon' => ['09:00', '22:00'],
                    'tue' => ['09:00', '22:00'],
                ],
                'the_best' => false,
                'is_available_for_orders' => true,
            ]);

            if (Schema::hasTable('menu_sections')) {
                $section = MenuSection::firstOrCreate([
                    'restaurant_id' => $detail->id,
                    'title' => 'Main Dishes',
                ]);

                if (Schema::hasTable('menu_items')) {
                    MenuItem::firstOrCreate([
                        'restaurant_id' => $detail->id,
                        'section_id' => $section->id,
                        'title' => 'Grilled Chicken',
                    ], [
                        'description' => 'Served with rice',
                        'price' => 75.00,
                        'image' => 'grilled_chicken.png',
                    ]);

                    MenuItem::firstOrCreate([
                        'restaurant_id' => $detail->id,
                        'section_id' => $section->id,
                        'title' => 'Margherita Pizza',
                    ], [
                        'description' => 'Classic cheese pizza',
                        'price' => 90.00,
                        'image' => 'pizza.png',
                    ]);
                }
            }
        }
    }
}
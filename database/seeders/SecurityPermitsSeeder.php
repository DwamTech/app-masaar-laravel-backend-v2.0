<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;
use Carbon\Carbon;

use App\Models\User;
use App\Models\SecurityPermit;
use App\Models\Country;
use App\Models\Nationality;
use App\Models\SecurityPermitSetting;

class SecurityPermitsSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure table exists to avoid errors
        if (!Schema::hasTable('security_permits')) {
            $this->command?->warn("Skipping SecurityPermitsSeeder: table 'security_permits' not found.");
            return;
        }

        $faker = Faker::create('ar_SA');

        // Ensure we have some normal users to attach permits to
        $users = User::where('user_type', 'normal')->get();
        if ($users->isEmpty()) {
            for ($i = 0; $i < 10; $i++) {
                $users->push(User::create([
                    'name' => $faker->name(),
                    'email' => 'normal+'.uniqid()."@example.com",
                    'user_type' => 'normal',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'phone' => '01'.mt_rand(100000000, 999999999),
                ]));
            }
        }

        // Optional relations
        $countries = Country::all();
        $nationalities = Nationality::all();

        $individualFee = SecurityPermitSetting::getSetting('individual_fee', 150.00);

        $statuses = ['new', 'pending', 'approved', 'rejected', 'expired'];
        $paymentMethods = ['credit_card', 'digital_wallet'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

        $count = 35; // number of sample permits
        for ($i = 0; $i < $count; $i++) {
            $user = $users[$i % $users->count()];

            // Pick country and nationality if available
            $country = $countries->isNotEmpty() ? $countries[$i % $countries->count()] : null;
            $nationality = $nationalities->isNotEmpty() ? $nationalities[$i % $nationalities->count()] : null;

            $peopleCount = mt_rand(1, 6);
            $status = $statuses[array_rand($statuses)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

            $createdAt = Carbon::now()->subDays(mt_rand(0, 30))->subHours(mt_rand(0, 48));
            $travelDate = Carbon::now()->addDays(mt_rand(-20, 45))->toDateString();

            SecurityPermit::create([
                'user_id' => $user->id,
                'travel_date' => $travelDate,
                'nationality' => $nationality?->name_ar ?? $faker->randomElement(['مصري', 'سعودي', 'إماراتي', 'سوداني']),
                'nationality_id' => $nationality?->id,
                'people_count' => $peopleCount,
                'coming_from' => $country?->name_ar ?? $faker->randomElement(['القاهرة', 'الرياض', 'دبي', 'الخرطوم']),
                'country_id' => $country?->id,
                'passport_image' => 'https://picsum.photos/seed/passport-'.$i.'/300/200',
                'other_document_image' => (mt_rand(0, 100) < 40) ? 'https://picsum.photos/seed/other-'.$i.'/300/200' : null,
                'residence_images' => (mt_rand(0, 100) < 50) ? [
                    'https://picsum.photos/seed/res-'.($i*2+1).'/400/300',
                    'https://picsum.photos/seed/res-'.($i*2+2).'/400/300',
                ] : null,
                'payment_method' => $paymentMethod,
                'individual_fee' => $individualFee,
                'total_amount' => round($individualFee * $peopleCount, 2),
                'payment_status' => $paymentStatus,
                'payment_reference' => ($paymentStatus === 'paid') ? 'PAY-'.strtoupper(uniqid()) : null,
                'status' => $status,
                'notes' => (mt_rand(0, 100) < 30) ? $faker->sentence(8) : null,
                'admin_notes' => in_array($status, ['approved', 'rejected']) ? $faker->sentence(6) : null,
                'processed_at' => in_array($status, ['approved', 'rejected']) ? Carbon::now()->subDays(mt_rand(0, 10)) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addHours(mt_rand(0, 36)),
            ]);
        }

        $this->command?->info('SecurityPermitsSeeder: seeded sample security permits successfully.');
    }
}
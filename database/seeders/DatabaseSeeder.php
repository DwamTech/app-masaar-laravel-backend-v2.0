<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountriesSeeder::class,
            NationalitiesSeeder::class,
            SecurityPermitSettingsSeeder::class,
            ConversationSystemSeeder::class,
        ]);

        // إنشاء مستخدم أدمن
        $this->createAdminUser();
        
        // إنشاء مستخدمين عاديين
        $this->createNormalUsers();
        
        // إنشاء مطاعم
        $this->createRestaurantUsers();
        
        // إنشاء مكاتب عقارات
        $this->createRealEstateOffices();
        
        // إنشاء سماسرة عقارات أفراد
        $this->createRealEstateIndividuals();
        
        // إنشاء مكاتب تأجير سيارات
        $this->createCarRentalOffices();
        
        // إنشاء سائقين
        $this->createDrivers();
    }

    private function createAdminUser()
    {
        User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@masaar.com',
            'password' => Hash::make('password123'),
            'phone' => '+964770123456',
            'governorate' => 'بغداد',
            'city' => 'الكرادة',
            'user_type' => 'admin',
            'is_approved' => true,
            'account_active' => true,
            'is_email_verified' => true,
            'email_verified_at' => now(),
            'the_best' => true,
            'rating' => 5.00,
            'rating_count' => 100,
            'is_available' => true,
            'push_notifications_enabled' => true,
        ]);
    }

    private function createNormalUsers()
    {
        $normalUsers = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'phone' => '+964770111111',
                'governorate' => 'بغداد',
                'city' => 'الجادرية',
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima@example.com',
                'phone' => '+964770222222',
                'governorate' => 'البصرة',
                'city' => 'العشار',
            ],
            [
                'name' => 'محمد حسن',
                'email' => 'mohammed@example.com',
                'phone' => '+964770333333',
                'governorate' => 'أربيل',
                'city' => 'المركز',
            ]
        ];

        foreach ($normalUsers as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
                'phone' => $userData['phone'],
                'governorate' => $userData['governorate'],
                'city' => $userData['city'],
                'latitude' => rand(3000, 3700) / 100,
                'longitude' => rand(4400, 4800) / 100,
                'current_address' => $userData['governorate'] . ' - ' . $userData['city'],
                'location_updated_at' => now(),
                'location_sharing_enabled' => true,
                'user_type' => 'normal',
                'is_approved' => true,
                'account_active' => true,
                'is_email_verified' => true,
                'email_verified_at' => now(),
                'rating' => rand(350, 500) / 100,
                'rating_count' => rand(5, 50),
                'is_available' => true,
                'push_notifications_enabled' => true,
            ]);

            // إنشاء تفاصيل المستخدم العادي
            $user->normalUser()->create([]);
        }
    }

    private function createRestaurantUsers()
    {
        $restaurants = [
            [
                'name' => 'مطعم بغداد الأصيل',
                'email' => 'baghdad.restaurant@example.com',
                'phone' => '+964770444444',
                'governorate' => 'بغداد',
                'city' => 'الكرادة',
                'restaurant_name' => 'مطعم بغداد الأصيل',
                'cuisine_types' => 'عراقي، شرقي',
                'branches' => 'الكرادة، الجادرية',
                'delivery_available' => true,
                'delivery_cost_per_km' => 2000,
                'table_reservation_available' => true,
                'max_people_per_reservation' => 8,
                'working_hours' => '10:00 AM - 11:00 PM',
                'vat_included' => true,
            ],
            [
                'name' => 'مطعم دجلة',
                'email' => 'dijla.restaurant@example.com',
                'phone' => '+964770555555',
                'governorate' => 'بغداد',
                'city' => 'المنصور',
                'restaurant_name' => 'مطعم دجلة للمأكولات البحرية',
                'cuisine_types' => 'مأكولات بحرية، عراقي',
                'branches' => 'المنصور',
                'delivery_available' => true,
                'delivery_cost_per_km' => 2500,
                'table_reservation_available' => true,
                'max_people_per_reservation' => 12,
                'working_hours' => '12:00 PM - 12:00 AM',
                'vat_included' => false,
            ]
        ];

        foreach ($restaurants as $restaurantData) {
            $user = User::create([
                'name' => $restaurantData['name'],
                'email' => $restaurantData['email'],
                'password' => Hash::make('password123'),
                'phone' => $restaurantData['phone'],
                'governorate' => $restaurantData['governorate'],
                'city' => $restaurantData['city'],
                'latitude' => rand(3000, 3700) / 100,
                'longitude' => rand(4400, 4800) / 100,
                'current_address' => $restaurantData['governorate'] . ' - ' . $restaurantData['city'],
                'location_updated_at' => now(),
                'location_sharing_enabled' => true,
                'user_type' => 'restaurant',
                'is_approved' => true,
                'the_best' => rand(0, 1) == 1,
                'account_active' => true,
                'is_email_verified' => true,
                'email_verified_at' => now(),
                'rating' => rand(400, 500) / 100,
                'rating_count' => rand(20, 100),
                'is_available' => true,
                'push_notifications_enabled' => true,
            ]);

            // إنشاء تفاصيل المطعم
            $user->restaurantDetail()->create([
                'restaurant_name' => $restaurantData['restaurant_name'],
                'cuisine_types' => $restaurantData['cuisine_types'],
                'branches' => $restaurantData['branches'],
                'delivery_available' => $restaurantData['delivery_available'],
                'delivery_cost_per_km' => $restaurantData['delivery_cost_per_km'],
                'table_reservation_available' => $restaurantData['table_reservation_available'],
                'max_people_per_reservation' => $restaurantData['max_people_per_reservation'],
                'working_hours' => $restaurantData['working_hours'],
                'vat_included' => $restaurantData['vat_included'],
                'the_best' => $user->the_best,
            ]);
        }
    }

    private function createRealEstateOffices()
    {
        $offices = [
            [
                'name' => 'مكتب العراق العقاري',
                'email' => 'iraq.realestate@example.com',
                'phone' => '+964770666666',
                'governorate' => 'بغداد',
                'city' => 'الجادرية',
                'office_name' => 'مكتب العراق العقاري',
                'office_address' => 'شارع الجادرية الرئيسي',
                'office_phone' => '+964770666666',
                'tax_enabled' => true,
            ],
            [
                'name' => 'مكتب دجلة العقاري',
                'email' => 'dijla.realestate@example.com',
                'phone' => '+964770777777',
                'governorate' => 'البصرة',
                'city' => 'العشار',
                'office_name' => 'مكتب دجلة العقاري',
                'office_address' => 'شارع العشار التجاري',
                'office_phone' => '+964770777777',
                'tax_enabled' => false,
            ]
        ];

        foreach ($offices as $officeData) {
            $user = User::create([
                'name' => $officeData['name'],
                'email' => $officeData['email'],
                'password' => Hash::make('password123'),
                'phone' => $officeData['phone'],
                'governorate' => $officeData['governorate'],
                'city' => $officeData['city'],
                'latitude' => rand(3000, 3700) / 100,
                'longitude' => rand(4400, 4800) / 100,
                'current_address' => $officeData['governorate'] . ' - ' . $officeData['city'],
                'location_updated_at' => now(),
                'location_sharing_enabled' => true,
                'user_type' => 'real_estate_office',
                'is_approved' => true,
                'the_best' => rand(0, 1) == 1,
                'account_active' => true,
                'is_email_verified' => true,
                'email_verified_at' => now(),
                'rating' => rand(400, 500) / 100,
                'rating_count' => rand(15, 80),
                'is_available' => true,
                'push_notifications_enabled' => true,
            ]);

            // إنشاء تفاصيل العقارات
            $realEstate = $user->realEstate()->create(['type' => 'office']);
            
            $realEstate->officeDetail()->create([
                'office_name' => $officeData['office_name'],
                'office_address' => $officeData['office_address'],
                'office_phone' => $officeData['office_phone'],
                'tax_enabled' => $officeData['tax_enabled'],
            ]);
        }
    }

    private function createRealEstateIndividuals()
    {
        $individuals = [
            [
                'name' => 'علي حسين - سمسار عقاري',
                'email' => 'ali.broker@example.com',
                'phone' => '+964770888888',
                'governorate' => 'النجف',
                'city' => 'المركز',
                'agent_name' => 'علي حسين',
            ],
            [
                'name' => 'سارة أحمد - سمسار عقاري',
                'email' => 'sara.broker@example.com',
                'phone' => '+964770999999',
                'governorate' => 'كربلاء',
                'city' => 'المركز',
                'agent_name' => 'سارة أحمد',
            ]
        ];

        foreach ($individuals as $individualData) {
            $user = User::create([
                'name' => $individualData['name'],
                'email' => $individualData['email'],
                'password' => Hash::make('password123'),
                'phone' => $individualData['phone'],
                'governorate' => $individualData['governorate'],
                'city' => $individualData['city'],
                'latitude' => rand(3000, 3700) / 100,
                'longitude' => rand(4400, 4800) / 100,
                'current_address' => $individualData['governorate'] . ' - ' . $individualData['city'],
                'location_updated_at' => now(),
                'location_sharing_enabled' => true,
                'user_type' => 'real_estate_individual',
                'is_approved' => true,
                'the_best' => rand(0, 1) == 1,
                'account_active' => true,
                'is_email_verified' => true,
                'email_verified_at' => now(),
                'rating' => rand(380, 480) / 100,
                'rating_count' => rand(10, 60),
                'is_available' => true,
                'push_notifications_enabled' => true,
            ]);

            // إنشاء تفاصيل العقارات
            $realEstate = $user->realEstate()->create(['type' => 'individual']);
            
            $realEstate->individualDetail()->create([
                'agent_name' => $individualData['agent_name'],
            ]);
        }
    }

    private function createCarRentalOffices()
    {
        $carOffices = [
            [
                'name' => 'مكتب بغداد لتأجير السيارات',
                'email' => 'baghdad.carrental@example.com',
                'phone' => '+964771111111',
                'governorate' => 'بغداد',
                'city' => 'الكرادة',
                'office_name' => 'مكتب بغداد لتأجير السيارات',
                'payment_methods' => 'نقدي، بطاقة ائتمان، تحويل بنكي',
                'rental_options' => 'يومي، أسبوعي، شهري',
                'cost_per_km' => 500,
                'daily_driver_cost' => 50000,
                'max_km_per_day' => 200,
            ],
            [
                'name' => 'مكتب الفرات لتأجير السيارات',
                'email' => 'furat.carrental@example.com',
                'phone' => '+964771222222',
                'governorate' => 'البصرة',
                'city' => 'العشار',
                'office_name' => 'مكتب الفرات لتأجير السيارات',
                'payment_methods' => 'نقدي، بطاقة ائتمان',
                'rental_options' => 'يومي، أسبوعي',
                'cost_per_km' => 600,
                'daily_driver_cost' => 60000,
                'max_km_per_day' => 150,
            ]
        ];

        foreach ($carOffices as $officeData) {
            $user = User::create([
                'name' => $officeData['name'],
                'email' => $officeData['email'],
                'password' => Hash::make('password123'),
                'phone' => $officeData['phone'],
                'governorate' => $officeData['governorate'],
                'city' => $officeData['city'],
                'latitude' => rand(3000, 3700) / 100,
                'longitude' => rand(4400, 4800) / 100,
                'current_address' => $officeData['governorate'] . ' - ' . $officeData['city'],
                'location_updated_at' => now(),
                'location_sharing_enabled' => true,
                'user_type' => 'car_rental_office',
                'is_approved' => true,
                'the_best' => rand(0, 1) == 1,
                'account_active' => true,
                'is_email_verified' => true,
                'email_verified_at' => now(),
                'rating' => rand(400, 500) / 100,
                'rating_count' => rand(25, 90),
                'is_available' => true,
                'push_notifications_enabled' => true,
            ]);

            // إنشاء تفاصيل تأجير السيارات
            $carRental = $user->carRental()->create(['rental_type' => 'office']);
            
            $carRental->officeDetail()->create([
                'office_name' => $officeData['office_name'],
                'payment_methods' => $officeData['payment_methods'],
                'rental_options' => $officeData['rental_options'],
                'cost_per_km' => $officeData['cost_per_km'],
                'daily_driver_cost' => $officeData['daily_driver_cost'],
                'max_km_per_day' => $officeData['max_km_per_day'],
            ]);
        }
    }

    private function createDrivers()
    {
        $drivers = [
            [
                'name' => 'حسام محمد - سائق',
                'email' => 'hussam.driver@example.com',
                'phone' => '+964771333333',
                'governorate' => 'بغداد',
                'city' => 'الجادرية',
                'payment_methods' => 'نقدي، تحويل بنكي',
                'rental_options' => 'يومي، رحلات',
                'cost_per_km' => 400,
                'daily_driver_cost' => 40000,
                'max_km_per_day' => 300,
            ],
            [
                'name' => 'كريم علي - سائق',
                'email' => 'kareem.driver@example.com',
                'phone' => '+964771444444',
                'governorate' => 'أربيل',
                'city' => 'المركز',
                'payment_methods' => 'نقدي',
                'rental_options' => 'يومي، أسبوعي',
                'cost_per_km' => 450,
                'daily_driver_cost' => 45000,
                'max_km_per_day' => 250,
            ],
            [
                'name' => 'أمير حسن - سائق',
                'email' => 'ameer.driver@example.com',
                'phone' => '+964771555555',
                'governorate' => 'النجف',
                'city' => 'المركز',
                'payment_methods' => 'نقدي، بطاقة ائتمان',
                'rental_options' => 'يومي، رحلات، أسبوعي',
                'cost_per_km' => 350,
                'daily_driver_cost' => 35000,
                'max_km_per_day' => 400,
            ]
        ];

        foreach ($drivers as $driverData) {
            $user = User::create([
                'name' => $driverData['name'],
                'email' => $driverData['email'],
                'password' => Hash::make('password123'),
                'phone' => $driverData['phone'],
                'governorate' => $driverData['governorate'],
                'city' => $driverData['city'],
                'latitude' => rand(3000, 3700) / 100,
                'longitude' => rand(4400, 4800) / 100,
                'current_address' => $driverData['governorate'] . ' - ' . $driverData['city'],
                'location_updated_at' => now(),
                'location_sharing_enabled' => true,
                'user_type' => 'driver',
                'is_approved' => true,
                'the_best' => rand(0, 1) == 1,
                'account_active' => true,
                'is_email_verified' => true,
                'email_verified_at' => now(),
                'rating' => rand(420, 500) / 100,
                'rating_count' => rand(30, 120),
                'is_available' => true,
                'push_notifications_enabled' => true,
            ]);

            // إنشاء تفاصيل السائق
            $carRental = $user->carRental()->create(['rental_type' => 'driver']);
            
            $carRental->driverDetail()->create([
                'payment_methods' => $driverData['payment_methods'],
                'rental_options' => $driverData['rental_options'],
                'cost_per_km' => $driverData['cost_per_km'],
                'daily_driver_cost' => $driverData['daily_driver_cost'],
                'max_km_per_day' => $driverData['max_km_per_day'],
            ]);
        }
    }
}

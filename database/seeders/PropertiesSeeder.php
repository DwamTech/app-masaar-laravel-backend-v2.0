<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Property;
use App\Models\RealEstate;
use App\Models\User;

class PropertiesSeeder extends Seeder
{
    public function run(): void
    {
        // احصل على جميع حسابات العقارات الموجودة (مكتب/فرد)
        $realEstates = RealEstate::with('user')->get();

        if ($realEstates->isEmpty()) {
            $this->command?->warn('لا توجد حسابات عقارية لربط العقارات بها. تخطي PropertiesSeeder.');
            return;
        }

        $samples = [
            [
                'title' => 'شقة فاخرة بوسط المدينة',
                'property_type' => 'apartment',
                'listing_purpose' => 'sale',
                'property_price' => 2500000,
                'currency' => 'EGP',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'size_in_sqm' => 160,
                'finishing_level' => 'fully_finished',
                'floor_number' => 5,
                'overlooking' => 'شارع رئيسي',
                'year_built' => 2018,
                'property_status' => 'available',
                'readiness_status' => 'ready_to_move',
                'address' => 'وسط البلد، القاهرة',
                'description' => 'شقة واسعة وتشطيب فاخر بالقرب من الخدمات.'
            ],
            [
                'title' => 'فيلا مستقلة بحديقة خاصة',
                'property_type' => 'villa',
                'listing_purpose' => 'sale',
                'property_price' => 7200000,
                'currency' => 'EGP',
                'bedrooms' => 5,
                'bathrooms' => 4,
                'size_in_sqm' => 420,
                'finishing_level' => 'semi_finished',
                'floor_number' => 0,
                'overlooking' => 'حديقة مفتوحة',
                'year_built' => 2022,
                'property_status' => 'available',
                'readiness_status' => 'under_construction',
                'address' => 'التجمع الخامس، القاهرة الجديدة',
                'description' => 'فيلا مستقلة بحديقة واسعة وموقع متميز.'
            ],
            [
                'title' => 'شقة للإيجار مفروشة بالكامل',
                'property_type' => 'apartment',
                'listing_purpose' => 'rent',
                'property_price' => 18000,
                'currency' => 'EGP',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'size_in_sqm' => 110,
                'finishing_level' => 'fully_finished',
                'floor_number' => 8,
                'overlooking' => 'إطلالة مدينة',
                'year_built' => 2015,
                'property_status' => 'available',
                'readiness_status' => 'ready_to_move',
                'address' => 'مدينة نصر، القاهرة',
                'description' => 'شقة مفروشة بالقرب من المواصلات والخدمات.'
            ],
            [
                'title' => 'فيلا ببحيرة صناعية وإطلالة مميزة',
                'property_type' => 'villa',
                'listing_purpose' => 'rent',
                'property_price' => 45000,
                'currency' => 'EGP',
                'bedrooms' => 4,
                'bathrooms' => 4,
                'size_in_sqm' => 380,
                'finishing_level' => 'fully_finished',
                'floor_number' => 0,
                'overlooking' => 'بحيرة صناعية',
                'year_built' => 2020,
                'property_status' => 'available',
                'readiness_status' => 'ready_to_move',
                'address' => 'الشيخ زايد، 6 أكتوبر',
                'description' => 'فيلا راقية بإطلالة خلابة وحديقة خاصة.'
            ],
        ];

        $gallerySets = [
            ['properties/gallery/seed-1.jpg', 'properties/gallery/seed-2.jpg'],
            ['properties/gallery/seed-3.jpg', 'properties/gallery/seed-4.jpg'],
            ['properties/gallery/seed-5.jpg'],
            ['properties/gallery/seed-6.jpg', 'properties/gallery/seed-7.jpg', 'properties/gallery/seed-8.jpg'],
        ];

        $locations = [
            ['latitude' => 30.0444, 'longitude' => 31.2357, 'formatted_address' => 'Tahrir Square, Cairo, Egypt'],
            ['latitude' => 30.0074, 'longitude' => 31.4913, 'formatted_address' => 'New Cairo City, Cairo, Egypt'],
            ['latitude' => 30.0561, 'longitude' => 31.3300, 'formatted_address' => 'Nasr City, Cairo, Egypt'],
            ['latitude' => 30.0220, 'longitude' => 31.0165, 'formatted_address' => 'Sheikh Zayed City, Giza, Egypt'],
        ];

        $i = 0;
        foreach ($realEstates as $re) {
            $user = $re->user;
            if (!$user) continue;

            // أنشئ 1-2 عقارات لكل حساب عقاري
            $toCreate = ($i % 2 === 0) ? 2 : 1;
            for ($n = 0; $n < $toCreate; $n++) {
                $sample = $samples[($i + $n) % count($samples)];
                $gallery = $gallerySets[($i + $n) % count($gallerySets)];
                $loc = $locations[($i + $n) % count($locations)];

                $mainImage = 'properties/main/seed-' . (($i + $n) % 6 + 1) . '.jpg';

                $payload = array_merge($sample, [
                    'ownership_type' => 'freehold',
                    'down_payment' => $sample['listing_purpose'] === 'sale' ? 100000 : null,
                    'contact_info' => [
                        'phone' => $user->phone ?? '01000000000',
                        'email' => $user->email,
                        'whatsapp' => $user->phone ?? '01000000000',
                    ],
                    'location' => $loc,
                    'features' => ['مصعد', 'أمن', 'جراج'],
                    'amenities' => ['قريب من الخدمات', 'تكييف مركزي'],
                    'main_image' => $mainImage,
                    'gallery_image_urls' => $gallery,
                    'real_estate_id' => $re->id,
                    'user_id' => $user->id,
                    'view_count' => 0,
                    // توافقية مع أعمدة قديمة بعد إعادة التسمية
                    'old_type' => $sample['property_type'],
                    'old_price' => $sample['property_price'],
                ]);

                // احسب سعر المتر إن توفر
                if (!empty($payload['property_price']) && !empty($payload['size_in_sqm'])) {
                    $payload['price_per_square_meter'] = $payload['property_price'] / $payload['size_in_sqm'];
                }

                Property::create($payload);
            }

            $i++;
        }
    }
}
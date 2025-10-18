<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nationality;

class NationalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationalities = [
            ['name_ar' => 'مصري', 'name_en' => 'Egyptian', 'code' => 'EG', 'sort_order' => 1],
            ['name_ar' => 'سعودي', 'name_en' => 'Saudi', 'code' => 'SA', 'sort_order' => 2],
            ['name_ar' => 'إماراتي', 'name_en' => 'Emirati', 'code' => 'AE', 'sort_order' => 3],
            ['name_ar' => 'كويتي', 'name_en' => 'Kuwaiti', 'code' => 'KW', 'sort_order' => 4],
            ['name_ar' => 'قطري', 'name_en' => 'Qatari', 'code' => 'QA', 'sort_order' => 5],
            ['name_ar' => 'بحريني', 'name_en' => 'Bahraini', 'code' => 'BH', 'sort_order' => 6],
            ['name_ar' => 'عماني', 'name_en' => 'Omani', 'code' => 'OM', 'sort_order' => 7],
            ['name_ar' => 'أردني', 'name_en' => 'Jordanian', 'code' => 'JO', 'sort_order' => 8],
            ['name_ar' => 'لبناني', 'name_en' => 'Lebanese', 'code' => 'LB', 'sort_order' => 9],
            ['name_ar' => 'سوري', 'name_en' => 'Syrian', 'code' => 'SY', 'sort_order' => 10],
            ['name_ar' => 'عراقي', 'name_en' => 'Iraqi', 'code' => 'IQ', 'sort_order' => 11],
            ['name_ar' => 'فلسطيني', 'name_en' => 'Palestinian', 'code' => 'PS', 'sort_order' => 12],
            ['name_ar' => 'ليبي', 'name_en' => 'Libyan', 'code' => 'LY', 'sort_order' => 13],
            ['name_ar' => 'تونسي', 'name_en' => 'Tunisian', 'code' => 'TN', 'sort_order' => 14],
            ['name_ar' => 'جزائري', 'name_en' => 'Algerian', 'code' => 'DZ', 'sort_order' => 15],
            ['name_ar' => 'مغربي', 'name_en' => 'Moroccan', 'code' => 'MA', 'sort_order' => 16],
            ['name_ar' => 'سوداني', 'name_en' => 'Sudanese', 'code' => 'SD', 'sort_order' => 17],
            ['name_ar' => 'يمني', 'name_en' => 'Yemeni', 'code' => 'YE', 'sort_order' => 18],
            ['name_ar' => 'صومالي', 'name_en' => 'Somali', 'code' => 'SO', 'sort_order' => 19],
            ['name_ar' => 'جيبوتي', 'name_en' => 'Djiboutian', 'code' => 'DJ', 'sort_order' => 20],
            ['name_ar' => 'موريتاني', 'name_en' => 'Mauritanian', 'code' => 'MR', 'sort_order' => 21],
            ['name_ar' => 'تركي', 'name_en' => 'Turkish', 'code' => 'TR', 'sort_order' => 22],
            ['name_ar' => 'إيراني', 'name_en' => 'Iranian', 'code' => 'IR', 'sort_order' => 23],
            ['name_ar' => 'أفغاني', 'name_en' => 'Afghan', 'code' => 'AF', 'sort_order' => 24],
            ['name_ar' => 'باكستاني', 'name_en' => 'Pakistani', 'code' => 'PK', 'sort_order' => 25],
            ['name_ar' => 'هندي', 'name_en' => 'Indian', 'code' => 'IN', 'sort_order' => 26],
            ['name_ar' => 'بنجلاديشي', 'name_en' => 'Bangladeshi', 'code' => 'BD', 'sort_order' => 27],
            ['name_ar' => 'سريلانكي', 'name_en' => 'Sri Lankan', 'code' => 'LK', 'sort_order' => 28],
            ['name_ar' => 'فلبيني', 'name_en' => 'Filipino', 'code' => 'PH', 'sort_order' => 29],
            ['name_ar' => 'إندونيسي', 'name_en' => 'Indonesian', 'code' => 'ID', 'sort_order' => 30],
            ['name_ar' => 'ماليزي', 'name_en' => 'Malaysian', 'code' => 'MY', 'sort_order' => 31],
            ['name_ar' => 'تايلاندي', 'name_en' => 'Thai', 'code' => 'TH', 'sort_order' => 32],
            ['name_ar' => 'صيني', 'name_en' => 'Chinese', 'code' => 'CN', 'sort_order' => 33],
            ['name_ar' => 'ياباني', 'name_en' => 'Japanese', 'code' => 'JP', 'sort_order' => 34],
            ['name_ar' => 'كوري جنوبي', 'name_en' => 'South Korean', 'code' => 'KR', 'sort_order' => 35],
            ['name_ar' => 'روسي', 'name_en' => 'Russian', 'code' => 'RU', 'sort_order' => 36],
            ['name_ar' => 'أوكراني', 'name_en' => 'Ukrainian', 'code' => 'UA', 'sort_order' => 37],
            ['name_ar' => 'ألماني', 'name_en' => 'German', 'code' => 'DE', 'sort_order' => 38],
            ['name_ar' => 'فرنسي', 'name_en' => 'French', 'code' => 'FR', 'sort_order' => 39],
            ['name_ar' => 'إيطالي', 'name_en' => 'Italian', 'code' => 'IT', 'sort_order' => 40],
            ['name_ar' => 'إسباني', 'name_en' => 'Spanish', 'code' => 'ES', 'sort_order' => 41],
            ['name_ar' => 'بريطاني', 'name_en' => 'British', 'code' => 'GB', 'sort_order' => 42],
            ['name_ar' => 'أمريكي', 'name_en' => 'American', 'code' => 'US', 'sort_order' => 43],
            ['name_ar' => 'كندي', 'name_en' => 'Canadian', 'code' => 'CA', 'sort_order' => 44],
            ['name_ar' => 'أسترالي', 'name_en' => 'Australian', 'code' => 'AU', 'sort_order' => 45],
            ['name_ar' => 'برازيلي', 'name_en' => 'Brazilian', 'code' => 'BR', 'sort_order' => 46],
            ['name_ar' => 'أرجنتيني', 'name_en' => 'Argentine', 'code' => 'AR', 'sort_order' => 47],
            ['name_ar' => 'جنوب أفريقي', 'name_en' => 'South African', 'code' => 'ZA', 'sort_order' => 48],
            ['name_ar' => 'نيجيري', 'name_en' => 'Nigerian', 'code' => 'NG', 'sort_order' => 49],
            ['name_ar' => 'كيني', 'name_en' => 'Kenyan', 'code' => 'KE', 'sort_order' => 50],
        ];

        foreach ($nationalities as $nationality) {
            Nationality::updateOrCreate(
                ['code' => $nationality['code']],
                array_merge($nationality, ['is_active' => true])
            );
        }
    }
}
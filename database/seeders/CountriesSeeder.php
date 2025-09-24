<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name_ar' => 'مصر', 'name_en' => 'Egypt', 'code' => 'EG', 'sort_order' => 1],
            ['name_ar' => 'السعودية', 'name_en' => 'Saudi Arabia', 'code' => 'SA', 'sort_order' => 2],
            ['name_ar' => 'الإمارات', 'name_en' => 'United Arab Emirates', 'code' => 'AE', 'sort_order' => 3],
            ['name_ar' => 'الكويت', 'name_en' => 'Kuwait', 'code' => 'KW', 'sort_order' => 4],
            ['name_ar' => 'قطر', 'name_en' => 'Qatar', 'code' => 'QA', 'sort_order' => 5],
            ['name_ar' => 'البحرين', 'name_en' => 'Bahrain', 'code' => 'BH', 'sort_order' => 6],
            ['name_ar' => 'عمان', 'name_en' => 'Oman', 'code' => 'OM', 'sort_order' => 7],
            ['name_ar' => 'الأردن', 'name_en' => 'Jordan', 'code' => 'JO', 'sort_order' => 8],
            ['name_ar' => 'لبنان', 'name_en' => 'Lebanon', 'code' => 'LB', 'sort_order' => 9],
            ['name_ar' => 'سوريا', 'name_en' => 'Syria', 'code' => 'SY', 'sort_order' => 10],
            ['name_ar' => 'العراق', 'name_en' => 'Iraq', 'code' => 'IQ', 'sort_order' => 11],
            ['name_ar' => 'فلسطين', 'name_en' => 'Palestine', 'code' => 'PS', 'sort_order' => 12],
            ['name_ar' => 'ليبيا', 'name_en' => 'Libya', 'code' => 'LY', 'sort_order' => 13],
            ['name_ar' => 'تونس', 'name_en' => 'Tunisia', 'code' => 'TN', 'sort_order' => 14],
            ['name_ar' => 'الجزائر', 'name_en' => 'Algeria', 'code' => 'DZ', 'sort_order' => 15],
            ['name_ar' => 'المغرب', 'name_en' => 'Morocco', 'code' => 'MA', 'sort_order' => 16],
            ['name_ar' => 'السودان', 'name_en' => 'Sudan', 'code' => 'SD', 'sort_order' => 17],
            ['name_ar' => 'اليمن', 'name_en' => 'Yemen', 'code' => 'YE', 'sort_order' => 18],
            ['name_ar' => 'الصومال', 'name_en' => 'Somalia', 'code' => 'SO', 'sort_order' => 19],
            ['name_ar' => 'جيبوتي', 'name_en' => 'Djibouti', 'code' => 'DJ', 'sort_order' => 20],
            ['name_ar' => 'موريتانيا', 'name_en' => 'Mauritania', 'code' => 'MR', 'sort_order' => 21],
            ['name_ar' => 'تركيا', 'name_en' => 'Turkey', 'code' => 'TR', 'sort_order' => 22],
            ['name_ar' => 'إيران', 'name_en' => 'Iran', 'code' => 'IR', 'sort_order' => 23],
            ['name_ar' => 'أفغانستان', 'name_en' => 'Afghanistan', 'code' => 'AF', 'sort_order' => 24],
            ['name_ar' => 'باكستان', 'name_en' => 'Pakistan', 'code' => 'PK', 'sort_order' => 25],
            ['name_ar' => 'الهند', 'name_en' => 'India', 'code' => 'IN', 'sort_order' => 26],
            ['name_ar' => 'بنجلاديش', 'name_en' => 'Bangladesh', 'code' => 'BD', 'sort_order' => 27],
            ['name_ar' => 'سريلانكا', 'name_en' => 'Sri Lanka', 'code' => 'LK', 'sort_order' => 28],
            ['name_ar' => 'الفلبين', 'name_en' => 'Philippines', 'code' => 'PH', 'sort_order' => 29],
            ['name_ar' => 'إندونيسيا', 'name_en' => 'Indonesia', 'code' => 'ID', 'sort_order' => 30],
            ['name_ar' => 'ماليزيا', 'name_en' => 'Malaysia', 'code' => 'MY', 'sort_order' => 31],
            ['name_ar' => 'تايلاند', 'name_en' => 'Thailand', 'code' => 'TH', 'sort_order' => 32],
            ['name_ar' => 'الصين', 'name_en' => 'China', 'code' => 'CN', 'sort_order' => 33],
            ['name_ar' => 'اليابان', 'name_en' => 'Japan', 'code' => 'JP', 'sort_order' => 34],
            ['name_ar' => 'كوريا الجنوبية', 'name_en' => 'South Korea', 'code' => 'KR', 'sort_order' => 35],
            ['name_ar' => 'روسيا', 'name_en' => 'Russia', 'code' => 'RU', 'sort_order' => 36],
            ['name_ar' => 'أوكرانيا', 'name_en' => 'Ukraine', 'code' => 'UA', 'sort_order' => 37],
            ['name_ar' => 'ألمانيا', 'name_en' => 'Germany', 'code' => 'DE', 'sort_order' => 38],
            ['name_ar' => 'فرنسا', 'name_en' => 'France', 'code' => 'FR', 'sort_order' => 39],
            ['name_ar' => 'إيطاليا', 'name_en' => 'Italy', 'code' => 'IT', 'sort_order' => 40],
            ['name_ar' => 'إسبانيا', 'name_en' => 'Spain', 'code' => 'ES', 'sort_order' => 41],
            ['name_ar' => 'المملكة المتحدة', 'name_en' => 'United Kingdom', 'code' => 'GB', 'sort_order' => 42],
            ['name_ar' => 'الولايات المتحدة', 'name_en' => 'United States', 'code' => 'US', 'sort_order' => 43],
            ['name_ar' => 'كندا', 'name_en' => 'Canada', 'code' => 'CA', 'sort_order' => 44],
            ['name_ar' => 'أستراليا', 'name_en' => 'Australia', 'code' => 'AU', 'sort_order' => 45],
            ['name_ar' => 'البرازيل', 'name_en' => 'Brazil', 'code' => 'BR', 'sort_order' => 46],
            ['name_ar' => 'الأرجنتين', 'name_en' => 'Argentina', 'code' => 'AR', 'sort_order' => 47],
            ['name_ar' => 'جنوب أفريقيا', 'name_en' => 'South Africa', 'code' => 'ZA', 'sort_order' => 48],
            ['name_ar' => 'نيجيريا', 'name_en' => 'Nigeria', 'code' => 'NG', 'sort_order' => 49],
            ['name_ar' => 'كينيا', 'name_en' => 'Kenya', 'code' => 'KE', 'sort_order' => 50],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['code' => $country['code']],
                array_merge($country, ['is_active' => true])
            );
        }
    }
}
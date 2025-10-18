<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SecurityPermitSetting;

class SecurityPermitSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'individual_fee',
                'value' => '150.00',
                'type' => 'number',
                'description' => 'رسوم التصريح الأمني للفرد الواحد (بالجنيه المصري)',
                'group' => 'pricing',
                'is_editable' => true,
            ],
            [
                'key' => 'max_people_per_request',
                'value' => '20',
                'type' => 'number',
                'description' => 'الحد الأقصى لعدد الأفراد في الطلب الواحد',
                'group' => 'limits',
                'is_editable' => true,
            ],
            [
                'key' => 'processing_time_days',
                'value' => '7',
                'type' => 'number',
                'description' => 'مدة معالجة الطلب بالأيام',
                'group' => 'processing',
                'is_editable' => true,
            ],
            [
                'key' => 'auto_expire_days',
                'value' => '30',
                'type' => 'number',
                'description' => 'عدد الأيام لانتهاء صلاحية التصريح تلقائياً',
                'group' => 'processing',
                'is_editable' => true,
            ],
            [
                'key' => 'require_residence_images',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'إجبارية رفع صور الإقامة',
                'group' => 'requirements',
                'is_editable' => true,
            ],
            [
                'key' => 'allowed_payment_methods',
                'value' => '["credit_card", "digital_wallet"]',
                'type' => 'json',
                'description' => 'طرق الدفع المسموحة',
                'group' => 'payment',
                'is_editable' => true,
            ],
            [
                'key' => 'notification_emails',
                'value' => '["admin@msar.app"]',
                'type' => 'json',
                'description' => 'بريد إلكتروني للإشعارات الإدارية',
                'group' => 'notifications',
                'is_editable' => true,
            ],
            [
                'key' => 'system_active',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'تفعيل نظام التصاريح الأمنية',
                'group' => 'system',
                'is_editable' => true,
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'نظام التصاريح الأمنية متاح حالياً',
                'type' => 'string',
                'description' => 'رسالة الصيانة عند تعطيل النظام',
                'group' => 'system',
                'is_editable' => true,
            ],
        ];

        foreach ($settings as $setting) {
            SecurityPermitSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
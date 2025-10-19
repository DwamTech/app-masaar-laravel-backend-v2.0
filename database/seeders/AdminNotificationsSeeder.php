<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class AdminNotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // البحث عن المدير بالإيميل أولاً، ثم بالنوع
        $admin = User::where('email', 'admin@masar.app')->first();
        
        if (!$admin) {
            // إذا لم يوجد بالإيميل، ابحث بالنوع
            $admin = User::where('user_type', 'admin')->first();
        }
        
        if (!$admin) {
            $this->command->error('لا يوجد مستخدم إداري في النظام');
            return;
        }

        $this->command->info("إنشاء إشعارات للمدير: {$admin->name} (ID: {$admin->id})");

        // حذف الإشعارات السابقة للمدير
        Notification::where('user_id', $admin->id)->delete();

        $notifications = [
            // إشعارات طلبات التصاريح الأمنية
            [
                'type' => 'security_permit',
                'title' => 'طلب تصريح أمني جديد',
                'message' => 'تم تقديم طلب تصريح أمني جديد رقم #SP001 من المستخدم أحمد محمد',
                'is_read' => false,
                'link' => '/admin/security-permits/1',
                'data' => json_encode([
                    'permit_id' => 1,
                    'user_name' => 'أحمد محمد',
                    'status' => 'pending'
                ]),
                'created_at' => Carbon::now()->subMinutes(5)
            ],
            [
                'type' => 'security_permit',
                'title' => 'طلب تصريح أمني جديد',
                'message' => 'تم تقديم طلب تصريح أمني جديد رقم #SP002 من المستخدم فاطمة علي',
                'is_read' => false,
                'link' => '/admin/security-permits/2',
                'data' => json_encode([
                    'permit_id' => 2,
                    'user_name' => 'فاطمة علي',
                    'status' => 'pending'
                ]),
                'created_at' => Carbon::now()->subMinutes(15)
            ],

            // إشعارات طلبات العقارات
            [
                'type' => 'property_request',
                'title' => 'طلب عقار جديد',
                'message' => 'تم تقديم طلب عقار جديد من مكتب العقارات الذهبي',
                'is_read' => true,
                'link' => '/admin/properties/pending',
                'data' => json_encode([
                    'property_id' => 1,
                    'office_name' => 'مكتب العقارات الذهبي',
                    'property_type' => 'شقة'
                ]),
                'created_at' => Carbon::now()->subHours(1)
            ],

            // إشعارات طلبات المطاعم
            [
                'type' => 'restaurant_request',
                'title' => 'طلب انضمام مطعم جديد',
                'message' => 'تم تقديم طلب انضمام من مطعم البيت الشامي',
                'is_read' => false,
                'link' => '/admin/restaurants/pending',
                'data' => json_encode([
                    'restaurant_id' => 1,
                    'restaurant_name' => 'مطعم البيت الشامي',
                    'status' => 'pending_approval'
                ]),
                'created_at' => Carbon::now()->subHours(2)
            ],

            // إشعارات طلبات تأجير السيارات
            [
                'type' => 'car_rental_request',
                'title' => 'طلب انضمام مكتب تأجير سيارات',
                'message' => 'تم تقديم طلب انضمام من مكتب الرحلة لتأجير السيارات',
                'is_read' => true,
                'link' => '/admin/car-rentals/pending',
                'data' => json_encode([
                    'office_id' => 1,
                    'office_name' => 'مكتب الرحلة لتأجير السيارات',
                    'status' => 'pending_approval'
                ]),
                'created_at' => Carbon::now()->subHours(3)
            ],

            // إشعارات طلبات السائقين
            [
                'type' => 'driver_request',
                'title' => 'طلب انضمام سائق جديد',
                'message' => 'تم تقديم طلب انضمام من السائق محمد حسن',
                'is_read' => false,
                'link' => '/admin/drivers/pending',
                'data' => json_encode([
                    'driver_id' => 1,
                    'driver_name' => 'محمد حسن',
                    'governorate' => 'القاهرة'
                ]),
                'created_at' => Carbon::now()->subHours(4)
            ],

            // إشعارات النظام
            [
                'type' => 'system',
                'title' => 'تحديث النظام',
                'message' => 'تم تحديث النظام بنجاح إلى الإصدار 2.1.0',
                'is_read' => true,
                'link' => '/admin/system/updates',
                'data' => json_encode([
                    'version' => '2.1.0',
                    'update_type' => 'minor'
                ]),
                'created_at' => Carbon::now()->subDays(1)
            ],

            // إشعارات الأمان
            [
                'type' => 'security',
                'title' => 'محاولة دخول مشبوهة',
                'message' => 'تم رصد محاولة دخول مشبوهة من عنوان IP: 192.168.1.100',
                'is_read' => false,
                'link' => '/admin/security/logs',
                'data' => json_encode([
                    'ip_address' => '192.168.1.100',
                    'attempts' => 5,
                    'blocked' => true
                ]),
                'created_at' => Carbon::now()->subHours(6)
            ],

            // إشعارات التقارير
            [
                'type' => 'report',
                'title' => 'تقرير شهري جاهز',
                'message' => 'تقرير شهر ديسمبر 2024 جاهز للمراجعة',
                'is_read' => true,
                'link' => '/admin/reports/monthly/2024-12',
                'data' => json_encode([
                    'report_type' => 'monthly',
                    'period' => '2024-12',
                    'total_users' => 1250,
                    'total_revenue' => 45000
                ]),
                'created_at' => Carbon::now()->subDays(2)
            ],

            // إشعارات المدفوعات
            [
                'type' => 'payment',
                'title' => 'دفعة جديدة مستلمة',
                'message' => 'تم استلام دفعة بقيمة 500 ريال من العميل أحمد محمد',
                'is_read' => false,
                'link' => '/admin/payments/recent',
                'data' => json_encode([
                    'amount' => 500,
                    'currency' => 'SAR',
                    'customer_name' => 'أحمد محمد',
                    'payment_method' => 'credit_card'
                ]),
                'created_at' => Carbon::now()->subMinutes(30)
            ],

            // إشعارات الشكاوى
            [
                'type' => 'complaint',
                'title' => 'شكوى جديدة',
                'message' => 'تم تقديم شكوى جديدة من العميل سارة أحمد حول جودة الخدمة',
                'is_read' => false,
                'link' => '/admin/complaints/new',
                'data' => json_encode([
                    'complaint_id' => 1,
                    'customer_name' => 'سارة أحمد',
                    'service_type' => 'restaurant',
                    'priority' => 'high'
                ]),
                'created_at' => Carbon::now()->subMinutes(45)
            ],

            // إشعارات الصيانة
            [
                'type' => 'maintenance',
                'title' => 'صيانة مجدولة',
                'message' => 'صيانة مجدولة للنظام غداً من الساعة 2:00 إلى 4:00 صباحاً',
                'is_read' => true,
                'link' => '/admin/maintenance/schedule',
                'data' => json_encode([
                    'maintenance_type' => 'database_optimization',
                    'scheduled_time' => '2024-12-25 02:00:00',
                    'duration' => '2 hours'
                ]),
                'created_at' => Carbon::now()->subDays(1)
            ],

            // إشعارات المراجعات
            [
                'type' => 'review',
                'title' => 'مراجعة جديدة',
                'message' => 'تم إضافة مراجعة جديدة بتقييم 5 نجوم لمطعم الأصالة',
                'is_read' => true,
                'link' => '/admin/reviews/recent',
                'data' => json_encode([
                    'rating' => 5,
                    'restaurant_name' => 'مطعم الأصالة',
                    'customer_name' => 'خالد محمد'
                ]),
                'created_at' => Carbon::now()->subHours(8)
            ]
        ];

        foreach ($notifications as $notification) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $notification['type'],
                'title' => $notification['title'],
                'message' => $notification['message'],
                'is_read' => $notification['is_read'],
                'link' => $notification['link'],
                'data' => $notification['data'],
                'created_at' => $notification['created_at'],
                'updated_at' => $notification['created_at']
            ]);
        }

        $this->command->info('تم إنشاء ' . count($notifications) . ' إشعار للمدير بنجاح');
    }
}
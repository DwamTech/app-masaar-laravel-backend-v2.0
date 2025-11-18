<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class DeviceTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('device_tokens')) {
            $this->command?->warn('جدول device_tokens غير موجود، سيتم تخطي DeviceTokensSeeder.');
            return;
        }

        // اجلب مجموعة من المستخدمين المتاحين لإنشاء توكنات لهم
        $users = User::whereIn('user_type', [
                'normal', 'real_estate_office', 'real_estate_individual', 'restaurant', 'driver', 'admin'
            ])
            ->limit(12)
            ->get();

        if ($users->isEmpty()) {
            $this->command?->warn('لا يوجد مستخدمون مناسبون لبذر التوكينات لهم.');
            return;
        }

        $platforms = ['android', 'ios', 'web'];

        foreach ($users as $i => $user) {
            // تأكد من أن المستخدم لديه تفعيل للإشعارات (إن كان الحقل موجود)
            try {
                if (Schema::hasColumn('users', 'push_notifications_enabled')) {
                    $user->push_notifications_enabled = true;
                    $user->save();
                }
            } catch (\Throwable $e) {
                // تجاهل أي أخطاء غير مؤثرة هنا
            }

            // أنشئ 1-2 توكنات لكل مستخدم لضمان الظهور في قائمة المؤهلين
            $tokensToCreate = ($i % 2 === 0) ? 2 : 1;
            for ($n = 0; $n < $tokensToCreate; $n++) {
                $token = 'sim-' . $user->id . '-' . Str::random(60);

                DB::table('device_tokens')->updateOrInsert(
                    ['token' => $token],
                    [
                        'user_id'     => $user->id,
                        'platform'    => $platforms[($i + $n) % count($platforms)],
                        'is_enabled'  => 1,
                        'last_seen_at'=> now(),
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }
        }

        $this->command?->info('تم إنشاء Device Tokens لعدد: ' . $users->count() . ' مستخدمين.');
    }
}
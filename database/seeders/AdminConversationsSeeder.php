<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

class AdminConversationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // احصل على بريد الأدمن من البيئة (مع افتراضي)
        $adminEmail = env('SUPPORT_ADMIN_EMAIL', 'admin@msar.app');

        // تأكد من وجود حساب الأدمن
        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'مدير الدعم',
                'user_type' => 'admin',
                'password' => Hash::make('password'),
                'is_approved' => true,
                'account_active' => true,
                'is_email_verified' => true,
            ]
        );

        // اجلب مجموعة من المستخدمين لبذر محادثات معهم
        $users = User::query()
            ->where('id', '!=', $admin->id)
            ->whereIn('user_type', ['normal', 'real_estate_office', 'real_estate_individual', 'restaurant', 'driver'])
            ->orderBy('id', 'asc')
            ->limit(12)
            ->get();

        if ($users->isEmpty()) {
            $this->command?->warn('لا يوجد مستخدمون مناسبون لبذر محادثات معهم.');
            return;
        }

        $createdConversations = 0;
        $createdMessages = 0;

        foreach ($users as $user) {
            // أنشئ/احصل على محادثة بين الأدمن والمستخدم
            $conversation = Conversation::createBetweenUsers(
                $admin->id,
                $user->id,
                'admin_user',
                'محادثة دعم مع ' . ($user->name ?: ('مستخدم #' . $user->id))
            );

            // عدّ المحادثات الجديدة فقط
            if ($conversation->wasRecentlyCreated) {
                $createdConversations++;
            }

            // أضف رسائل تجريبية إن كانت المحادثة فارغة
            if ($conversation->messages()->count() === 0) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $user->id,
                    'content' => 'مرحباً، أحتاج مساعدة للتأكد من أن الدردشة تعمل.',
                    'type' => 'text',
                ]);
                $createdMessages++;

                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $admin->id,
                    'content' => 'أهلاً ' . ($user->name ?: 'بك') . '، تم إعداد محادثة الدعم بنجاح.',
                    'type' => 'text',
                ]);
                $createdMessages++;

                // رسالة نظام اختيارية لإبراز السيناريو
                Message::createSystemMessage(
                    $conversation->id,
                    'تم إنشاء المحادثة التجريبية لضمان عمل النظام',
                    ['seeded' => true]
                );
                $createdMessages++;

                // حدّث الوقت الخاص بآخر رسالة
                $conversation->updateLastMessageTime();
            }
        }

        $this->command?->info('تم تحضير محادثات الأدمن مع المستخدمين: ' . $createdConversations . ' محادثة جديدة، و ' . $createdMessages . ' رسالة.');
    }
}
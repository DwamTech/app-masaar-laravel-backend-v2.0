<?php
// app/Support/Notifier.php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\NotificationReceived;
use App\Events\UnreadNotificationsCountUpdated;

class Notifier {
  public static function send(User $user, string $type, string $title, string $body, array $data=[], ?string $link=null): void {
    // دمج البيانات مع الرابط (للاستخدام في الواجهة الأمامية والتتبع)
    $payloadData = $data ?? [];
    if ($link) {
      $payloadData['link'] = $link;
    }

    // إنشاء الإشعار في قاعدة البيانات (يشمل data و link)
    $notification = $user->notifications()->create([
      'type'    => $type,
      'title'   => $title,
      'message' => $body,
      'link'    => $link,
      'data'    => $payloadData,
    ]);

    // بث حدث الاستلام الفوري ليصل عبر Laravel Echo
    if ($notification) {
      event(new NotificationReceived($notification));
      // بث عدد الإشعارات غير المقروءة المحدَّث
      $unreadCount = $user->notifications()->where('is_read', false)->count();
      event(new UnreadNotificationsCountUpdated($user->id, $unreadCount));
    }

    // إعدادات وإحصائيات قبل محاولة إرسال Push
    $enabled = (bool)($user->push_notifications_enabled ?? true);
    $tokens = method_exists($user,'deviceTokens')
      ? $user->deviceTokens()->where('is_enabled',1)->pluck('token')->all()
      : DB::table('device_tokens')->where('user_id',$user->id)->where('is_enabled',1)->pluck('token')->all();
    $tokensCount = is_array($tokens) ? count($tokens) : 0;
    $projectId    = config('services.fcm_v1.project_id');
    $credentials  = config('services.fcm_v1.credentials');
    $fcmConfigured = !empty($projectId) && !empty($credentials) && @file_exists($credentials);

    // سجل القرار التشخيصي
    Log::info('[Notifier] Push decision', [
      'user_id'        => $user->id,
      'type'           => $type,
      'title'          => $title,
      'enabled'        => $enabled,
      'tokens_count'   => $tokensCount,
      'fcm_configured' => $fcmConfigured,
    ]);

    // حالات التخطي
    if (!$enabled) {
      Log::warning("[Notifier] SKIP push: user disabled push notifications", ['user_id' => $user->id]);
      return;
    }
    if ($tokensCount === 0) {
      Log::warning("[Notifier] SKIP push: no active device tokens", ['user_id' => $user->id]);
      return;
    }
    if (!$fcmConfigured) {
      Log::error("[Notifier] SKIP push: FCM v1 not configured on server", ['user_id' => $user->id]);
      return;
    }

    // إرسال Push Notification
    app(\App\Services\FcmHttpV1Service::class)->sendToTokens(
      $tokens,
      ['title' => $title, 'body' => $body],
      $payloadData
    );
  }
}

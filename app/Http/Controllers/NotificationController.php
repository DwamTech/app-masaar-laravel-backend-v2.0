<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Events\UnreadNotificationsCountUpdated;

class NotificationController extends Controller
{
    // جلب إشعارات المستخدم الحالي
    public function index(Request $request)
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();

        return response()->json([
            'status' => true,
            'notifications' => $notifications
        ]);
    }

    // تعيين إشعار كمقروء
    public function markAsRead($id){
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

        // بث التحديث الفوري لعدد الإشعارات غير المقروءة
        $unreadCount = $user->notifications()->where('is_read', false)->count();
        event(new UnreadNotificationsCountUpdated($user->id, $unreadCount));

        return response()->json([
            'status' => true,
            'message' => 'تم تعيين الإشعار كمقروء',
            'unread_count' => $unreadCount,
        ]);
    }

    // حذف إشعار
    public function destroy($id)
    {
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف الإشعار بنجاح',
        ]);
    }

    // عدد الإشعارات غير المقروءة للمستخدم الحالي
    public function unreadCount(Request $request)
    {
        $user = auth()->user();
        $count = $user->notifications()->where('is_read', false)->count();

        return response()->json([
            'status' => true,
            'unread_count' => $count,
        ]);
    }
}

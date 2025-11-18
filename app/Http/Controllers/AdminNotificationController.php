<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Support\Notifier;

class AdminNotificationController extends Controller
{
    // قائمة المستخدمين الذين لديهم Device Token مفعل
    public function eligibleUsers(Request $request)
    {
        $query = trim((string)$request->query('q', ''));

        $builder = DB::table('users')
            ->join('device_tokens', 'device_tokens.user_id', '=', 'users.id')
            ->where('device_tokens.is_enabled', 1)
            ->select(
                'users.id', 'users.name', 'users.email', 'users.phone', 'users.user_type',
                DB::raw('COUNT(device_tokens.id) as tokens_count')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'users.phone', 'users.user_type')
            ->orderBy('users.name');

        if ($query !== '') {
            $builder->where(function ($q) use ($query) {
                $q->where('users.name', 'like', "%$query%")
                  ->orWhere('users.email', 'like', "%$query%")
                  ->orWhere('users.phone', 'like', "%$query%");
            });
        }

        $users = $builder->limit(200)->get();
        return response()->json([
            'status' => true,
            'users' => $users,
        ]);
    }

    // إرسال إشعار مخصص لمستخدم محدد
    public function send(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'link'    => 'nullable|string',
        ]);

        $user = User::findOrFail($data['user_id']);

        // معلومات مبدئية عن حالة الدفع
        $tokens = DB::table('device_tokens')
            ->where('user_id', $user->id)
            ->where('is_enabled', 1)
            ->pluck('token')
            ->all();
        $pushEnabled = (bool)($user->push_notifications_enabled ?? true);

        // إرسال الإشعار عبر النظام المركزي مع تتبع النتيجة
        $result = Notifier::send(
            $user,
            'admin_custom',
            $data['title'],
            $data['body'],
            [],
            $data['link'] ?? null
        );

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال الإشعار',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'push_enabled' => $pushEnabled,
                'tokens_count' => count($tokens),
            ],
            'result' => $result,
        ]);
    }
}
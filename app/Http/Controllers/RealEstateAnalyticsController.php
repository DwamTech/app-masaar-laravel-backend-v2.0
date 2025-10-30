<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Appointment;
use App\Models\RealEstate;

class RealEstateAnalyticsController extends Controller
{
    /**
     * إحصائيات مزود عقارات: عدد العقارات، عدد الطلبات، وأعلى 6 عقارات مشاهدة.
     * GET /api/real-estate/analytics
     * Optional Query: user_id (للأدمن فقط لعرض مزود آخر)
     */
    public function overview(Request $request)
    {
        $authUser = $request->user();
        $targetUserId = (int) ($request->query('user_id', $authUser->id));

        // منع الاطلاع على مزود آخر إلا للأدمن
        if ($targetUserId !== (int) $authUser->id && ($authUser->user_type ?? null) !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'غير مصرح بعرض إحصائيات مستخدم آخر'
            ], 403);
        }

        // البحث عن كيان العقارات المرتبط بالمستخدم (للتوافق مع بيانات قديمة فيها real_estate_id)
        $realEstate = RealEstate::where('user_id', $targetUserId)->first();

        // بناء استعلام العقارات الخاصة بالمستخدم
        $propertiesQuery = Property::query()->where('user_id', $targetUserId);
        if ($realEstate) {
            $propertiesQuery->orWhere('real_estate_id', $realEstate->id);
        }

        $propertiesCount = (clone $propertiesQuery)->count();

        // عدد الطلبات (طلبات معاينة) الخاصة بهذا المزود
        $requestsCount = Appointment::where('provider_id', $targetUserId)->count();

        // أعلى 6 عقارات مشاهدة — نعيد فقط (title, view)
        $topProperties = (clone $propertiesQuery)
            ->orderBy('view_count', 'desc')
            ->limit(6)
            ->get(['title', 'view_count', 'view'])
            ->map(function ($p) {
                return [
                    'title' => $p->title,
                    // نستخدم view_count الأساسي، وإن وُجد عمود قديم view نأخذ الأعلى
                    'view' => (int) (isset($p->view_count) ? $p->view_count : ($p->view ?? 0)),
                ];
            });

        return response()->json([
            'status' => true,
            'data' => [
                'user_id' => $targetUserId,
                'properties_count' => $propertiesCount,
                'requests_count' => $requestsCount,
                'top_properties' => $topProperties,
            ],
        ]);
    }
}
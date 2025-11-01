<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDriverEligible
{
    /**
     * تأكيد أهلية السائق للوصول لمسارات التوصيل
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'غير مصرح'], 401);
        }

        // يجب أن يكون المستخدم سائقاً
        if ($user->user_type !== 'driver') {
            return response()->json(['message' => 'هذه الخدمة مخصصة للسائقين فقط'], 403);
        }

        // يجب أن يمتلك السائق سيارة واحدة على الأقل معتمدة (owner_type = driver, is_reviewed = 1)
        $hasApprovedCar = $user->driverCars()
            ->where('owner_type', 'driver')
            ->where('is_reviewed', 1)
            ->exists();

        if (!$hasApprovedCar) {
            return response()->json([
                'message' => 'حسابك غير مؤهل: يجب اعتماد سيارتك أولًا من الإدارة قبل الوصول لمسارات التوصيل.',
                'code' => 'DRIVER_NOT_ELIGIBLE',
            ], 403);
        }

        return $next($request);
    }
}
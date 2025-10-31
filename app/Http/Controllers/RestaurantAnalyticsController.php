<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RestaurantAnalyticsController extends Controller
{
    /**
     * نظرة عامة لإحصائيات المطعم المسجل دخوله.
     * GET /api/restaurant/analytics
     * يدعم باراميتر اختياري period: today | 7d | 30d | month
     */
    public function overview(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->user_type !== 'restaurant' || !$user->restaurantDetail) {
            return response()->json(['status' => false, 'message' => 'غير مصرح لك بالوصول.'], 403);
        }

        $restaurantId = $user->restaurantDetail->id; // يشير إلى جدول restaurant_details

        // فلترة اختيارية حسب الفترة
        $period = $request->query('period');
        $ordersQuery = Order::where('restaurant_id', $restaurantId);
        if ($period) {
            $ordersQuery = $this->applyPeriodFilter($ordersQuery, $period);
        }

        // إجماليات الطلبات وحالاتها
        $totalOrders = (clone $ordersQuery)->count();
        $statusCounts = (clone $ordersQuery)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // الإيرادات (نحسب إيرادات الطلبات المكتملة فقط)
        $revenueTotal = (clone $ordersQuery)
            ->where('status', 'completed')
            ->sum('total_price');

        $averageOrderValue = 0.0;
        $completedCount = (clone $ordersQuery)->where('status', 'completed')->count();
        if ($completedCount > 0) {
            $averageOrderValue = $revenueTotal / $completedCount;
        }

        // عدد الأقسام والوجبات (مربوط بـ restaurant_details.id)
        $sectionsCount = MenuSection::where('restaurant_id', $restaurantId)->count();

        // عدد الوجبات: نحسب بطريقتين لضمان الاتساق مع بيانات قديمة
        // 1) مباشرة من menu_items بالـ restaurant_id
        $itemsCountByRestaurant = MenuItem::where('restaurant_id', $restaurantId)->count();
        // 2) عبر join مع الأقسام للتأكد من صحة الربط
        $itemsCountBySections = DB::table('menu_items as mi')
            ->join('menu_sections as ms', 'ms.id', '=', 'mi.section_id')
            ->where('ms.restaurant_id', $restaurantId)
            ->count('mi.id');
        $itemsCount = max($itemsCountByRestaurant, $itemsCountBySections);

        // أفضل 5 وجبات مبيعاً (حسب إجمالي الكمية)
        $topItems = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->where('o.restaurant_id', $restaurantId)
            ->when($period, function ($q) use ($period) {
                return $this->applyPeriodFilter($q, $period, 'o');
            })
            ->select(
                'oi.menu_item_id',
                'oi.title',
                DB::raw('SUM(oi.quantity) as total_qty'),
                DB::raw('SUM(oi.total_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT oi.order_id) as orders_count')
            )
            ->groupBy('oi.menu_item_id', 'oi.title')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // مخطط عدد الطلبات لآخر 7 أيام (غير متأثر بـ period حتى تبقى رؤية سريعة)
        $chartDays = 7;
        $chart = [];
        for ($i = $chartDays - 1; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $count = Order::where('restaurant_id', $restaurantId)
                ->whereDate('created_at', $day->toDateString())
                ->count();
            $chart[] = [
                'date' => $day->toDateString(),
                'orders' => $count,
            ];
        }

        return response()->json([
            'status' => true,
            'period' => $period ?: 'all',
            'data' => [
                'orders' => [
                    'total' => $totalOrders,
                    'by_status' => [
                        'accepted_by_admin' => (int) ($statusCounts['accepted_by_admin'] ?? 0),
                        'processing' => (int) ($statusCounts['processing'] ?? 0),
                        'completed' => (int) ($statusCounts['completed'] ?? 0),
                        'rejected_by_admin' => (int) ($statusCounts['rejected_by_admin'] ?? 0),
                        'pending' => (int) ($statusCounts['pending'] ?? 0),
                    ],
                ],
                'revenue' => [
                    'total_completed' => (float) $revenueTotal,
                    'average_order_value_completed' => round((float) $averageOrderValue, 2),
                ],
                'menu' => [
                    'sections' => $sectionsCount,
                    'items' => $itemsCount,
                ],
                'top_items' => $topItems,
                'chart_last_7_days' => $chart,
            ],
        ]);
    }

    private function applyPeriodFilter($query, string $period, string $tableAlias = null)
    {
        $column = ($tableAlias ? $tableAlias . '.' : '') . 'created_at';
        switch ($period) {
            case 'today':
                return $query->whereDate($column, Carbon::today());
            case '7d':
                return $query->whereDate($column, '>=', Carbon::today()->subDays(6));
            case '30d':
                return $query->whereDate($column, '>=', Carbon::today()->subDays(29));
            case 'month':
                return $query->whereYear($column, Carbon::now()->year)
                             ->whereMonth($column, Carbon::now()->month);
            default:
                return $query; // الكل
        }
    }
}
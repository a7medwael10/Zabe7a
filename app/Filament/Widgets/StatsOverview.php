<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // عدد المستخدمين الكلي
        $totalUsers = User::count();

        // عدد الطلبات
        $totalOrders = Order::count();

        // عدد الطلبات المكتملة (تم التسليم)
        $completedOrders = Order::where('status', 6)->count(); // 6 == DONE

        // عدد الطلبات قيد الانتظار
        $pendingOrders = Order::where('status', 1)->count(); // 1 == PENDING

        // إجمالي المبيعات (لنفترض فيه عمود total_price بالطلب)
        $totalSales = Order::where('status', 6)->sum('total');

        // الإيرادات هذا الشهر
        $monthlyRevenue = Order::where('status', 6)
            ->whereMonth('delivered_at', Carbon::now()->month)
            ->sum('total');

        return [
            Stat::make('إجمالي المستخدمين', $totalUsers)
                ->description('إجمالي عدد المستخدمين المسجلين')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('إجمالي الطلبات', $totalOrders)
                ->description('عدد كل الطلبات')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('secondary'),

            Stat::make('الطلبات المكتملة', $completedOrders)
                ->description('تم التسليم')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('طلبات قيد الانتظار', $pendingOrders)
                ->description('بانتظار التنفيذ')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('إجمالي المبيعات', number_format($totalSales, 2) . ' ر.س')
                ->description('المبيعات المكتملة')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('إيرادات هذا الشهر', number_format($monthlyRevenue, 2) . ' ر.س')
                ->description('إجمالي الإيرادات في ' . Carbon::now()->format('F'))
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('info'),
        ];
    }


}

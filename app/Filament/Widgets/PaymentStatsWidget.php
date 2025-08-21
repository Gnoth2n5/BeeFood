<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPayments = Payment::count();
        $completedPayments = Payment::where('status', 'completed')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();
        $totalAmount = Payment::where('status', 'completed')->sum('transfer_amount');
        
        $completionRate = $totalPayments > 0 ? round(($completedPayments / $totalPayments) * 100, 1) : 0;

        return [
            Stat::make('Tổng số giao dịch', $totalPayments)
                ->description('Tất cả các giao dịch')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),

            Stat::make('Giao dịch hoàn thành', $completedPayments)
                ->description("Tỷ lệ: {$completionRate}%")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Giao dịch chờ xử lý', $pendingPayments)
                ->description('Cần xử lý')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Tổng số tiền', number_format($totalAmount) . ' đ')
                ->description('Từ giao dịch hoàn thành')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}

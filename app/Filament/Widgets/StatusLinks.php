<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatusLinks extends BaseWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('','System Health Panel')
                ->url(url('/health'))
                ->extraAttributes(['class' => 'text-center']),

            Stat::make("", 'Horizon')
                ->url(url('/horizon'))
                ->extraAttributes(['class' => 'text-center']),

                Stat::make('', 'Pulse')
                ->url(url('/pulse'))
                ->extraAttributes(['class' => 'text-center']),
        ];
    }
}

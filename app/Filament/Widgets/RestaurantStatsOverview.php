<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RestaurantStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $categoriesCount = Category::query()->count();
        $productsCount = Product::query()->count();
        $flaggedCount = Product::query()->where('is_flag', true)->count();
        $testimonialsCount = Testimonial::query()->count();
        $branchesCount = Branch::query()->count();

        return [
            Stat::make(__('panel.stats.categories'), $categoriesCount)
                ->description(__('panel.stats.categories_desc'))
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->icon('heroicon-o-squares-2x2')
                ->color('primary')
                ->url(route('filament.admin.resources.categories.index')),

            Stat::make(__('panel.stats.products'), $productsCount)
                ->description(__('panel.stats.products_desc', ['count' => $flaggedCount]))
                ->descriptionIcon('heroicon-m-fire')
                ->icon('heroicon-o-cake')
                ->color('warning')
                ->url(route('filament.admin.resources.products.index')),

            Stat::make(__('panel.stats.testimonials'), $testimonialsCount)
                ->description(__('panel.stats.testimonials_desc'))
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url(route('filament.admin.resources.testimonials.index')),

            Stat::make(__('panel.stats.branches'), $branchesCount)
                ->description(__('panel.stats.branches_desc'))
                ->descriptionIcon('heroicon-m-map-pin')
                ->icon('heroicon-o-map-pin')
                ->color('info')
                ->url(route('filament.admin.resources.branches.index')),
        ];
    }
}

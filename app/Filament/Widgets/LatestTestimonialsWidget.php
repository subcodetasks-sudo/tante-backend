<?php

namespace App\Filament\Widgets;

use App\Models\Testimonial;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTestimonialsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('panel.widgets.latest_testimonials');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Testimonial::query()->latest()
            )
            ->paginated([5])
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('panel.fields.name'))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('rating')
                    ->label(__('panel.fields.rating'))
                    ->formatStateUsing(fn ($state) => str_repeat('★', (int) $state))
                    ->color('warning'),
                Tables\Columns\TextColumn::make('review')
                    ->label(__('panel.fields.review'))
                    ->limit(35),
            ])
            ->emptyStateHeading(__('panel.widgets.empty_testimonials'));
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BranchesOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('panel.widgets.branches');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Branch::query()->latest()
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('panel.fields.name'))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('panel.fields.address'))
                    ->limit(50),
                Tables\Columns\TextColumn::make('open_from')
                    ->label(__('panel.fields.open_from'))
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : '—'),
                Tables\Columns\TextColumn::make('open_to')
                    ->label(__('panel.fields.open_to'))
                    ->badge()
                    ->color('danger')
                    ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : '—'),
            ])
            ->emptyStateHeading(__('panel.widgets.empty_branches'));
    }
}

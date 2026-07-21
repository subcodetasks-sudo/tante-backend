<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MostOrderedProductsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('panel.widgets.most_ordered');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->with(['category', 'weights'])
                    ->mostOrdered()
                    ->latest()
            )
            ->paginated([5])
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('panel.fields.name_ar'))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category.name_ar')
                    ->label(__('panel.fields.category'))
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('panel.fields.price'))
                    ->placeholder('—')
                    ->formatStateUsing(fn ($state, Product $record) => $record->hasWeights()
                        ? $record->weights->map(fn ($weight) => $weight->weight.': '.number_format((float) $weight->price, 2).' ر.س')->join(' | ')
                        : ($state !== null ? number_format((float) $state, 2).' ر.س' : '—')),
            ])
            ->emptyStateHeading(__('panel.widgets.empty_most_ordered'));
    }
}

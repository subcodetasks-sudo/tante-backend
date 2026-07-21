<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.menu');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.product.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('panel.product.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.product.plural');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('weights');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.product_info'))
                    ->description(__('panel.sections.product_info_desc'))
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label(__('panel.fields.category'))
                            ->relationship('category', 'name_ar')
                            ->getOptionLabelFromRecordUsing(
                                fn ($record) => $record->name_ar.' / '.$record->name_en
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('panel.fields.name_ar'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('panel.fields.name_en'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('calories')
                            ->label(__('panel.fields.calories'))
                            ->helperText(__('panel.fields.calories_help'))
                            ->maxLength(50)
                            ->suffix('kcal')
                            ->visible(fn (Get $get) => empty($get('weights')))
                            ->dehydrated(fn (Get $get) => empty($get('weights'))),
                        Forms\Components\TextInput::make('price')
                            ->label(__('panel.fields.price'))
                            ->numeric()
                            ->minValue(0)
                            ->prefix('ر.س')
                            ->required(fn (Get $get) => empty($get('weights')))
                            ->visible(fn (Get $get) => empty($get('weights')))
                            ->dehydrated(fn (Get $get) => empty($get('weights'))),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('panel.fields.is_active'))
                            ->default(true)
                            ->inline(false),
                        Forms\Components\Toggle::make('is_flag')
                            ->label(__('panel.fields.is_flag'))
                            ->helperText(__('panel.fields.is_flag_help'))
                            ->default(false)
                            ->inline(false),
                        Forms\Components\FileUpload::make('image')
                            ->label(__('panel.fields.image'))
                            ->disk('public')
                            ->directory('products/images')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make(__('panel.sections.product_weights'))
                    ->description(__('panel.sections.product_weights_desc'))
                    ->icon('heroicon-o-scale')
                    ->schema([
                        Forms\Components\Repeater::make('weights')
                            ->relationship()
                            ->label(__('panel.fields.weights'))
                            ->schema([
                                Forms\Components\TextInput::make('weight')
                                    ->label(__('panel.fields.weight'))
                                    ->required()
                                    ->maxLength(50)
                                    ->placeholder('250g'),
                                Forms\Components\TextInput::make('calories')
                                    ->label(__('panel.fields.calories'))
                                    ->helperText(__('panel.fields.calories_help'))
                                    ->maxLength(50)
                                    ->suffix('kcal'),
                                Forms\Components\TextInput::make('price')
                                    ->label(__('panel.fields.price'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('ر.س'),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel(__('panel.actions.add_weight'))
                            ->reorderable()
                            ->orderColumn('sort_order')
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('panel.sections.product_info'))
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->label(__('panel.fields.image'))
                            ->disk('public')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('category.name_ar')
                            ->label(__('panel.fields.category')),
                        Infolists\Components\TextEntry::make('name_ar')
                            ->label(__('panel.fields.name_ar')),
                        Infolists\Components\TextEntry::make('name_en')
                            ->label(__('panel.fields.name_en')),
                        Infolists\Components\TextEntry::make('calories')
                            ->label(__('panel.fields.calories'))
                            ->suffix(' kcal')
                            ->visible(fn (Product $record) => ! $record->hasWeights())
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('price')
                            ->label(__('panel.fields.price'))
                            ->money('SAR')
                            ->badge()
                            ->color('primary')
                            ->visible(fn (Product $record) => ! $record->hasWeights())
                            ->placeholder('—'),
                        Infolists\Components\RepeatableEntry::make('weights')
                            ->label(__('panel.fields.weights'))
                            ->schema([
                                Infolists\Components\TextEntry::make('weight')
                                    ->label(__('panel.fields.weight')),
                                Infolists\Components\TextEntry::make('calories')
                                    ->label(__('panel.fields.calories'))
                                    ->suffix(' kcal')
                                    ->placeholder('—'),
                                Infolists\Components\TextEntry::make('price')
                                    ->label(__('panel.fields.price'))
                                    ->money('SAR'),
                            ])
                            ->columns(3)
                            ->visible(fn (Product $record) => $record->hasWeights())
                            ->columnSpanFull(),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label(__('panel.fields.is_active'))
                            ->boolean(),
                        Infolists\Components\IconEntry::make('is_flag')
                            ->label(__('panel.fields.is_flag'))
                            ->boolean(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('panel.fields.image'))
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('panel.fields.name_ar'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('panel.fields.name_en'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category.name_ar')
                    ->label(__('panel.fields.category'))
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('calories')
                    ->label(__('panel.fields.calories'))
                    ->suffix(' kcal')
                    ->placeholder('—')
                    ->formatStateUsing(fn ($state, Product $record) => $record->hasWeights()
                        ? $record->weights->map(fn ($weight) => $weight->weight.': '.($weight->calories ?: '—'))->join(' | ')
                        : $state),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('panel.fields.price'))
                    ->money('SAR')
                    ->sortable()
                    ->placeholder('—')
                    ->formatStateUsing(fn ($state, Product $record) => $record->hasWeights()
                        ? $record->weights->map(fn ($weight) => $weight->weight.': '.number_format((float) $weight->price, 2).' ر.س')->join(' | ')
                        : ($state !== null ? number_format((float) $state, 2).' ر.س' : '—')),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('panel.fields.is_active'))
                    ->boolean(),
                Tables\Columns\ToggleColumn::make('is_flag')
                    ->label(__('panel.fields.is_flag')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('panel.fields.updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('panel.fields.category'))
                    ->relationship('category', 'name_ar'),
                Tables\Filters\TernaryFilter::make('is_flag')
                    ->label(__('panel.fields.is_flag')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}

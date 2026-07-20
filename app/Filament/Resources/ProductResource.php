<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                            ->numeric()
                            ->minValue(0)
                            ->suffix('kcal'),
                        Forms\Components\TextInput::make('price')
                            ->label(__('panel.fields.price'))
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('ر.س'),
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
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('price')
                            ->label(__('panel.fields.price'))
                            ->money('SAR')
                            ->badge()
                            ->color('primary'),
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
                    ->sortable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('panel.fields.price'))
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('panel.fields.is_active'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_flag')
                    ->label(__('panel.fields.is_flag'))
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray'),
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.menu');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.category.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('panel.category.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.category.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.category_info'))
                    ->description(__('panel.sections.category_info_desc'))
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('panel.fields.name_ar'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('panel.fields.name_en'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('panel.fields.sort_order'))
                            ->numeric()
                            ->default(0),
                        Forms\Components\FileUpload::make('image')
                            ->label(__('panel.fields.image'))
                            ->disk('public')
                            ->directory('categories/images')
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
                Infolists\Components\Section::make(__('panel.sections.category_info'))
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->label(__('panel.fields.image'))
                            ->disk('public')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('name_ar')
                            ->label(__('panel.fields.name_ar')),
                        Infolists\Components\TextEntry::make('name_en')
                            ->label(__('panel.fields.name_en')),
                        Infolists\Components\TextEntry::make('sort_order')
                            ->label(__('panel.fields.sort_order')),
                        Infolists\Components\TextEntry::make('products_count')
                            ->label(__('panel.fields.products_count'))
                            ->state(fn (Category $record) => $record->products()->count()),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('products_count')
                    ->label(__('panel.fields.products_count'))
                    ->counts('products')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('panel.fields.sort_order'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('panel.fields.updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([])
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
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}

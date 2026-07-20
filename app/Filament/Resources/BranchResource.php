<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.branches');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.branch.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('panel.branch.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.branch.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.branch_info'))
                    ->description(__('panel.sections.branch_info_desc'))
                    ->icon('heroicon-o-building-storefront')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('panel.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('address')
                            ->label(__('panel.fields.address'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make(__('panel.sections.working_hours'))
                    ->description(__('panel.sections.working_hours_desc'))
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\TimePicker::make('open_from')
                            ->label(__('panel.fields.open_from'))
                            ->seconds(false)
                            ->native(false),
                        Forms\Components\TimePicker::make('open_to')
                            ->label(__('panel.fields.open_to'))
                            ->seconds(false)
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('panel.sections.branch_info'))
                    ->icon('heroicon-o-building-storefront')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('panel.fields.name')),
                        Infolists\Components\TextEntry::make('address')
                            ->label(__('panel.fields.address'))
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make(__('panel.sections.working_hours'))
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Infolists\Components\TextEntry::make('open_from')
                            ->label(__('panel.fields.open_from'))
                            ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : '—')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('open_to')
                            ->label(__('panel.fields.open_to'))
                            ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : '—')
                            ->badge()
                            ->color('danger'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('panel.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('panel.fields.address'))
                    ->limit(40)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('open_from')
                    ->label(__('panel.fields.open_from'))
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : null),
                Tables\Columns\TextColumn::make('open_to')
                    ->label(__('panel.fields.open_to'))
                    ->badge()
                    ->color('danger')
                    ->formatStateUsing(fn ($state) => $state ? substr((string) $state, 0, 5) : null),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('panel.fields.updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
            'index' => Pages\ManageBranches::route('/'),
        ];
    }
}

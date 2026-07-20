<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchContentResource\Pages;
use App\Models\BranchContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BranchContentResource extends Resource
{
    protected static ?string $model = BranchContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.branches');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.branch_content.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('panel.branch_content.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.branch_content.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.branch_content'))
                    ->description(__('panel.sections.branch_content_desc'))
                    ->icon('heroicon-o-map')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('panel.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('panel.fields.description'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('panel.sections.branch_content'))
                    ->icon('heroicon-o-map')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label(__('panel.fields.title')),
                        Infolists\Components\TextEntry::make('description')
                            ->label(__('panel.fields.description'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('panel.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('panel.fields.description'))
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('panel.fields.updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
            'index' => Pages\ManageBranchContents::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return BranchContent::query()->count() === 0;
    }
}

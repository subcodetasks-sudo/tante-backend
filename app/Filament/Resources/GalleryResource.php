<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.gallery.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('panel.gallery.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.gallery.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.gallery_info'))
                    ->description(__('panel.sections.gallery_info_desc'))
                    ->icon('heroicon-o-film')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('panel.fields.title'))
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('type')
                            ->label(__('panel.fields.media_type'))
                            ->options([
                                'image' => __('panel.fields.type_image'),
                                'video' => __('panel.fields.type_video'),
                            ])
                            ->required()
                            ->native(false)
                            ->live()
                            ->default('image'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('panel.fields.sort_order'))
                            ->numeric()
                            ->default(0),
                        Forms\Components\FileUpload::make('image')
                            ->label(__('panel.fields.image'))
                            ->disk('public')
                            ->directory('galleries/images')
                            ->image()
                            ->imageEditor()
                            ->visible(fn (Get $get) => $get('type') === 'image')
                            ->required(fn (Get $get) => $get('type') === 'image')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('video_url')
                            ->label(__('panel.fields.video_url'))
                            ->url()
                            ->maxLength(255)
                            ->visible(fn (Get $get) => $get('type') === 'video')
                            ->required(fn (Get $get) => $get('type') === 'video')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('panel.sections.gallery_info'))
                    ->icon('heroicon-o-film')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label(__('panel.fields.title'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('type')
                            ->label(__('panel.fields.media_type'))
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state === 'video'
                                ? __('panel.fields.type_video')
                                : __('panel.fields.type_image')),
                        Infolists\Components\ImageEntry::make('image')
                            ->label(__('panel.fields.image'))
                            ->disk('public')
                            ->visible(fn ($record) => $record->type === 'image'),
                        Infolists\Components\TextEntry::make('video_url')
                            ->label(__('panel.fields.video_url'))
                            ->url(fn ($state) => $state)
                            ->visible(fn ($record) => $record->type === 'video'),
                        Infolists\Components\TextEntry::make('sort_order')
                            ->label(__('panel.fields.sort_order')),
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
                    ->disk('public'),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('panel.fields.title'))
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('panel.fields.media_type'))
                    ->badge()
                    ->color(fn ($state) => $state === 'video' ? 'warning' : 'success')
                    ->formatStateUsing(fn ($state) => $state === 'video'
                        ? __('panel.fields.type_video')
                        : __('panel.fields.type_image')),
                Tables\Columns\TextColumn::make('video_url')
                    ->label(__('panel.fields.video_url'))
                    ->limit(30)
                    ->toggleable()
                    ->placeholder('—'),
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
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('panel.fields.media_type'))
                    ->options([
                        'image' => __('panel.fields.type_image'),
                        'video' => __('panel.fields.type_video'),
                    ]),
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
            'index' => Pages\ManageGalleries::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.engagement');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.testimonial.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('panel.testimonial.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel.testimonial.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.customer_info'))
                    ->description(__('panel.sections.customer_info_desc'))
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label(__('panel.fields.image'))
                            ->disk('public')
                            ->directory('testimonials/images')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('video')
                            ->label(__('panel.fields.video'))
                            ->disk('public')
                            ->directory('testimonials/videos')
                            ->acceptedFileTypes([
                                'video/mp4',
                                'video/webm',
                                'video/ogg',
                                'video/quicktime',
                            ])
                            ->maxSize(512000)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->label(__('panel.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('rating')
                            ->label(__('panel.fields.rating'))
                            ->options([
                                1 => '★☆☆☆☆ (1)',
                                2 => '★★☆☆☆ (2)',
                                3 => '★★★☆☆ (3)',
                                4 => '★★★★☆ (4)',
                                5 => '★★★★★ (5)',
                            ])
                            ->required()
                            ->default(5)
                            ->native(false),
                    ])
                    ->columns(2),
                Forms\Components\Section::make(__('panel.sections.customer_review'))
                    ->description(__('panel.sections.customer_review_desc'))
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->schema([
                        Forms\Components\Textarea::make('review')
                            ->label(__('panel.fields.review'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('panel.sections.customer_info'))
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->label(__('panel.fields.image'))
                            ->disk('public')
                            ->circular()
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('video')
                            ->label(__('panel.fields.video'))
                            ->formatStateUsing(fn ($state) => $state ? __('panel.fields.type_video') : '—')
                            ->url(fn ($record) => $record->video_url)
                            ->openUrlInNewTab()
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('panel.fields.name')),
                        Infolists\Components\TextEntry::make('rating')
                            ->label(__('panel.fields.rating'))
                            ->formatStateUsing(fn ($state) => str_repeat('★', (int) $state).str_repeat('☆', 5 - (int) $state))
                            ->color('warning'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('panel.sections.customer_review'))
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->schema([
                        Infolists\Components\TextEntry::make('review')
                            ->label(__('panel.fields.review'))
                            ->columnSpanFull(),
                    ]),
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
                Tables\Columns\IconColumn::make('video')
                    ->label(__('panel.fields.video'))
                    ->boolean()
                    ->getStateUsing(fn ($record) => filled($record->video))
                    ->trueIcon('heroicon-o-video-camera')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('panel.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('rating')
                    ->label(__('panel.fields.rating'))
                    ->sortable()
                    ->formatStateUsing(fn ($state) => str_repeat('★', (int) $state).str_repeat('☆', 5 - (int) $state))
                    ->color('warning'),
                Tables\Columns\TextColumn::make('review')
                    ->label(__('panel.fields.review'))
                    ->limit(40)
                    ->toggleable(),
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
            'index' => Pages\ManageTestimonials::route('/'),
        ];
    }
}

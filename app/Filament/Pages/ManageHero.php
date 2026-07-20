<?php

namespace App\Filament\Pages;

use App\Models\Hero;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageHero extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static string $view = 'filament.pages.manage-singleton';

    protected static ?int $navigationSort = 1;

    public ?Hero $record = null;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.hero.navigation');
    }

    public function getTitle(): string
    {
        return __('panel.hero.navigation');
    }

    public function mount(): void
    {
        $this->record = Hero::query()->firstOrCreate(
            ['id' => 1],
            [
                'title' => 'طعم الأصالة',
                'description' => null,
                'content' => null,
                'button_1_name' => null,
                'button_2_name' => null,
                'video' => null,
            ],
        );

        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.main_content'))
                    ->description(__('panel.sections.main_content_desc'))
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('panel.fields.title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('panel.fields.description'))
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('content')
                            ->label(__('panel.fields.content'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make(__('panel.sections.buttons'))
                    ->description(__('panel.sections.buttons_desc'))
                    ->icon('heroicon-o-cursor-arrow-rays')
                    ->schema([
                        Forms\Components\TextInput::make('button_1_name')
                            ->label(__('panel.fields.button_1'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('button_2_name')
                            ->label(__('panel.fields.button_2'))
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Forms\Components\Section::make(__('panel.sections.video'))
                    ->description(__('panel.sections.video_desc'))
                    ->icon('heroicon-o-video-camera')
                    ->schema([
                        Forms\Components\FileUpload::make('video')
                            ->label(__('panel.fields.video'))
                            ->disk('public')
                            ->directory('heroes/videos')
                            ->acceptedFileTypes([
                                'video/mp4',
                                'video/webm',
                                'video/ogg',
                                'video/quicktime',
                            ])
                            ->maxSize(102400)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->success()
            ->send();
    }
}

<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.manage-singleton';

    protected static ?int $navigationSort = 1;

    public ?Setting $record = null;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.system');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.setting.navigation');
    }

    public function getTitle(): string
    {
        return __('panel.setting.navigation');
    }

    public function mount(): void
    {
        $this->record = Setting::query()->firstOrCreate(
            ['id' => 1],
            [
                'restaurant_name' => 'طنط',
                'logo' => null,
                'description' => null,
                'facebook' => null,
                'instagram' => null,
                'twitter' => null,
                'youtube' => null,
                'tiktok' => null,
                'whatsapp' => null,
            ],
        );

        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.restaurant_info'))
                    ->description(__('panel.sections.restaurant_info_desc'))
                    ->icon('heroicon-o-building-storefront')
                    ->schema([
                        Forms\Components\TextInput::make('restaurant_name')
                            ->label(__('panel.fields.restaurant_name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('logo')
                            ->label(__('panel.fields.logo'))
                            ->disk('public')
                            ->directory('settings/logos')
                            ->image()
                            ->imageEditor()
                            ->avatar(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('panel.fields.description'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make(__('panel.sections.social_links'))
                    ->description(__('panel.sections.social_links_desc'))
                    ->icon('heroicon-o-share')
                    ->schema([
                        Forms\Components\TextInput::make('facebook')
                            ->label('Facebook')
                            ->url()
                            ->prefixIcon('heroicon-m-globe-alt')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('instagram')
                            ->label('Instagram')
                            ->url()
                            ->prefixIcon('heroicon-m-camera')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('twitter')
                            ->label('Twitter / X')
                            ->url()
                            ->prefixIcon('heroicon-m-at-symbol')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('youtube')
                            ->label('YouTube')
                            ->url()
                            ->prefixIcon('heroicon-m-play')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tiktok')
                            ->label('TikTok')
                            ->url()
                            ->prefixIcon('heroicon-m-musical-note')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->tel()
                            ->prefixIcon('heroicon-m-phone')
                            ->maxLength(255),
                    ])
                    ->columns(2),
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

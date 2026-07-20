<?php

namespace App\Filament\Pages;

use App\Models\About;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageAbout extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static string $view = 'filament.pages.manage-singleton';

    protected static ?int $navigationSort = 2;

    public ?About $record = null;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.about.navigation');
    }

    public function getTitle(): string
    {
        return __('panel.about.navigation');
    }

    public function mount(): void
    {
        $this->record = About::query()->firstOrCreate(
            ['id' => 1],
            [
                'title' => 'من نحن',
                'description' => null,
                'content' => null,
                'image' => null,
            ],
        );

        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.about_texts'))
                    ->description(__('panel.sections.about_texts_desc'))
                    ->icon('heroicon-o-document-text')
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
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make(__('panel.sections.about_image'))
                    ->description(__('panel.sections.about_image_desc'))
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label(__('panel.fields.image'))
                            ->disk('public')
                            ->directory('abouts/images')
                            ->image()
                            ->imageEditor()
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

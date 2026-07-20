<?php

namespace App\Filament\Pages;

use App\Models\MostOrderedContent;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageMostOrderedContent extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static string $view = 'filament.pages.manage-singleton';

    protected static ?int $navigationSort = 3;

    public ?MostOrderedContent $record = null;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('panel.groups.menu');
    }

    public static function getNavigationLabel(): string
    {
        return __('panel.most_ordered.navigation');
    }

    public function getTitle(): string
    {
        return __('panel.most_ordered.navigation');
    }

    public function mount(): void
    {
        $this->record = MostOrderedContent::query()->firstOrCreate(
            ['id' => 1],
            [
                'title' => 'الأكثر طلبًا',
                'description' => 'أشهى الأطباق التي يفضلها عملاؤنا',
            ],
        );

        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('panel.sections.most_ordered'))
                    ->description(__('panel.sections.most_ordered_desc'))
                    ->icon('heroicon-o-fire')
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

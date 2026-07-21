<?php

namespace App\Filament\Pages;

use App\Models\MostOrderedContent;
use App\Models\Product;
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

        $this->form->fill([
            ...$this->record->attributesToArray(),
            'product_ids' => Product::query()
                ->where('is_flag', true)
                ->pluck('id')
                ->all(),
        ]);
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
                Forms\Components\Section::make(__('panel.sections.most_ordered_products'))
                    ->description(__('panel.sections.most_ordered_products_desc'))
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        Forms\Components\Select::make('product_ids')
                            ->label(__('panel.fields.most_ordered_products'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => Product::query()
                                ->with('category')
                                ->where('is_active', true)
                                ->orderBy('name_ar')
                                ->get()
                                ->mapWithKeys(fn (Product $product) => [
                                    $product->id => $product->name_ar.' / '.$product->name_en
                                        .($product->category ? ' ('.$product->category->name_ar.')' : ''),
                                ]))
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $productIds = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        $this->record->update($data);

        Product::query()->update(['is_flag' => false]);

        if (! empty($productIds)) {
            Product::query()
                ->whereIn('id', $productIds)
                ->update(['is_flag' => true]);
        }

        Notification::make()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->success()
            ->send();
    }
}

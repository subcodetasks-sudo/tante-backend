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
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ManageMostOrderedContent extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static string $view = 'filament.pages.manage-most-ordered-content';

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

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('panel.sections.most_ordered_products'))
            ->description(__('panel.sections.most_ordered_products_desc'))
            ->query(
                Product::query()
                    ->with(['category', 'weights'])
                    ->mostOrdered()
                    ->latest()
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('panel.fields.image'))
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('panel.fields.name_ar'))
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('panel.fields.name_en'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category.name_ar')
                    ->label(__('panel.fields.category'))
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('calories')
                    ->label(__('panel.fields.calories'))
                    ->suffix(' kcal')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('panel.fields.price'))
                    ->placeholder('—')
                    ->formatStateUsing(fn ($state, Product $record) => $record->hasWeights()
                        ? $record->weights->map(fn ($weight) => $weight->weight.': '.number_format((float) $weight->price, 2).' ر.س')->join(' | ')
                        : ($state !== null ? number_format((float) $state, 2).' ر.س' : '—')),
            ])
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading(__('panel.widgets.empty_most_ordered'))
            ->emptyStateDescription(__('panel.sections.most_ordered_products_empty_desc'))
            ->actions([])
            ->bulkActions([])
            ->headerActions([]);
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

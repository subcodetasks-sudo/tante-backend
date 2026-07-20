<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickLinksWidget extends Widget
{
    protected static ?int $sort = 5;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.quick-links-widget';
}

<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\BranchesOverviewWidget;
use App\Filament\Widgets\LatestTestimonialsWidget;
use App\Filament\Widgets\MostOrderedProductsWidget;
use App\Filament\Widgets\QuickLinksWidget;
use App\Filament\Widgets\RestaurantStatsOverview;
use App\Filament\Widgets\WelcomeWidget;
use App\Http\Middleware\SetLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName(fn () => __('panel.brand'))
            ->font('Cairo')
            ->darkMode(true, true)
            ->maxContentWidth('full')
            ->navigationGroups([
                NavigationGroup::make(fn () => __('panel.groups.content')),
                NavigationGroup::make(fn () => __('panel.groups.menu')),
                NavigationGroup::make(fn () => __('panel.groups.branches')),
                NavigationGroup::make(fn () => __('panel.groups.engagement')),
                NavigationGroup::make(fn () => __('panel.groups.system')),
            ])
            ->colors([
                'danger' => Color::Rose,
                'gray' => [
                    50 => '#f4f7f4',
                    100 => '#e4ebe4',
                    200 => '#c8d6c8',
                    300 => '#9fb59f',
                    400 => '#6f8f6f',
                    500 => '#4f6f4f',
                    600 => '#3d583d',
                    700 => '#2f4632',
                    800 => '#1f3324',
                    900 => '#15241a',
                    950 => '#0c1610',
                ],
                'info' => Color::Sky,
                'primary' => [
                    50 => '#fbf6e8',
                    100 => '#f5eac7',
                    200 => '#ebd48a',
                    300 => '#dfbc4e',
                    400 => '#d4a84a',
                    500 => '#c9a227',
                    600 => '#a8841f',
                    700 => '#86661a',
                    800 => '#6b5118',
                    900 => '#594316',
                    950 => '#34250b',
                ],
                'success' => Color::hex('#3d6b45'),
                'warning' => Color::hex('#d4a84a'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                WelcomeWidget::class,
                RestaurantStatsOverview::class,
                MostOrderedProductsWidget::class,
                LatestTestimonialsWidget::class,
                BranchesOverviewWidget::class,
                QuickLinksWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('@include(\'filament.hooks.theme-styles\')'),
            )
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn (): string => Blade::render('@include(\'filament.hooks.locale-switcher\')'),
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => Blade::render('<div class="mb-4 flex justify-center">@include(\'filament.hooks.locale-switcher\')</div>'),
            );
    }
}

<x-filament-widgets::widget class="fi-quick-links-widget">
    <x-filament::section>
        <x-slot name="heading">
            {{ __('panel.widgets.quick_links') }}
        </x-slot>

        <div class="fi-quick-links-grid">
            <a href="{{ route('filament.admin.resources.categories.index') }}" class="fi-quick-link">
                <x-heroicon-o-squares-2x2 class="fi-quick-link-icon" />
                <span>{{ __('panel.category.navigation') }}</span>
            </a>

            <a href="{{ route('filament.admin.resources.products.index') }}" class="fi-quick-link">
                <x-heroicon-o-cake class="fi-quick-link-icon" />
                <span>{{ __('panel.product.navigation') }}</span>
            </a>

            <a href="{{ route('filament.admin.pages.manage-most-ordered-content') }}" class="fi-quick-link">
                <x-heroicon-o-fire class="fi-quick-link-icon" />
                <span>{{ __('panel.most_ordered.navigation') }}</span>
            </a>

            <a href="{{ route('filament.admin.resources.branches.index') }}" class="fi-quick-link">
                <x-heroicon-o-map-pin class="fi-quick-link-icon" />
                <span>{{ __('panel.branch.navigation') }}</span>
            </a>

            <a href="{{ route('filament.admin.resources.testimonials.index') }}" class="fi-quick-link">
                <x-heroicon-o-chat-bubble-left-right class="fi-quick-link-icon" />
                <span>{{ __('panel.testimonial.navigation') }}</span>
            </a>

            <a href="{{ route('filament.admin.pages.manage-settings') }}" class="fi-quick-link">
                <x-heroicon-o-cog-6-tooth class="fi-quick-link-icon" />
                <span>{{ __('panel.setting.navigation') }}</span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

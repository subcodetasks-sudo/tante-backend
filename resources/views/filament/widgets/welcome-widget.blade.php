@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget class="fi-welcome-widget">
    <x-filament::section>
        <div class="fi-welcome-widget-inner flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-x-4">
                <x-filament-panels::avatar.user size="lg" :user="$user" />

                <div>
                    <h2 class="fi-welcome-title">
                        {{ __('panel.welcome.title') }}
                    </h2>

                    <p class="fi-welcome-subtitle">
                        {{ filament()->getUserName($user) }}
                    </p>
                </div>
            </div>

            <form
                action="{{ filament()->getLogoutUrl() }}"
                method="post"
                class="my-auto"
            >
                @csrf

                <x-filament::button
                    color="primary"
                    icon="heroicon-m-arrow-left-on-rectangle"
                    labeled-from="sm"
                    tag="button"
                    type="submit"
                >
                    {{ __('filament-panels::widgets/account-widget.actions.logout.label') }}
                </x-filament::button>
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

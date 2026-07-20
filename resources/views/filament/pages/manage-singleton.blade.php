<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="fi-form-actions flex justify-start gap-3">
            <x-filament::button type="submit" color="primary" size="lg">
                {{ __('filament-panels::resources/pages/edit-record.form.actions.save.label') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

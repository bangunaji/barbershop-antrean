{{-- resources/views/filament/pages/settings/general-settings.blade.php --}}

<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }} {{-- Ini akan merender skema form dari metode form() --}}

        <div class="filament-page-actions mt-6">
            {{-- Baris ini yang dihapus karena menyebabkan error dan tidak esensial --}}
            {{-- {{ \Filament\Support\Facades\Filament::renderHook('page.form.actions.after', [
                'page' => $this,
                'form' => $this->form,
                'actions' => $this->getFormActions(),
            ]) }} --}}

            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
                class="mt-6"
            />
        </div>
    </form>
</x-filament-panels::page>
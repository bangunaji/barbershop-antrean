<x-filament-panels::page>
    <x-filament::section>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
            <strong>⚠ Beta Testing:</strong> Sistem ini masih dalam tahap uji coba. Beberapa fitur mungkin belum stabil.
        </div>
    </x-filament::section>

    {{-- Tampilkan widget bawaan dashboard --}}
    @livewire('filament.pages.dashboard')
</x-filament-panels::page>

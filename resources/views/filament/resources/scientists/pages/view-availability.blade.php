<x-filament::page>
    <h2>Availability for {{ $this->scientist->name }}</h2>

    <livewire:availability-calendar-widget :scientist-id="$this->scientist->id" />
</x-filament::page>

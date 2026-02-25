<x-filament::page>
    @foreach ($this->getWidgets() as $widgetClass => $params)
        @livewire($widgetClass, $params)
    @endforeach
</x-filament::page>

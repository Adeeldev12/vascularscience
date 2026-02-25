@php
    $currentStep = $this->getCurrentStep();
    $totalSteps = count($this->getSteps());
    $isLastStep = $currentStep === $totalSteps;
@endphp

<div class="flex items-center justify-between gap-4">
    @if ($currentStep > 1)
        <x-filament::button
            type="button"
            color="gray"
            x-on:click="previous"
        >
            Previous
        </x-filament::button>
    @else
        <div></div> {{-- Empty div for spacing --}}
    @endif

    @if (! $isLastStep)
        <x-filament::button
            type="button"
            color="primary"
            x-on:click="next"
        >
            Next
        </x-filament::button>
    @else
        <x-filament::button
            type="submit"
            color="success"
            size="lg"
        >
            Register Now
        </x-filament::button>
    @endif
</div>

@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'primary', // primary, success, info, warning, danger
    'percentage' => null,
    'percentageColor' => 'success',
    'description' => null
])

<x-card class="mb-4">
    <div class="row">
        <div class="col-8">
            <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">{{ $title }}</p>
                <h5 class="font-weight-bolder mb-0">
                    {{ $value }}
                    @if($percentage)
                        <span class="text-{{ $percentageColor }} text-sm font-weight-bolder">{{ $percentage }}</span>
                    @endif
                </h5>
                @if($description)
                    <p class="text-sm mb-0 font-weight-bold">{{ $description }}</p>
                @endif
            </div>
        </div>
        <div class="col-4 text-end">
            @if($icon)
                <div class="icon icon-shape bg-gradient-{{ $color }} shadow text-center border-radius-md">
                    <i class="fas fa-{{ $icon }} text-lg opacity-10" aria-hidden="true"></i>
                </div>
            @else
                <div class="icon icon-shape bg-gradient-{{ $color }} shadow text-center border-radius-md">
                    <i class="fas fa-chart-bar text-lg opacity-10" aria-hidden="true"></i>
                </div>
            @endif
        </div>
    </div>
</x-card>

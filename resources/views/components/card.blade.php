<div class="card {{ $attributes->get('class') }}">
    @if(isset($header))
        <div class="card-header d-flex justify-content-between align-items-center">
            {{ $header }}
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
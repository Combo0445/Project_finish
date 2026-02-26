@props(['title', 'buttons' => null])

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ $title }}</h2>
    <div class="header-actions">
        {{ $buttons ?? $slot }}
    </div>
</div>
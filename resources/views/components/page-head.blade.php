@props(['title', 'subtitle' => null])
<div class="page-head">
    <div>
        <h4>{{ $title }}</h4>
        @if ($subtitle) <p>{{ $subtitle }}</p> @endif
    </div>
    @if (trim($slot))
        <div class="d-flex gap-2">{{ $slot }}</div>
    @endif
</div>

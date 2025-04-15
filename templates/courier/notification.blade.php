<x-courier::message :logo="$logo">
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Code --}}
@if (! empty($code))
<x-courier::code>
{{ $code }}
</x-courier::code>
@endif


        {{-- Action Button --}}
@isset($actionText)
@php
$color = match ($level) {
    'success', 'error' => $level,
    default => 'primary',
};
@endphp
<x-courier::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-courier::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
{{ option('beebmx.kirby-courier.message.salutation', 'Regards') }},
{{ option('beebmx.kirby-courier.message.brand_name', site()->title()->or('Courier')) }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
{{ option('beebmx.kirby-courier.message.notify', 'If you\'re having trouble clicking the button, copy and paste the URL below into your web browser') }}
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-courier::message>

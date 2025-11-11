{!! strip_tags(kirbytext($header ?? '')) !!}

{!! strip_tags(kirbytext($slot)) !!}

@isset($subcopy)

{!! strip_tags(kirbytext($subcopy)) !!}
@endisset

{!! strip_tags(kirbytext($footer ?? '')) !!}

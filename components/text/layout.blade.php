{!! html_entity_decode(strip_tags(kirbytext($header ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}

{!! html_entity_decode(strip_tags(kirbytext($slot)), ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}

@isset($subcopy)

{!! html_entity_decode(strip_tags(kirbytext($subcopy)), ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}
@endisset

{!! html_entity_decode(strip_tags(kirbytext($footer ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8') !!}

@props(['logo' => null])
<x-courier::layout>
{{-- Header --}}
<x-slot:header>
<x-courier::header :url="site()->url()">
@if(is_string($logo))
<img src="<?= $logo ?>" class="logo" alt="<?= site()->title() ?>">
@else
<?= site()->title()->or('Courier') ?>
@endif
</x-courier::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-courier::subcopy>
{{ $subcopy }}
</x-courier::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-courier::footer>
© {{ date('Y') }} {{ site()->title() }}. {{ option('beebmx.kirby-courier.message.rights', 'All rights reserved.') }}
</x-courier::footer>
</x-slot:footer>
</x-courier::layout>

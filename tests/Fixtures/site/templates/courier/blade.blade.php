<x-courier::message :logo="$logo">
# Blade courier template for: {{ $title ?? 'nobody' }}

The body of your message.

<x-courier::button url="https://example.com" color="success">
Blade button
</x-courier::button>

<x-courier::panel>
Blade panel
</x-courier::panel>

<x-courier::table>
| Content    | Price |
| ---------- | ----: |
| Blade row  | $100  |
</x-courier::table>

Thanks,
{{ site()->title() }}
</x-courier::message>

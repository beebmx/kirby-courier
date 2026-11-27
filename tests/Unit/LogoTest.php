<?php

use Beebmx\KirbyCourier\Logo;
use Kirby\Cms\App;

beforeEach(function () {
    App();
    $this->home = App::instance()->site()->homePage();
});

it('returns null without a logo', function () {
    expect(Logo::url(null))->toBeNull()
        ->and(Logo::url(''))->toBeNull()
        ->and(Logo::url(fn () => null))->toBeNull();
});

it('accepts a url string', function () {
    expect(Logo::url('https://example.com/logo.png'))->toBe('https://example.com/logo.png')
        ->and(Logo::url(fn () => 'https://example.com/logo.png'))->toBe('https://example.com/logo.png');
});

it('accepts an asset', function () {
    expect(Logo::url(fn () => asset('media/plugins/beebmx/kirby-courier/logo-700.png')))
        ->toEndWith('media/plugins/beebmx/kirby-courier/logo-700.png');
});

it('accepts a file', function () {
    expect(Logo::url(fn () => $this->home->file('empty.jpg')))
        ->toBe($this->home->file('empty.jpg')->url());
});

it('accepts a field with a file', function () {
    expect(Logo::url(fn () => $this->home->logo()))
        ->toBe($this->home->file('more.jpg')->url());
});

it('returns null for a field without a file', function () {
    expect(Logo::url(fn () => $this->home->title()))->toBeNull();
});

it('returns null for unsupported values', function () {
    expect(Logo::url(123))->toBeNull()
        ->and(Logo::url(fn () => new stdClass))->toBeNull();
});

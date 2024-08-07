<?php

use Beebmx\KirbyCourier\Content;
use Beebmx\KirbyCourier\Parse;

describe('basic', function () {
    beforeEach(function () {
        App();
    });

    it('return Content instance when render', function () {
        expect((new Parse('simple'))->render())
            ->toBeInstanceOf(Content::class);
    });

    it('return content parsed', function () {
        expect((new Parse('simple'))->render())
            ->toHtml()
            ->not->toContain('<h1>Simple courier template</h1>')
            ->toContain('-apple-system');
    });

    it('can pass data to parsed content', function () {
        expect((new Parse('basic'))->data(['title' => 'example'])->render())
            ->toHtml()
            ->toContain('example');
    });

    it('can change the default theme', function () {
        expect((new Parse('simple', 'black'))->render())
            ->toHtml()
            ->toContain('color: black');
    });
});

describe('advance', function () {
    beforeEach(function () {
        App(options: [
            'beebmx.kirby-courier.path' => 'emails',
        ]);
    });

    it('can pass data to parsed content', function () {
        expect((new Parse('email'))->data(['title' => 'homepage'])->render())
            ->toHtml()
            ->toContain('homepage');
    });

    it('can change the default theme', function () {
        expect((new Parse('simple', 'red'))->render())
            ->toHtml()
            ->toContain('color: red');
    });
});

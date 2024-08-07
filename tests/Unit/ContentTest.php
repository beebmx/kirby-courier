<?php

use Beebmx\KirbyCourier\Content;

it('can set a html content')
    ->expect(new Content('<h1>Hello World</h1>'))
    ->isNotEmpty()
    ->toBeTrue();

it('can have an empty content')
    ->expect(new Content)
    ->isEmpty()
    ->toBeTrue();

it('can returns toHtml content')
    ->expect(new Content('<h1>Hello World</h1>'))
    ->toHtml()
    ->toEqual('<h1>Hello World</h1>');

it('can instance as string content')
    ->expect(new Content('<h1>Hello World</h1>'))
    ->toEqual('<h1>Hello World</h1>');

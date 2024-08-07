<?php

use Kirby\CLI\CLI;

beforeEach(function () {
    App(options: [
        'beebmx.kirby-courier.path' => 'stubs',
    ]);
    $this->cli = new CLI;
});

it('exists a courier:theme command', function () {
    expect($this->cli->commands()['plugins'])
        ->toContain('courier:theme');
});

it('creates a courier mail message', function () {
    CLI::command('courier:theme', 'courier');

    $stubs = filesFor('stubs/themes');

    expect($stubs)
        ->toHaveCount(1)
        ->and($stubs[0])
        ->toEndWith('courier.css');
});

it('fakes a courier mail message creation', function () {
    CLI::command('courier:theme', 'courier', '--fake');

    expect(filesFor('stubs/css'))
        ->toHaveCount(0);
});

beforeEach(function () {
    deleteFor('stubs');
});

afterAll(function () {
    deleteFor('stubs');
});

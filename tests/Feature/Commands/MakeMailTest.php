<?php

use Kirby\CLI\CLI;

beforeEach(function () {
    App(options: [
        'beebmx.courier.path' => 'stubs',
    ]);
    $this->cli = new CLI;
});

it('exists a make:courier command', function () {
    expect($this->cli->commands()['plugins'])
        ->toContain('make:courier');
});

it('can be executed a make:courier command', function () {
    CLI::command('make:courier', 'message', '--fake');
})->throwsNoExceptions();

it('creates a courier mail message', function () {
    CLI::command('make:courier', 'message');

    $stubs = filesFor('stubs');

    expect($stubs)
        ->toHaveCount(1)
        ->and($stubs[0])
        ->toEndWith('message.php');
});

it('creates a courier mail message for blade', function () {
    CLI::command('make:courier', 'message', '--blade');

    $stubs = filesFor('stubs');

    expect($stubs)
        ->toHaveCount(1)
        ->and($stubs[0])
        ->toEndWith('message.blade.php');
});

it('fakes a courier mail message creation', function () {
    CLI::command('make:courier', 'message', '--fake');

    expect(filesFor('stubs'))
        ->toHaveCount(0);
});

it('exists a courier:make command as alias', function () {
    expect($this->cli->commands()['plugins'])
        ->toContain('courier:make');
});

it('can run an alias command', function () {
    CLI::command('courier:make', 'message', '--blade');

    $stubs = filesFor('stubs');

    expect($stubs)
        ->toHaveCount(1)
        ->and($stubs[0])
        ->toEndWith('message.blade.php');
});

beforeEach(function () {
    deleteFor('stubs');
});

afterAll(function () {
    deleteFor('stubs');
});

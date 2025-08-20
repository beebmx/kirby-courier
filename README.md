<p align="center"><a href="https://github.com/beebmx/kirby-courier" target="_blank" rel="noopener"><img src="https://github.com/beebmx/kirby-courier/blob/main/assets/logo.svg?raw=true" width="125" alt="Kirby Courier Logo"></a></p>

<p align="center">
<a href="https://github.com/beebmx/kirby-courier/actions"><img src="https://img.shields.io/github/actions/workflow/status/beebmx/kirby-courier/tests.yml?branch=main" alt="Build Status"></a>
<a href="https://packagist.org/packages/beebmx/kirby-courier"><img src="https://img.shields.io/packagist/dt/beebmx/kirby-courier" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/beebmx/kirby-courier"><img src="https://img.shields.io/packagist/v/beebmx/kirby-courier" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/beebmx/kirby-courier"><img src="https://img.shields.io/packagist/l/beebmx/kirby-courier" alt="License"></a>
</p>

# Courier for Kirby

`Courier` offers a convenient and painless solution for creating emails tailored for your `Kirby` website.
With `Courier`, you can streamline the process of email design and implementation for your site.

****

## Overview

- [1. Installation](#installation)
- [2. Usage](#usage)
- [3. Snippets](#snippets)
- [4. Console](#console)
- [5. Helper](#helper)
- [6. Challenge](#challenge)
- [7. Options](#options)
- [8. License](#license)
- [9. Credits](#credits)

## Installation

### Download

Download and copy this repository to `/site/plugins/kirby-courier`.

### Composer

```
composer require beebmx/kirby-courier
```

## Usage

`Courier` comes with two email `message` types, and you can customize them for your convenience.

![Kirby Courier example](https://raw.githubusercontent.com/beebmx/kirby-courier/main/assets/example.png)

### Notification message

The `Notification` message is the easiest way to send an email. You only need to use the `Beebmx\KirbyCourier\Notification\Message` class
in your `controller` or in your own implementation. Here's an example:

```php
use Beebmx\KirbyCourier\Notification\Message;

(new Message)
    ->to('john@doe.co')
    ->line('Welcome to Kirby Courier')
    ->action('Visit', 'https://beeb.mx')
    ->send()
```

#### Formating notification messages

You have several methods to customize your `Notification` message.
Here's an example with all the methods:

```php
use Beebmx\KirbyCourier\Notification\Message;

(new Message)
    ->to('john@doe.co')
    ->greeting('Hello friend!')
    ->line('Welcome to Kirby Courier')
    ->line('You can add more lines before an action')
    ->lines(['Multiple line 01', 'Multiple line 02'])
    ->lineIf($someCondition === true, 'You can add more lines before an action')
    ->linesIf($someCondition === false, ['Line 03', 'Line 04'])
    ->success()         // To set the action button as successful
    ->error()           // To set the action button as an error
    ->action('Action button', 'https://beeb.mx')
    ->line('You can add lines after an action')
    ->salutation('Good bye!')
    ->send()
```

> [!WARNING]
> You can only add one `action` per `Notification` message.

### Mail message

The `Mail` message is the easiest way to send an email if you need to customize all the body of your email, you only need to use the `Beebmx\KirbyCourier\Mail\Message` class
in your `controller` or in your own implementation. Here's an example:

```php
use Beebmx\KirbyCourier\Mail\Message;

(new Message)
    ->to('john@doe.co')
    ->template('marketing')
    ->send()
```

> [!NOTE]
> It's important that you set a `template` to display your own customization.
> Every template should be located in your `courier` directory.

#### Formating mail messages

To create your own `template` for your `mail` messages, you need to create a file in the default location for `Courier`.
Here's an example for a marketing message:

```php
/*** /site/template/courier/marketing.php ***/

<?php snippet('courier/message', slots: true) ?>
<?php slot('body') ?>
# Hello Courier

The body of your courier message.

<?php snippet('courier/button', ['url' => ''], slots: true) ?>
Button
<?php endsnippet() ?>

Thanks,
<?= site()->title() ?>
<?php endslot() ?>
<?php endsnippet() ?>
```

> [!NOTE]
> You can add content as `markdown` and it will be processed by `kirbytext`.

### Message methods

For both [Notifications](#notification-message) and [Mail](#mail-message) messages, there are shared methods.
Here's an example with all the methods:

```php
use Beebmx\KirbyCourier\Mail\Message;

(new Message)
    ->preset('contact')                     // The preset should be available in your config.php file
    ->from('no-reply@example.co')
    ->from('no-reply@example.co', 'Webmaster')  // You can add a name to from address
    ->to('john@doe.co')
    ->to('jane@doe.co')                     // You can add multiple recipients (TO)
    ->cc('john@doe.co')
    ->cc('jane@doe.co')                     // You can add multiple recipients (CC)
    ->bcc('john@doe.co')
    ->bcc('jane@doe.co')                    // You can add multiple recipients (BCC)
    ->replyTo('john@doe.co')
    ->subject('Thank you for your contact request')
    ->theme('dark')                         // The theme should be available in your themes
    ->data(['name' => 'John Doe', 'position' => 'CEO']) // All the data available for the template
    ->attach($page->file('image.jpg'))
    ->attach($page->file('file.pdf'))       // You can add multiple files
    ->attachMany([$page->file('file.pdf'), $page->file('file.jpg')])
    ->render()                              // Returns a Content instance to visualize
    ->send()                                // Trigger to send the email
```

If you want to previsualize your email, you can do it in any template. Here's an example:

```php
<?= (new Beebmx\KirbyCourier\Mail\Message)
    ->template('marketing')
    ->data(['name' => 'John Doe'])
    ->render()
    ->toHtml() ?>
```

> [!NOTE]
> The `render` method doesn't trigger any email, and doesn't require any email settings
> like `subject`, `from` or `to`.

## Snippets

For your convenience, `Courier` has some `snippets` to speed up your email building flow.
You can add them in your `courier template`.

> [!NOTE]
> You can create your own `snippets` if you want and apply it to the `courier/message` snippet.
> Just be sure to add it in the `slot('body')`.

### Button

```php
<?php snippet('courier/button', ['url' => ''], slots: true) ?>
Button
<?php endsnippet() ?>
```

### Panel

```php
<?php snippet('courier/panel', slots: true) ?>
This is a panel
<?php endsnippet() ?>
```

### Table

```php
<?php snippet('courier/table', slots: true) ?>
| Content      | Info          | Currency  |
| ------------ | :-----------: | --------: |
| Content 01   | Centered      | $100      |
| Content 02   | Is centered   | $150      |
<?php endsnippet() ?>
```

### Subcopy

```php
<?php snippet('courier/subcopy', slots: true) ?>
This is a subcopy
<?php endsnippet() ?>
```

## Console

If you are a [Kirby CLI](https://github.com/getkirby/cli) user, `Courier` also has you covered.
The `Courier` commands can help you create `Mail` messages faster or even create your own `Courier Theme`.

You can create your `Mail` template with:

```shell
$ kirby make:courier <template>
$ kirby courier:make <template>
```

> [!NOTE]
> `courier:make` is an alias of `make:courier`

Or may be you want to customize your messages with your own `theme` with:

```shell
$ kirby courier:theme <theme>
```

## Helper

If dealing with `namespaces` is hard, or you feel confused using the same `Message` class name,
you can simplify it with the `courier` helper:

For `Notification` message you can use:

```php
courier('notification')
    ->to('john@doe.co')
    ->line('This is a line')
    ->send()
```

For `Mail` message you can use:

```php
courier('mail')
    ->to('john@doe.co')
    ->template('marketing')
    ->send()
```

And of course, you can use it in a `template` to render it:

```php
<?= courier('mail')
        ->template('marketing')
        ->render()
        ->toHtml() ?>
```

## Challenge

`Courier` also provides a `challenge` message type to help you with your login and password reset flows.
First, you need to set the `challenge` in your `config.php` file:

```php
'auth' => [
    'challenges' => ['courier', 'email'],
],
```

Then, you can specify the `methods` you want to use in your `config.php` file:

```php
'auth' => [
    'challenges' => ['courier', 'email'],
    'methods'   => ['password', 'code'],
],
```

> [!WARNING]
> Right now only `code` and `password-reset` are supported for the `courier` challenge.

## Options

| Option                                               |       Default        |           Type            | Description                                                |
|:-----------------------------------------------------|:--------------------:|:-------------------------:|:-----------------------------------------------------------|
| beebmx.courier.logo                                  |         null         | `Closure`,`string`,`null` | Set your own logo in every message.                        |
| beebmx.courier.path                                  |       courier        |         `string`          | Set a path where the `templates` and `themes` are located. |
| beebmx.courier.from.address                          |          4           |           `int`           | Set the default `form.address` for every message.          |
| beebmx.courier.from.name                             |          2           |           `int`           | Set the default `form.name` for every message.             |
| beebmx.courier.message.greeting                      |        Hello!        |         `string`          | Set the default `message.greeting` for every message.      |
| beebmx.courier.message.rights                        | All rights reserved. |         `string`          | Set the default `message.rights` for every message.        |
| beebmx.courier.message.salutation                    |       Regards        |         `string`          | Set the default `message.salutation` for every message.    |
| beebmx.courier.message.subject                       | Message from courier |         `string`          | Set the default `message.subject` for every message.       |
| beebmx.courier.message.notify                        |                      |         `string`          | Set the default `message.notify` for every message.        |
| beebmx.courier.message.brand_name                    |         null         |         `?string`         | Set the default `message.brand_name` for every message.    |
| beebmx.courier.challenge.theme                       |       default        |         `?string`         | Set the default `theme` for your challenge message.        |
| beebmx.courier.challenge.greeting                    |         null         |         `?string`         | Set the `greeting` message for your challenge.             |
| beebmx.courier.challenge.email.login.before          |         null         |         `?string`         | Set the text before `code` for login message.              |
| beebmx.courier.challenge.email.login.after           |         null         |         `?string`         | Set the text after `code` for login message.               |
| beebmx.courier.challenge.email.password-reset.before |         null         |         `?string`         | Set the text before `code` for password-reset message.     |
| beebmx.courier.challenge.email.password-reset.after  |         null         |         `?string`         | Set the text after `code` for password-reset message.      |

Here's an example of a full use of the options from the `config.php` file:

```php
'beebmx.courier' => [
    'logo' => function() {
        return site()->file('logo.png');
    },
    'path' => 'courier',
    'from' => [
        'address' => 'hello@example.com',
        'name' => 'Example',
    ],
    'message' => [
        'greeting' => 'Hello friend!',
        'rights' => 'Copyrights.',
        'salutation' => 'Thanks',
        'subject' => 'Message from courier',
        'notify' => 'Si tienes problemas para hacer clic en el botón, copia y pega la URL de abajo en tu navegador web.',
        'brand_name' => null,
    ],
    'challenge' => [
        'theme' => 'your-own-theme',
        'greeting' => 'Hello friend!',
        'email' => [
            'login' => [
                'before' => 'Recently, a login attempt was made on your account. You have X minutes to complete the login.',
                'after' => 'DO NOT share this code with anyone. If you did not request this login, you can ignore this email.',
            ],
            'password-reset' => [
                'before' => 'Recently, a login attempt was made on your account. You have X minutes to complete the login.',
                'after' => 'DO NOT share this code with anyone. If you did not request this password reset, you can ignore this email.',
            ],
        ],
    ],
],
```

> [!WARNING]
> Since version `1.2.0`, `Courier` changes the plugin prefix from `beebmx.kirby-courier` to `beebmx.courier`.

## License

Licensed under the [MIT](LICENSE.md).

## Credits

`Courier` is inspired by the [Laravel Notifications](https://laravel.com/docs/master/notifications) and [Laravel Mail](https://laravel.com/docs/master/mail).

- Fernando Gutierrez [@beebmx](https://github.com/beebmx)
- Jonas Ceja [@jonatanjonas](https://github.com/jonatanjonas) `logo`
- [All Contributors](../../contributors)

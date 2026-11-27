# Changelog

All notable changes to `Courier` will be documented in this file.

## 2.0.1 - 2026-11-26

- Fixed the README badges, now served by GitHub and Packagist instead of shields.io.
- Removed Kirby 3 from the compatibility table, it was never supported.

## 2.0.0 - 2026-11-26

- Added support for Kirby 6. Kirby 4 and 5 sites should keep using `1.x`.
- Requires PHP `^8.4` (tested on 8.4, 8.5 and 8.6) and `tijsverkoyen/css-to-inline-styles` `^2.4` (older versions trigger PHP 8.4/8.5 deprecations).
- Migrated the Panel area to Vue 3, built with `kirbyup` 4.
- Migrated the `courier` auth challenge to the new `Kirby\Auth\Challenge\EmailChallenge` class.
- Added optional support for `beebmx/kirby-blade` `^7.0`, loaded only when it's installed, with Blade versions of the default templates and the anonymous `courier::` components.
- Declared the Kirby compatibility in `composer.json` (`extra.kirby.compatible`) and added a compatibility table to the README.
- Fixed the text version of an email containing the HTML snippets when it was rendered after the HTML version.
- Fixed the `password-reset` challenge using the `login` texts from the `beebmx.courier.challenge.email.*` options.
- Fixed the test email failing when the `panel.slug` option was not set, and its message ignoring the send result.
- Removed the SMTP credentials from the Panel area props.
- Fixed the test email API route ignoring the `courier` area permissions and the `panel` option.
- Fixed a closure `logo` that returns a field crashing every message; the logo accepts a URL, an asset, a file or a files field.
- Escaped the `Notification` values (greeting, lines, code, action and salutation) and the snippet attributes in the PHP templates, like the Blade templates. HTML in these values is now shown as text.
- Fixed the subcopy link of the Blade notification double-encoding URLs with query strings.
- Upgraded to Pest 5 and Kirby CLI 2.
- Updated the GitHub workflow: newest actions, PHP 8.4–8.6 with lowest and stable dependencies, plus code style, Blade (with `beebmx/kirby-blade` installed only for that job) and Panel build jobs.

## 1.5.0 - 2025-11-11

- Added support for plain text messages.

## 1.4.4 - 2025-11-10

- Added more options for panel area logo image.

## 1.4.3 - 2025-11-10

- Fixed an issue with panel logo image.

## 1.4.2 - 2025-11-05

- Display a correct image path in the test email panel.

## 1.4.1 - 2025-11-05

- Fixed an issue wit a SendTestEmail action.

## 1.4.0 - 2025-11-05

- Added a new panel `Area` to send test messages.

## 1.3.0 - 2025-11-03

- Dropped support for php@8.2
- Upgrade to Pest@4.x

## 1.2.0 - 2025-08-20

- Update plugin prefix to `beebmx.courier`

## 1.1.0 - 2025-07-30

- Added new challenge `courier`
- Added challenge `courier` support for `login`
- Added challenge `courier` support for `password-reset`

## 1.0.5 - 2025-05-13

- Fixed an issue with empty logo on the message template
- Fixed an issue with the logo in stubs

## 1.0.4 - 2025-04-14

- Added new code block template
- Added support for Kirby@5.x
- Upgrade testing to pest@3.x

## 1.0.3 - 2024-10-08

- Fixed an error when setting an attachment with strings

## 1.0.2 - 2024-10-08

- Only set the default form recipient if none was set

## 1.0.1 - 2024-10-08

- Fixed an error setting a from recipient

## 1.0.0 - 2024-08-07

- Initial release

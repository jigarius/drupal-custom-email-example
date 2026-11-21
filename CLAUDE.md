# custom_email_example

Example Drupal module that sends login/logout notification emails with the
Mailer Plus module (`drupal/symfony_mailer`). It exists to teach: keep the code
minimal and readable (KISS). Prefer fewer files and no abstractions that only
have one implementation.

## Branches

- `2.x` (default): Mailer Plus 2.x, Drupal `^11.3`. Active development.
- `1.x`: Mailer Plus 1.x (`@EmailBuilder` plugin, `email_factory` service).
  Frozen; it matches the 2023 article
  https://jigarius.com/blog/drupal-symfony-mailer.
- Releases are tags (`v1.x.y`, `v2.x.y`). `composer.json` has no `version` key
  on purpose; Composer takes the version from the tag.

## Documentation

The README only covers versions, installation and usage. Explanations of how
the code works belong in the articles on jigarius.com, not in the README.
Code comments may explain Mailer Plus concepts, since readers learn from them.

## Structure (2.x)

- `src/Mailer/CustomEmailExampleMailer.php`: Extends `ComponentMailerBase`.
  The `#[MailerInfo]` attribute declares the base tag `custom_email_example`
  and the sub-types `login` and `logout`.
- `custom_email_example.services.yml`: Registers the mailer by class name.
  Mailer Plus finds it through the attribute and injects `$baseTag`.
- `src/Hook/CustomEmailExampleHooks.php`: `#[Hook]` class (OOP hooks) with
  constructor injection. There is no `.module` file.
- `config/install/mailer_policy.mailer_policy.custom_email_example.*.yml`:
  Default subject and body. Requires the `mailer_policy` submodule.

## Mailer Plus 2.x gotchas

- Emails go through phases: init, build, post-render, post-send. Setters throw
  a `LogicException` when called in the wrong phase.
- The To address may only be set in init (right after `newEmail()`). Cc, Bcc,
  Reply-To and From may only be set from build onwards, e.g. in `build()`.
- `$email->getTag(1)` returns the sub-type (`login` or `logout`).
- To read the module's source, use `../../contrib/symfony_mailer`.

## Local environment

This module is cloned into a Drupal 11 project at `../../../..`, which runs on
DDEV (project `custom-email-example`). `ddev` commands work from this
directory.

- Site: https://custom-email-example.ddev.site (`admin` / `admin`)
- Mailpit (catches all mail): https://custom-email-example.ddev.site:8026
- Start: `ddev start`. After changing services or hooks: `ddev drush cr`.
- To check a change, log in and out on the site and look in Mailpit. Login
  sends one email; logout sends one with R2D2 on Cc.

## Code style

Drupal coding standards: 2-space indentation, 4 spaces in `composer.json`.
Use `declare(strict_types=1);`, typed signatures and constructor property
promotion.

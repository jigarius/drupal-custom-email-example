# Drupal Email Example

Example for sending emails using the [Mailer Plus](https://www.drupal.org/project/symfony_mailer) (formerly Drupal Symfony Mailer) module in Drupal.

## Versions

| Branch | Mailer Plus | Drupal  | Composer constraint |
|--------|-------------|---------|---------------------|
| `2.x`  | 2.x         | ^11.3   | `^2.0`              |
| `1.x`  | 1.x         | ^10, ^11| `^1.0`              |

The `1.x` branch accompanies the article [Sending Emails with Drupal Symfony Mailer](https://jigarius.com/blog/drupal-symfony-mailer) on [Jigarius.com](https://jigarius.com/). Mailer Plus 2.x replaced the API used in that article, so the code on this branch looks different.

## Installation

### Define repository

This package contains a Drupal module. To see the code in action, add these
lines to the project's `composer.json`.

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:jigarius/drupal-custom-email-example.git"
    }
  ]
}
```

You can then install this module with the following command:

    composer require jigarius/custom_email_example

### Enable module

Enable the example module from Drupal's administration interface or using
the Drush command:

    drush en -y custom_email_example

## Usage

If everything went well, you should receive email notifications when you:
- Log into your Drupal website.
- Log out of your Drupal website.

The subject and body of each email can be edited at _Configuration » System »
Mailer Plus » Policy_.

# Drupal Email Example

Example for sending emails using Symfony Mailer module in Drupal.

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

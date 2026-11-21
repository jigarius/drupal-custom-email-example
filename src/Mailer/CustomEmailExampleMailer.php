<?php

declare(strict_types=1);

namespace Drupal\custom_email_example\Mailer;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\symfony_mailer\Address;
use Drupal\symfony_mailer\Attribute\MailerInfo;
use Drupal\symfony_mailer\Component\ComponentMailerBase;
use Drupal\symfony_mailer\EmailInterface;
use Drupal\user\UserInterface;

/**
 * Mailer for the custom_email_example module.
 *
 * In Mailer Plus 2.x, each group of emails is sent by a dedicated "mailer"
 * service. Instead of calling a generic factory with an email type, calling
 * code gets this service from the container and calls a method on it.
 *
 * This replaces the @EmailBuilder plugin from Mailer Plus 1.x. The attribute
 * describes the emails this mailer sends:
 * - base_tag + sub_defs: Each email gets the tag "BASE_TAG.SUB_TYPE", e.g.
 *   "custom_email_example.login". The mailer policy config uses the same ID.
 * - required_config: The policy settings that must exist for each email.
 * - token_types: Tokens that can be used in the policy, e.g. [site:name].
 * - variables: Twig variables that can be used in the policy, e.g. {{ ip }}.
 *
 * The class is registered as a service in custom_email_example.services.yml.
 * Mailer Plus detects it from the attribute and injects the base tag.
 */
#[MailerInfo(
  base_tag: 'custom_email_example',
  label: new TranslatableMarkup('Email Example'),
  sub_defs: [
    'login' => new TranslatableMarkup('Login notification'),
    'logout' => new TranslatableMarkup('Logout notification'),
  ],
  required_config: ['email_subject', 'email_body'],
  token_types: ['site'],
  variables: [
    'ip' => new TranslatableMarkup('IP address of the user'),
    'account' => new TranslatableMarkup('The user account (logout only)'),
  ],
)]
class CustomEmailExampleMailer extends ComponentMailerBase {

  /**
   * Sends a login notification.
   *
   * Demonstrates sending an email with minimal setup.
   *
   * @param \Drupal\user\UserInterface $account
   *   The user who logged in.
   *
   * @return bool
   *   Whether the email was sent successfully.
   */
  public function login(UserInterface $account): bool {
    // ::newEmail() creates an email tagged "custom_email_example.login". The
    // subject and body come from the matching mailer policy.
    return $this->newEmail('login')
      ->setTo($account->getEmail())
      ->send();
  }

  /**
   * Sends a logout notification.
   *
   * Demonstrates sending an email with more customization.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user who logged out.
   *
   * @return bool
   *   Whether the email was sent successfully.
   */
  public function logout(AccountInterface $account): bool {
    return $this->newEmail('logout')
      ->setTo($account->getEmail())
      // This will be available as a Twig variable in the email body.
      ->setVariable('account', $account)
      ->send();
  }

  /**
   * {@inheritdoc}
   *
   * Called for every email this mailer sends, after ::send() is called and
   * before the email is rendered.
   *
   * Emails go through phases, and some setters only work in certain phases:
   * - init: Right after ::newEmail(). The "To" address must be set here.
   * - build: This method. Other addresses (Cc, Bcc, Reply-To, From) can be
   *   set from here onwards, but not earlier.
   */
  public function build(EmailInterface $email): void {
    $email->setVariable('ip', \Drupal::request()->getClientIp());

    // The tag is "custom_email_example.logout"; part 1 is the sub-type.
    if ($email->getTag(1) === 'logout') {
      $email->setCc(new Address('r2d2@example.com', 'R2D2'));
    }
  }

}

<?php

namespace Drupal\custom_email_example\Plugin\EmailBuilder;

use Drupal\symfony_mailer\EmailInterface;
use Drupal\symfony_mailer\Processor\EmailBuilderBase;
use Drupal\symfony_mailer\Processor\TokenProcessorTrait;

/**
 * Email Builder plug-in for the custom_email_example module.
 *
 * @EmailBuilder(
 *   id = "custom_email_example",
 *   sub_types = {
 *     "login" = @Translation("Login notification"),
 *     "logout" = @Translation("Logout notification"),
 *   },
 *   common_adjusters = {"email_subject", "email_body"},
 * )
 */
class ExampleEmailBuilder extends EmailBuilderBase {

  use TokenProcessorTrait;

  /**
   * Saves the parameters for a newly created email.
   *
   * After the second parameter, $emailFactory->sendTypedMail() takes a
   * variadic parameter named $params. The contents of $params are then
   * forwarded to ::createParams() after $email.
   *
   * Thus, if you send five parameters to ::sendTypedMail() or
   * ::newTypedMail(), the last three will be sent to ::createParams().
   *
   * @param \Drupal\symfony_mailer\EmailInterface $email
   *   The email to modify.
   * @param mixed $to
   *   The "to" addresses, see Address::convert().
   */
  public function createParams(EmailInterface $email, $to = NULL) {
    if ($to) {
      // For back-compatibility, allow $to to be NULL.
      $email->setParam('to', $to);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build(EmailInterface $email) {
    if ($to = $email->getParam('to')) {
      $email->setTo($to);
    }

    // - Set a parameter programmatically.
    //   The variable is used by the mailer policy which specifies the
    //   email title and body as defined in
    //   \Drupal\symfony_mailer\config\install\symfony_mailer.mailer_policy.custom_email_example.test.yml.
    $email->setVariable('ip', $_SERVER['REMOTE_ADDR']);
  }

}

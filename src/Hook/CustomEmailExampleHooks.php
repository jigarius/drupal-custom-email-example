<?php

declare(strict_types=1);

namespace Drupal\custom_email_example\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\custom_email_example\Mailer\CustomEmailExampleMailer;
use Drupal\user\UserInterface;

/**
 * Hook implementations for the custom_email_example module.
 */
class CustomEmailExampleHooks {

  use StringTranslationTrait;

  public function __construct(
    protected readonly CustomEmailExampleMailer $mailer,
    protected readonly MessengerInterface $messenger,
  ) {}

  /**
   * Implements hook_user_login().
   */
  #[Hook('user_login')]
  public function userLogin(UserInterface $account): void {
    if ($this->mailer->login($account)) {
      $this->messenger->addStatus($this->t(
        'Attempted sending %type notification to %email.',
        ['%type' => 'login', '%email' => $account->getEmail()],
      ));
    }
  }

  /**
   * Implements hook_user_logout().
   */
  #[Hook('user_logout')]
  public function userLogout(AccountInterface $account): void {
    if ($this->mailer->logout($account)) {
      $this->messenger->addStatus($this->t(
        'Attempted sending %type notification to %email.',
        ['%type' => 'logout', '%email' => $account->getEmail()],
      ));
    }
  }

}

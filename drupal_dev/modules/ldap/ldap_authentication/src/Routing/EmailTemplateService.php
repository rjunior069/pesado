<?php

namespace Drupal\ldap_authentication\Routing;

use Drupal\Core\Url;
use Drupal\user\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class EmailTemplateService.
 *
 * @package Drupal\ldap_authentication
 */
class EmailTemplateService implements EventSubscriberInterface {

  private $config;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('ldap_authentication.settings');
  }

  /**
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   */
  public function checkTemplate(GetResponseEvent $event) {
    if ($this->config->get('emailTemplateUsagePromptUser') === TRUE) {
      $this->checkForEmailTemplate();
    }

  }

  /**
   * Form submit callback to check for an email template and redirect if needed.
   */
  public static function checkForEmailTemplate() {
    if (self::profileNeedsUpdate()) {
      $url = Url::fromRoute('ldap_authentication.profile_update_form');
      $currentRoute = \Drupal::service('path.current')->getPath();
      if ($currentRoute != '/user/ldap-profile-update' && $currentRoute != '/user/logout') {
        $response = new RedirectResponse($url->toString());
        $response->send();

      }
    }
  }

  /**
   * Helper function that determines whether or not the user's profile
   * is valid or needs to be updated on login.
   *
   * Currently this only checks if mail is valid or not according to the
   * authentication settings.
   *
   * @return bool
   *   TRUE if the user's profile is valid, otherwise FALSE.
   */
  public static function profileNeedsUpdate() {
    $proxy = \Drupal::currentUser();
    $result = FALSE;

    // We only want non-anonymous and non-1 users.
    if ($proxy->id() != 1 && $proxy->isAuthenticated()) {
      $user = User::load($proxy->id());
      $regex = \Drupal::config('ldap_authentication.settings')
        ->get('emailTemplateUsagePromptRegex');

      $regex = '`' . $regex . '`i';
      if (preg_match($regex, $user->get('mail')->value)) {
        $result = TRUE;
      }
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkTemplate', 30];
    return $events;
  }

}

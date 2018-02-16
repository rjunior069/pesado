<?php 

namespace Drupal\authorization\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\externalauth\Event\ExternalAuthEvents;

class RegisterSubscriber implements EventSubscriberInterface {

  public function onRegister() {

  }

  /**
   * {@inheritdoc}
   */
  static public function getSubscribedEvents() {
    $events[ExternalAuthEvents::REGISTER][] = ['onRegister'];
    return $events;
  }

}

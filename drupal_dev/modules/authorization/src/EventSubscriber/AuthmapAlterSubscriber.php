<?php 

namespace Drupal\authorization\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\externalauth\Event\ExternalAuthEvents;

class AuthmapAlterSubscriber implements EventSubscriberInterface {

  public function onAuthmapAlter() {

  }

  /**
   * {@inheritdoc}
   */
  static public function getSubscribedEvents() {
    $events[ExternalAuthEvents::AUTHMAP_ALTER][] = ['onAuthmapAlter'];
    return $events;
  }

}

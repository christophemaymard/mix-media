<?php

namespace Drupal\allocine\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Represents an event subscriber related to imported data from Allocine.
 */
class ImportSubscriber implements EventSubscriberInterface {
  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents() {
    return $events = [];
    
    return $events;
  }
}

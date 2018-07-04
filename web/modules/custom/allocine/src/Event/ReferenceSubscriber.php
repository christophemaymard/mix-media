<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\ContentTypeManager;
use Drupal\allocine\Database;
use Drupal\allocine\TaxonomyManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Represents a subscriber.
 */
class ReferenceSubscriber implements EventSubscriberInterface {
  /**
   * @var Database
   */
  private $database;
  
  /**
   * The taxonomy manager.
   * @var TaxonomyManager
   */
  private $taxonomyManager;
  
  /**
   * The content type manager.
   * @var ContentTypeManager
   */
  private $contentTypeManager;
  
  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    
    return $events;
  }
  
  /**
   * Constructor.
   * 
   * @param   Database              $database
   * @param   TaxonomyManager       $taxonomyManager    The taxonomy manager.
   * @param   ContentTypeManager    $contentTypeManager The content type manager.
   */
  public function __construct(
    Database $database, 
    TaxonomyManager $taxonomyManager,
    ContentTypeManager $contentTypeManager) {
    $this->database = $database;
    $this->taxonomyManager = $taxonomyManager;
    $this->contentTypeManager = $contentTypeManager;
  }
}

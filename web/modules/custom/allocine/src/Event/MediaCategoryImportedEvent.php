<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\WebService\Data\MediaCategory;
use Symfony\Component\EventDispatcher\Event;

/**
 * The allocine.media_category.imported event is dispatched each time a 
 * category of media is imported from Allocine.
 */
class MediaCategoryImportedEvent extends Event {
  /**
   * The name of the event.
   */
  const NAME = 'allocine.media_category.imported';
  
  /**
   * The importing media category.
   * @var MediaCategory
   */
  private $mediaCategory;
  
  /**
   * Constructor.
   * 
   * @param   MediaCategory $mediaCategory
   */
  public function __construct(MediaCategory $mediaCategory) {
    $this->mediaCategory = $mediaCategory;
  }
  
  /**
   * Returns the media category being imported.
   * 
   * @return  MediaCategory
   */
  public function getMediaCategory() {
    return $this->mediaCategory;
  }
}

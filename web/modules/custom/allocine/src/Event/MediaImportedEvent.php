<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\WebService\Data\Media;
use Symfony\Component\EventDispatcher\Event;

/**
 * The allocine.media.imported event is dispatched each time a media is 
 * imported from Allocine.
 */
class MediaImportedEvent extends Event {
  /**
   * The name of the event.
   */
  const NAME = 'allocine.media.imported';
  
  /**
   * The importing media (should be a child of Media class).
   * @var Media
   */
  private $media;
  
  /**
   * Constructor.
   * 
   * @param   Media $media
   */
  public function __construct(Media $media) {
    $this->media = $media;
  }
  
  /**
   * Returns the media being imported.
   * 
   * @return  Media
   */
  public function getMedia() {
    return $this->media;
  }
}

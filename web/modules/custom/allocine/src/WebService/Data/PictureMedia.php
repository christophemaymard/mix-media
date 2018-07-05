<?php

namespace Drupal\allocine\WebService\Data;

/**
 * Represents the picture media data.
 */
class PictureMedia extends Media {
  /**
   * The media type.
   */
  const MEDIA_TYPE = 'picture';
  
  /**
   * The title.
   * @var string
   */
  public $title;
  
  /**
   * The URL of the picture.
   * @var string
   */
  public $url;
  
  /**
   * The width of the picture.
   * @var int
   */
  public $width;
  
  /**
   * The height of the picture.
   * @var int
   */
  public $height;
  
  /**
   * The copyright.
   * @var string|NULL
   */
  public $copyright;
}

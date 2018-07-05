<?php

namespace Drupal\allocine\WebService\Data;

/**
 * Represents the media data.
 */
class Media {
  /**
   * The media type.
   * @var string
   */
  public $type;
  
  /**
   * The code.
   * @var int
   */
  public $code;
  
  /**
   * The category.
   * @var MediaCategory
   */
  public $category;
}

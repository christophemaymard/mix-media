<?php

namespace Drupal\allocine\WebService\Data;

/**
 * Represents the movie data.
 */
class Movie {
  /**
   * The code.
   * @var int
   */
  public $code;
  
  /**
   * The title.
   * @var string
   */
  public $title;
  
  /**
   * The release date.
   * @var \DateTime|NULL
   */
  public $releaseDate;
  
  /**
   * The runtime.
   * @var int
   */
  public $runtime;
  
  /**
   * The runtime.
   * @var int
   */
  public $synopsis;
  
  /**
   * The nationalities.
   * @var Country[]
   */
  public $nationalities = [];
  
  /**
   * The genres.
   * @var Genre[]
   */
  public $genres = [];
}
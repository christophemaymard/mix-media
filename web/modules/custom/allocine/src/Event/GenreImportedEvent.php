<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\WebService\Data\Genre;
use Symfony\Component\EventDispatcher\Event;

/**
 * The allocine.genre.imported event is dispatched each time a genre is 
 * imported from Allocine.
 */
class GenreImportedEvent extends Event {
  /**
   * The name of the event.
   */
  const NAME = 'allocine.genre.imported';
  
  /**
   * The importing genre.
   * @var Genre
   */
  private $genre;
  
  /**
   * Constructor.
   * 
   * @param   Genre $genre
   */
  public function __construct(Genre $genre) {
    $this->genre = $genre;
  }
  
  /**
   * Returns the genre being imported.
   * 
   * @return  Genre
   */
  public function getGenre() {
    return $this->genre;
  }
}

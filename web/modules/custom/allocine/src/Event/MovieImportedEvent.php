<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\WebService\Data\Movie;
use Symfony\Component\EventDispatcher\Event;

/**
 * The allocine.movie.imported event is dispatched each time a movie is 
 * imported from Allocine.
 */
class MovieImportedEvent extends Event {
  /**
   * The name of the event.
   */
  const NAME = 'allocine.movie.imported';
  
  /**
   * The importing movie.
   * @var Movie
   */
  private $movie;
  
  /**
   * Constructor.
   * 
   * @param   Movie $movie
   */
  public function __construct(Movie $movie) {
    $this->movie = $movie;
  }
  
  /**
   * Returns the movie being imported.
   * 
   * @return  Movie
   */
  public function getMovie() {
    return $this->movie;
  }
}

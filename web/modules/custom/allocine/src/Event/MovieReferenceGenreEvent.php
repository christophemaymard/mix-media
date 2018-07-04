<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\WebService\Data\Genre;
use Drupal\allocine\WebService\Data\Movie;
use Symfony\Component\EventDispatcher\Event;

/**
 * The allocine.movie.reference.genre event is dispatched each time a movie 
 * references a set of genres.
 */
class MovieReferenceGenreEvent extends Event {
  /**
   * The name of the event.
   */
  const NAME = 'allocine.movie.reference.genre';
  
  /**
   * The movie.
   * @var Movie
   */
  private $movie;
  
  /**
   * The set of genres.
   * @var Genre[]
   */
  private $genres = [];
  
  /**
   * Constructor.
   * 
   * @param   Movie     $movie
   * @param   Genre[]   $genres
   */
  public function __construct(Movie $movie, array $genres) {
    $this->movie = $movie;
    $this->genres = $genres;
  }
  
  /**
   * Returns the movie.
   * 
   * @return  Movie
   */
  public function getMovie() {
    return $this->movie;
  }
  
  /**
   * Returns the set of genres.
   * 
   * @return  Genre[]
   */
  public function getGenres() {
    return $this->genres;
  }
}

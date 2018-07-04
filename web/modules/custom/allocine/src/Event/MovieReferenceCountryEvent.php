<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\WebService\Data\Country;
use Drupal\allocine\WebService\Data\Movie;
use Symfony\Component\EventDispatcher\Event;

/**
 * The allocine.movie.reference.country event is dispatched each time a 
 * movie references a set of countries.
 */
class MovieReferenceCountryEvent extends Event {
  /**
   * The name of the event.
   */
  const NAME = 'allocine.movie.reference.country';
  
  /**
   * The movie.
   * @var Movie
   */
  private $movie;
  
  /**
   * The set of countries.
   * @var Country[]
   */
  private $countries = [];
  
  /**
   * Constructor.
   * 
   * @param   Movie     $movie
   * @param   Country[] $countries
   */
  public function __construct(Movie $movie, array $countries) {
    $this->movie = $movie;
    $this->countries = $countries;
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
   * Returns the set of countries.
   * 
   * @return  Country[]
   */
  public function getCountries() {
    return $this->countries;
  }
}

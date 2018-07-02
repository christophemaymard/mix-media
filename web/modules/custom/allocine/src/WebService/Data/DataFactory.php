<?php

namespace Drupal\allocine\WebService\Data;

/**
 * Represents the factory of data.
 * 
 * Ths class acts as an abstract factory of data related to movies from 
 * Allocine web site. It creates data from the API web service responses.
 */
class DataFactory {
  /**
   * Creates a Movie data from the specified \stdClass instance.
   * 
   * @param   \stdClass   $movie
   * @return  Movie   The created Movie data.
   */
  public function createMovie(\stdClass $movie) {
    $data = new Movie();
    $data->code = $movie->code;
    $data->title = $movie->title;
    $data->releaseDate = \DateTime::createFromFormat('Y-m-d', $movie->release->releaseDate);
    $data->runtime = $movie->runtime;
    $data->synopsis = $movie->synopsis;
    
    foreach ($movie->genre as $genre) {
      $data->genres[] = $this->createGenre($genre);
    }
    
    foreach ($movie->nationality as $country) {
      $data->nationalities[] = $this->createCountry($country);
    }
    
    return $data;
  }
  
  /**
   * Creates a Genre data from the specified \stdClass instance.
   * 
   * @param   \stdClass   $genre
   * @return  Genre   The created Genre data.
   */
  public function createGenre(\stdClass $genre) {
    $data = new Genre();
    $data->code = $genre->code;
    $data->name = $genre->{'$'};
    
    return $data;
  }
  
  /**
   * Creates a Country data from the specified \stdClass instance.
   * 
   * @param   \stdClass   $country
   * @return  Country   The created Country data.
   */
  public function createCountry(\stdClass $country) {
    $data = new Country();
    $data->code = $country->code;
    $data->name = $country->{'$'};
    
    return $data;
  }
}
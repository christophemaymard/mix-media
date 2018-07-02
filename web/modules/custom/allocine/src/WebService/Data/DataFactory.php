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
}
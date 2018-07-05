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
    
    $data->medias = $this->createMovieMedias($movie);
    
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
  
  /**
   * Creates a set of Media  data from the specified Movie \stdClass instance.
   * 
   * @param   \stdClass $movie
   * @return  Media[]   An indexed array of the created Media data.
   */
  public function createMovieMedias(\stdClass $movie) {
    $medias = [];
    
    foreach ($movie->media as $media) {
      // Only process picture media.
      if ($media->class != 'picture') {
        continue;
      }
      
      $medias[] = $this->createPictureMedia($media);
    }
    
    return $medias;
  }
  
  /**
   * Creates a PictureMedia data from the specified \stdClass instance.
   * 
   * @param   \stdClass   $media
   * @return  PictureMedia  The created PictureMedia data.
   */
  public function createPictureMedia(\stdClass $media) {
    $data = new PictureMedia();
    $data->type = PictureMedia::MEDIA_TYPE;
    $data->code = $media->rcode;
    $data->title = $media->title;
    $data->url = $media->thumbnail->href;
    $data->width = $media->width;
    $data->height = $media->height;
    
    if (property_exists($media, 'copyrightHolder') && '' != $media->copyrightHolder) {
      $data->copyright = $media->copyrightHolder;
    }
    
    $category = new MediaCategory();
    $category->code = $media->type->code;
    $category->name = $media->type->{'$'};
    
    $data->category = $category;
    
    return $data;
  }
}
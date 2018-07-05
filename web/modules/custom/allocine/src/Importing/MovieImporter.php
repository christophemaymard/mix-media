<?php

namespace Drupal\allocine\Importing;

use Drupal\allocine\Event\CountryImportedEvent;
use Drupal\allocine\Event\GenreImportedEvent;
use Drupal\allocine\Event\MediaCategoryImportedEvent;
use Drupal\allocine\Event\MovieImportedEvent;
use Drupal\allocine\Event\MovieReferenceCountryEvent;
use Drupal\allocine\Event\MovieReferenceGenreEvent;
use Drupal\allocine\WebService\Client;
use Drupal\allocine\WebService\QueryBuilder;
use Drupal\allocine\WebService\Data\Movie;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Represents a processor that imports the data for a movie from Allocine.
 */
class MovieImporter {
  /**
   * @var Client
   */
  private $client;
  
  /**
   * @var EventDispatcherInterface
   */
  private $dispatcher;
  
  /**
   * Constructor.
   * 
   * @param   Client                    $client 
   * @param   EventDispatcherInterface  $dispatcher The event dispatcher.
   */
  public function __construct(
    Client $client, 
    EventDispatcherInterface $dispatcher) {
    $this->client = $client;
    $this->dispatcher = $dispatcher;
  }
  
  /**
   * Imports the movie with the specified code from Allocine web service.
   * 
   * @param   int $code The code of the movie to import.
   * @return  Movie
   */
  public function importMovie($code) {
    // Initialize the query builder.
    $qb = new QueryBuilder();
    $qb
      ->setCode($code)
      ->setProfileLarge();
    
    // Retrieves the informations of the movie from the web service.
    $movie = $this->client->getMovie($qb);
    
    // Imports countries.
    foreach ($movie->nationalities as $country) {
      $this->dispatcher->dispatch(CountryImportedEvent::NAME, new CountryImportedEvent($country));
    }
    
    // Imports genres.
    foreach ($movie->genres as $genre) {
      $this->dispatcher->dispatch(GenreImportedEvent::NAME, new GenreImportedEvent($genre));
    }
    
    // Imports the movie.
    $this->dispatcher->dispatch(MovieImportedEvent::NAME, new MovieImportedEvent($movie));
    
    // 
    $this->dispatcher->dispatch(
      MovieReferenceCountryEvent::NAME, 
      new MovieReferenceCountryEvent($movie, $movie->nationalities)
    );
    
    // 
    $this->dispatcher->dispatch(
      MovieReferenceGenreEvent::NAME, 
      new MovieReferenceGenreEvent($movie, $movie->genres)
    );
    
    foreach ($movie->medias as $media) {
      // Imports media category.
      $this->dispatcher->dispatch(
        MediaCategoryImportedEvent::NAME, 
        new MediaCategoryImportedEvent($media->category)
      );
    }
    
    return $movie;
  }
}

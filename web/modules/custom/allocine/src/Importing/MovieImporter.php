<?php

namespace Drupal\allocine\Importing;

use Drupal\allocine\Event\CountryImportedEvent;
use Drupal\allocine\Event\GenreImportedEvent;
use Drupal\allocine\WebService\Client;
use Drupal\allocine\WebService\QueryBuilder;
use Drupal\allocine\WebService\Data\Movie;
use Drupal\Core\Entity\EntityTypeManagerInterface;
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
   * @var EntityTypeManagerInterface
   */
  private $etm;
  
  /**
   * @var EventDispatcherInterface
   */
  private $dispatcher;
  
  /**
   * Constructor.
   * 
   * @param   Client                        $client 
   * @param   EntityTypeManagerInterface    $etm        The entity type manager used.
   * @param   EventDispatcherInterface      $dispatcher The event dispatcher.
   */
  public function __construct(
    Client $client, 
    EntityTypeManagerInterface $etm,
    EventDispatcherInterface $dispatcher) {
    $this->client = $client;
    $this->etm = $etm;
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
    
    return $movie;
  }
}

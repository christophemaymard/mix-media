<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\ContentTypeManager;
use Drupal\allocine\Database;
use Drupal\allocine\TaxonomyManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Represents a subscriber.
 */
class ReferenceSubscriber implements EventSubscriberInterface {
  /**
   * @var Database
   */
  private $database;
  
  /**
   * The taxonomy manager.
   * @var TaxonomyManager
   */
  private $taxonomyManager;
  
  /**
   * The content type manager.
   * @var ContentTypeManager
   */
  private $contentTypeManager;
  
  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[MovieReferenceCountryEvent::NAME] = [
      ['onMovieReferenceCountries', 0],
    ];
    $events[MovieReferenceGenreEvent::NAME] = [
      ['onMovieReferenceGenres', 0],
    ];
    
    return $events;
  }
  
  /**
   * Constructor.
   * 
   * @param   Database              $database
   * @param   TaxonomyManager       $taxonomyManager    The taxonomy manager.
   * @param   ContentTypeManager    $contentTypeManager The content type manager.
   */
  public function __construct(
    Database $database, 
    TaxonomyManager $taxonomyManager,
    ContentTypeManager $contentTypeManager) {
    $this->database = $database;
    $this->taxonomyManager = $taxonomyManager;
    $this->contentTypeManager = $contentTypeManager;
  }
  
  /**
   * Actions when a allocine.movie.reference.country event is dispatched.
   * 
   * @param   MovieReferenceCountryEvent  $event  The event to process.
   */
  public function onMovieReferenceCountries(MovieReferenceCountryEvent $event) {
    // Retrieves the node of the movie.
    $movieNid = $this->database->getMovieNodeIdByCode($event->getMovie()->code);
    $movieNode = $this->contentTypeManager->getMovieContentTypeByNid($movieNid);
    
    // Retrieves the term IDs.
    $countryTids = [];
    
    foreach ($event->getCountries() as $country) {
      $countryTids[] = $this->database->getCountryTermIdByCode($country->code);
    }
    
    // Updates the country references.
    $movieNode->set('field_movie_countries', $countryTids);
    $movieNode->save();
  }
  
  /**
   * Actions when a allocine.movie.reference.genre event is dispatched.
   * 
   * @param   MovieReferenceGenreEvent  $event  The event to process.
   */
  public function onMovieReferenceGenres(MovieReferenceGenreEvent $event) {
    // Retrieves the node of the movie.
    $movieNid = $this->database->getMovieNodeIdByCode($event->getMovie()->code);
    $movieNode = $this->contentTypeManager->getMovieContentTypeByNid($movieNid);
    
    // Retrieves the term IDs.
    $genreTids = [];
    
    foreach ($event->getGenres() as $genre) {
      $genreTids[] = $this->database->getGenreTermIdByCode($genre->code);
    }
    
    // Updates the genre references.
    $movieNode->set('field_movie_genres', $genreTids);
    $movieNode->save();
  }
}

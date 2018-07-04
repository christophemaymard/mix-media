<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\ContentTypeManager;
use Drupal\allocine\Database;
use Drupal\allocine\TaxonomyManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Represents an event subscriber related to imported data from Allocine.
 */
class ImportSubscriber implements EventSubscriberInterface {
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
    $events[CountryImportedEvent::NAME] = [
      ['onImportCountry', 0],
    ];
    $events[GenreImportedEvent::NAME] = [
      ['onImportGenre', 0],
    ];
    $events[MovieImportedEvent::NAME] = [
      ['onImportMovie', 0],
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
   * Actions when a CountryImportedEvent::NAME event is dispatched.
   * 
   * @param   CountryImportedEvent  $event  The event to process.
   */
  public function onImportCountry(CountryImportedEvent $event) {
    $country = $event->getCountry();
    
    // Determines whether a country is already mapped with a term.
    if (!$this->database->hasCountryByCode($country->code)) {
      // Creates a 'countries' term.
      $countryTerm = $this->taxonomyManager->createCountryTerm($country->name);
      
      // Creates the mapping between the Allocine country and the 'countries' term.
      $this->database->createCountry($country->code, $country->name, $countryTerm->id());
    }
  }
  
  /**
   * Actions when a GenreImportedEvent::NAME event is dispatched.
   * 
   * @param   GenreImportedEvent  $event  The event to process.
   */
  public function onImportGenre(GenreImportedEvent $event) {
    $genre = $event->getGenre();
    
    // Determines whether a genre is already mapped with a term.
    if (!$this->database->hasGenreByCode($genre->code)) {
      // Creates a 'genres' term.
      $genreTerm = $this->taxonomyManager->createGenreTerm($genre->name);
      
      // Creates the mapping between the Allocine genre and the 'genres' term.
      $this->database->createGenre($genre->code, $genre->name, $genreTerm->id());
    }
  }
  
  /**
   * Actions when a MovieImportedEvent::NAME event is dispatched.
   * 
   * @param   MovieImportedEvent  $event  The event to process.
   */
  public function onImportMovie(MovieImportedEvent $event) {
    $movie = $event->getMovie();
    
    // Determines whether a movie is already mapped with a content type.
    if (!$this->database->hasMovieByCode($movie->code)) {
      // Creates a 'movie' content type.
      $movieContentType = $this->contentTypeManager->createMovieContentType(
        $movie->title,
        $movie->synopsis,
        $movie->runtime,
        $movie->releaseDate
      );
      
      // Creates the mapping between the Allocine movie and the 'movie' content type.
      $this->database->createMovie($movie->code, $movie->title, $movieContentType->id());
    }
  }
}

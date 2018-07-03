<?php

namespace Drupal\allocine\Event;

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
   * {@inheritDoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[CountryImportedEvent::NAME] = [
      ['onImportCountry', 0],
    ];
    
    return $events;
  }
  
  /**
   * Constructor.
   * 
   * @param   Database          $database
   * @param   TaxonomyManager   $taxonomyManager    The entity type manager.
   */
  public function __construct(Database $database, TaxonomyManager $taxonomyManager) {
    $this->database = $database;
    $this->taxonomyManager = $taxonomyManager;
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
}

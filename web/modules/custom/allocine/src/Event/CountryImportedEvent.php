<?php

namespace Drupal\allocine\Event;

use Drupal\allocine\WebService\Data\Country;
use Symfony\Component\EventDispatcher\Event;

/**
 * The allocine.country.imported event is dispatched each time a country is 
 * imported from Allocine.
 */
class CountryImportedEvent extends Event {
  /**
   * The name of the event.
   */
  const NAME = 'allocine.country.imported';
  
  /**
   * The importing country.
   * @var Country
   */
  private $country;
  
  /**
   * Constructor.
   * 
   * @param   Country   $country
   */
  public function __construct(Country $country) {
    $this->country = $country;
  }
  
  /**
   * Returns the country being imported.
   * 
   * @return  Country
   */
  public function getCountry() {
    return $this->country;
  }
}
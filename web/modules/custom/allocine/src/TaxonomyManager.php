<?php

namespace Drupal\allocine;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\taxonomy\Entity\Term;

/**
 * Represents a taxonomy manager.
 */
class TaxonomyManager {
  /**
   * The entity type manager.
   * @var EntityTypeManagerInterface
   */
  private $entityTypeManager;
  
  /**
   * Constructor.
   * 
   * @param   EntityTypeManagerInterface  $entityTypeManager    The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }
  
  /**
   * Creates a term with the specified name into the 'countries' vocabulary.
   * 
   * @param   string    $name The name of the country.
   * @return  Term  The instance of the created term.
   */
  public function createCountryTerm($name) {
    // Creates the 'countries' term.
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    
    $term = $storage->create([
      'vid' => 'countries',
      'name' => $name,
    ]);
    
    $term->save();
    
    return $term;
  }
}

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
    return $this->createTerm('countries', $name);
  }
  
  /**
   * Creates a term with the specified name into the 'genres' vocabulary.
   * 
   * @param   string    $name The name of the genre.
   * @return  Term  The instance of the created term.
   */
  public function createGenreTerm($name) {
    return $this->createTerm('genres', $name);
  }
  
  /**
   * Creates a term with the specified name into the 'media_categories' 
   * vocabulary.
   * 
   * @param   string    $name The name of the media category.
   * @return  Term  The instance of the created term.
   */
  public function createMediaCategoryTerm($name) {
    return $this->createTerm('media_categories', $name);
  }
  
  /**
   * Creates a term into the specified vocabulary.
   * 
   * @param   string  $vid  The vocabulary ID.
   * @param   string  $name The name of the term to create.
   * @return  Term  The instance of the created term.
   */
  private function createTerm($vid, $name) {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    
    $term = $storage->create([
      'vid' => $vid,
      'name' => $name,
    ]);
    $term->save();
    
    return $term;
  }
}

<?php

namespace Drupal\allocine;

use Drupal\allocine\Exception\TaxonomyException;
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
   * @param   int       $tid  The term ID to set for the new term.
   * @param   string    $name The name of the media category.
   * @return  Term  The instance of the created term.
   */
  public function createMediaCategoryTerm($tid, $name) {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    
    $term = $storage->create([
      'vid' => 'media_categories',
      'tid' => $tid,
      'name' => $name,
    ]);
    $term->save();
    
    return $term;
  }
  
  /**
   * Returns the term with the specified ID from the 'media_categories' 
   * vocabulary.
   * 
   * @param   int $tid  The term ID.
   * @return  Term
   * 
   * @throws  TaxonomyException When the 'media_categories' term cannot be found.
   */
  public function getMediaCategoryById($tid) {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    
    // Retrieves the term with the speicified tid.
    $query = $storage->getQuery();
    $query->condition('vid',  'media_categories');
    $query->condition('tid', $tid);
    $tids = $query->execute();
    
    $term = $storage->load($tid);
    
    if (count($tids) != 1 || !$term) {
      throw new TaxonomyException(sprintf('The "media_categories" term with the term ID "%s" cannot be found.', $tid));
    }
    
    return $term;
  }
  
  /**
   * Indicates whether a term with the specified ID is stored in the 
   * 'media_categories'  vocabulary.
   * 
   * @param   int $tid  The term ID.
   * @return  bool
   */
  public function hasMediaCategoryTermById($tid) {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    
    // Retrieves the term with the speicified tid.
    $query = $storage->getQuery();
    $query->condition('vid',  'media_categories');
    $query->condition('tid', $tid);
    $tids = $query->execute();
    
    return count($tids) > 0;
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

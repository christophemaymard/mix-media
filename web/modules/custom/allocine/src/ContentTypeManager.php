<?php

namespace Drupal\allocine;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;

/**
 * Represents a manager of content types.
 */
class ContentTypeManager {
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
   * Creates a 'movie' content type.
   * 
   * @param   string          $title
   * @param   string          $synopsis
   * @param   int             $runtime
   * @param   \DateTime|NULL  $releaseDate
   * @return  Node  The instance of the created content type.
   */
  public function createMovieContentType($title, $synopsis, $runtime, $releaseDate) {
    $storage = $this->entityTypeManager->getStorage('node');
    
    // Creates the 'movie' node.
    $node = $storage->create(['type' => 'movie']);
    
    // Initialize the node.
    $node->set('title', $title);
    $node->set('field_movie_title', $title);
    $node->set('field_movie_synopsis', $synopsis);
    $node->set('field_movie_runtime', $runtime);
    
    if ($releaseDate instanceof \DateTime) {
      $node->set('field_movie_release_date', $releaseDate->format('Y-m-d'));
    }
    
    $node->save();
    
    return $node;
  }
}
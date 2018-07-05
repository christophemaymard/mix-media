<?php

namespace Drupal\allocine;

use Drupal\allocine\Exception\EntityException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

/**
 * Represents a manager of content types.
 */
class ContentTypeManager {
  /**
   * The name of the 'movie' content type.
   */
  const TYPE_MOVIE = 'movie';
  
  /**
   * The name of the 'picturemedia' content type.
   */
  const TYPE_PICTURE_MEDIA = 'picturemedia';
  
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
    $node = $storage->create(['type' => self::TYPE_MOVIE]);
    
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
  
  /**
   * Returns the 'movie' content type with the specified node ID.
   * 
   * @param   int   $nid  The node ID of the 'movie' content type to retrieve.
   * @return  Node
   * 
   * @throws  EntityException   When the 'movie' node cannot be found.
   */
  public function getMovieContentTypeByNid($nid) {
    $storage = $this->entityTypeManager->getStorage('node');
    
    // Retrieves the 'movie' node with the speicified nid.
    $query = $storage->getQuery();
    $query->condition('type',  self::TYPE_MOVIE);
    $query->condition('nid', $nid);
    $nids = $query->execute();
    
    $movie = $storage->load($nid);
    
    if (count($nids) != 1 || !$movie) {
      throw new EntityException(sprintf('The "movie" node with the node ID "%s" cannot be found.', $nid));
    }
    
    return $movie;
  }
  
  /**
   * Creates a 'picturemedia' content type.
   * 
   * @param   string        $title
   * @param   int           $imageTid       The term ID.
   * @param   int           $categoryTid    The term ID.
   * @param   string|NULL   $copyright      The copyright.
   * @return  Node  The instance of the created content type.
   */
  public function createPictureMediaContentType($title, $imageTid, $categoryTid, $copyright) {
    $storage = $this->entityTypeManager->getStorage('node');
    
    // Creates the 'picturemedia' node.
    $node = $storage->create(['type' => self::TYPE_PICTURE_MEDIA]);
    
    // Initialize the node.
    $node->set('title', $title);
    $node->set('field_picturemedia_title', $title);
    $node->set('field_picturemedia_category', $categoryTid);
    $node->set('field_picturemedia_image', [
      'target_id' => $imageTid,
      'alt' => $title,
      'title' => $title,
    ]);
    
    if ($copyright !== NULL) {
      $node->set('field_picturemedia_copyright', $copyright);
    }
    
    $node->save();
    
    return $node;
  }
}
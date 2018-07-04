<?php

namespace Drupal\allocine;

use Drupal\allocine\Exception\DatabaseException;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;

/**
 * Represents the wrapper for the Database API operations related to the 
 * tables created in the HOOK_schema().
 */
class Database {
  /**
   * The name of the table.
   */
  const TBL_COUNTRY = 'allocine_country';
  
  /**
   * The name of the table.
   */
  const TBL_GENRE = 'allocine_genre';
  
  /**
   * The name of the table.
   */
  const TBL_MOVIE = 'allocine_movie';
  
  /**
   * The database connection.
   * @var Connection
   */
  private $database;
  
  /**
   * The time.
   * @var TimeInterface
   */
  private $time;
  
  /**
   * Constructor.
   * 
   * @param   Connection    $database   The database connection.
   * @param   TimeInterface $time
   */
  public function __construct(Connection $database, TimeInterface $time) {
    $this->database = $database;
    $this->time = $time;
  }
  
  /**
   * Creates a record in the allocine_country table.
   * 
   * @param   int     $code The Allocine country code.
   * @param   string  $name The Allocine country name. 
   * @param   int     $tid  The term ID.
   * @return  
   */
  public function createCountry($code, $name, $tid) {
    $createdTime = $this->time->getCurrentTime();
    
    return $this->database
      ->insert(self::TBL_COUNTRY)
      ->fields([
        'ac_code' => $code,
        'ac_name' => $name,
        'tid' => $tid,
        'created' => $createdTime,
        'updated' => $createdTime,
      ])
      ->execute();
  }
  
  /**
   * Indicates whether a record with the specified Allocine country code is 
   * stored in the allocine_country table.
   * 
   * @param   int   $code   The Allocine country code.
   * @return  bool
   */
  public function hasCountryByCode($code) {
    $results = $this->database
      ->select(self::TBL_COUNTRY, 'ac')
      ->fields('ac', ['id'])
      ->condition('ac_code', $code)
      ->execute()
      ->fetchAll();
    
    return count($results) > 0;
  }
  
  /**
   * Returns the term ID of the country with the specified Allocine country 
   * code.
   * 
   * @param   int   $code   The Allocine code of the country to retrieve.
   * @return  int   The term ID of the country.
   * 
   * @throws  DatabaseException When the term ID cannot be determined for the country.
   */
  public function getCountryTermIdByCode($code) {
    $results = $this->database
      ->select(self::TBL_COUNTRY, 'ac')
      ->fields('ac', ['tid'])
      ->condition('ac_code', $code)
      ->execute()
      ->fetchAll();
    
    if (count($results) != 1) {
      throw new DatabaseException(sprintf(
        'The term ID, in the "countries" vocabulary, cannot be determined for the Allocine country code "%s".', 
        $code
      ));
    }
    
    $term = array_pop($results);
    
    return $term->tid;
  }
  
  /**
   * Creates a record in the allocine_country table that maps a genre from 
   * Allocine and a term from the 'genres' vocabulary.
   * 
   * @param   int     $code The Allocine genre code.
   * @param   string  $name The Allocine genre name. 
   * @param   int     $tid  The term ID; the term should belong to the 'genres' vocabulary.
   * @return  
   */
  public function createGenre($code, $name, $tid) {
    $createdTime = $this->time->getCurrentTime();
    
    return $this->database
      ->insert(self::TBL_GENRE)
      ->fields([
        'ac_code' => $code,
        'ac_name' => $name,
        'tid' => $tid,
        'created' => $createdTime,
        'updated' => $createdTime,
      ])
      ->execute();
  }
  
  /**
   * Indicates whether a record with the specified Allocine genre code is 
   * stored in the allocine_genre table.
   * 
   * @param   int   $code   The Allocine genre code.
   * @return  bool
   */
  public function hasGenreByCode($code) {
    $results = $this->database
      ->select(self::TBL_GENRE, 'ac')
      ->fields('ac', ['id'])
      ->condition('ac_code', $code)
      ->execute()
      ->fetchAll();
    
    return count($results) > 0;
  }
  
  /**
   * Creates a record in the allocine_movie table that maps a movie from 
   * Allocine and a 'movie' content type.
   * 
   * @param   int     $code     The Allocine movie code.
   * @param   string  $title    The Allocine movie title. 
   * @param   int     $nid      The node ID.
   * @return  
   */
  public function createMovie($code, $title, $nid) {
    $createdTime = $this->time->getCurrentTime();
    
    return $this->database
      ->insert(self::TBL_MOVIE)
      ->fields([
        'ac_code' => $code,
        'ac_title' => $title,
        'nid' => $nid,
        'created' => $createdTime,
        'updated' => $createdTime,
      ])
      ->execute();
  }
  
  /**
   * Indicates whether a record with the specified Allocine movie code is 
   * stored in the allocine_movie table.
   * 
   * @param   int   $code   The Allocine movie code.
   * @return  bool
   */
  public function hasMovieByCode($code) {
    $results = $this->database
      ->select(self::TBL_MOVIE, 'ac')
      ->fields('ac', ['id'])
      ->condition('ac_code', $code)
      ->execute()
      ->fetchAll();
    
    return count($results) > 0;
  }
  
  /**
   * Returns the node ID of the movie with the specified Allocine movie code.
   * 
   * @param   int   $code   The Allocine code of the movie to retrieve.
   * @return  int   The node ID of the movie.
   * 
   * @throws  DatabaseException When the node ID cannot be determined for the movie.
   */
  public function getMovieNodeIdByCode($code) {
    $results = $this->database
      ->select(self::TBL_MOVIE, 'ac')
      ->fields('ac', ['nid'])
      ->condition('ac_code', $code)
      ->execute()
      ->fetchAll();
    
    if (count($results) != 1) {
      throw new DatabaseException(sprintf(
        'The node ID cannot be determined for the Allocine movie code "%s".', 
        $code
      ));
    }
    
    $node = array_pop($results);
    
    return $node->nid;
  }
}

<?php

namespace Drupal\allocine;

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
}

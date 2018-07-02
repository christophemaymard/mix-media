<?php

namespace Drupal\allocine;

use Drupal\allocine\WebService\QueryBuilder;

/**
 * Represents the generator of URLs used in Allocine web site.
 */
class UrlGenerator {
  /**
   * The host of the web service API.
   * @var string
   */
  private $apiHost;
  
  /**
   * The identifier of the partner to use in the API of the web service.
   * @var string
   */
  private $apiPartner;
  
  /**
   * The salt used to compute the signature.
   * @var string
   */
  private $apiSigSalt;
  
  /**
   * Constructor.
   * 
   * @param   string  $apiHost    The host of the web service API.
   * @param   string  $apiPartner The identifier of the partner.
   * @param   string  $apiSigSalt The salt used to compute the signature.
   */
  public function __construct($apiHost, $apiPartner, $apiSigSalt) {
    $this->apiHost = $apiHost;
    $this->apiPartner = $apiPartner;
    $this->apiSigSalt = $apiSigSalt;
  }
  
  /**
   * Generates the URL used to retrieve movie informations from the API.
   * 
   * @param   QueryBuilder  $qb The query builder used.
   * @return  string
   */
  public function generateApiMovie(QueryBuilder $qb) {
    $qb
      ->setFormat('json')
      ->setPartner($this->apiPartner)
      ->setSed(date('Ymd'));
    
    // The 'sig' parameter must be excluded when computing the signature.
    $qb->removeSignature();
    
    // Computes the signature.
    $sig = base64_encode(sha1($this->apiSigSalt.$qb->getQueryString(), TRUE));
    
    $qb->setSignature($sig);
    
    return sprintf('%s/rest/v3/movie?%s', $this->apiHost, $qb->getQueryString());
  }
}
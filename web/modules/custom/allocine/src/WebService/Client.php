<?php

namespace Drupal\allocine\WebService;

use Drupal\allocine\UrlGenerator;
use Drupal\allocine\Exception\WebServiceException;
use Drupal\allocine\WebService\Data\DataFactory;
use Drupal\allocine\WebService\Data\Movie;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * Represents the client for the web service of Allocine.
 */
class Client {
  /**
   * The HTTP client.
   * @var GuzzleHttp\ClientInterface
   */
  private $client;
  
  /**
   * The generator of URLs.
   * @var UrlGenerator
   */
  private $urlGenerator;
  
  /**
   * The user-agent.
   * @var string
   */
  private $userAgent;
  
  /**
   * The Internet Protocol address.
   * @var string
   */
  private $ip;
  
  /**
   * The factory of data.
   * @var DataFactory
   */
  private $dataFactory;
  
  /**
   * Constructor.
   * 
   * @param   ClientInterface   $client
   * @param   UrlGenerator      $urlGenerator The generator of URLs.
   * @param   string            $userAgent
   * @param   string            $ip
   * @param   DataFactory       $dataFactory
   */
  public function __construct(
    ClientInterface $client, 
    UrlGenerator $urlGenerator,
    $userAgent,
    $ip,
    DataFactory $dataFactory
  ) {
    $this->client = $client;
    $this->urlGenerator = $urlGenerator;
    $this->userAgent = $userAgent;
    $this->ip = $ip;
    $this->dataFactory = $dataFactory;
  }
  
  /**
   * Returns the informations related to a movie.
   * 
   * @param   QueryBuilder  $qb
   * @return  Movie
   * 
   * @throws  ClientException     When a client error occured.
   * @throws  WebServiceException When the response is an invalid JSON.
   * 
   * @see     QueryBuilder::setCode         The code of the movie.
   * @see     QueryBuilder::setProfileLarge The verbosity of the informations.
   */
  public function getMovie(QueryBuilder $qb) {
    // Generates the URL.
    $url = $this->urlGenerator->generateApiMovie($qb);
    
    // Makes a request.
    $response = $this->request($url);
    
    // Decodes the message body.
    $body = json_decode((string)$response->getBody());
    
    if (!$body instanceof \stdClass || !property_exists($body, 'movie')) {
      throw new WebServiceException(sprintf('The response is an invalid JSON movie: %s.', print_r($body, TRUE)));
    }
    
    // Extracts the movie response.
    $movie = $body->movie;
    
    return $this->dataFactory->createMovie($movie);
  }
  
  /**
   * Makes a HTTP GET request to the specified URL.
   * 
   * @param   string  $url
   * @return  ResponseInterface
   * 
   * @throws  ClientException
   */
  private function request($url) {
    return $this->client->request('GET', $url, [
        'headers' => [
          'User-Agent' => $this->userAgent,
          'REMOTE_ADDR' => $this->ip,
          'HTTP_X_FORWARDED_FOR' => $this->ip,
        ],
    ]);
  }
}

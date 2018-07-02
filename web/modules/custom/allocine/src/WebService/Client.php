<?php

namespace Drupal\allocine\WebService;

use Drupal\allocine\UrlGenerator;
use Drupal\allocine\WebService\Data\DataFactory;
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

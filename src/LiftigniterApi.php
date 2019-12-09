<?php

namespace Drupal\liftigniter;

use Drupal\Core\Http\ClientFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Component\Serialization\Json;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Inventory.
 *
 * Class for Inventory REST API.
 */
class LiftigniterApi {

  /**
   * A HTTP Client.
   *
   * @var \Drupal\Core\Http\ClientFactory
   */
  protected $httpClient;

  /**
   * The Config Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * The API Key.
   *
   * @var string
   */
  private $apiKey;

  /**
   * Constructs an Inventory object.
   *
   * @param \Drupal\Core\Http\ClientFactory $http_client_factory
   *   The HTTP Client Manager Factory service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Config Factory.
   * @param \Psr\Log\LoggerInterface $logger
   *   The Logger Factory.
   */
  public function __construct(ClientFactory $http_client_factory, ConfigFactoryInterface $config_factory, LoggerInterface $logger) {
    $this->httpClient = $http_client_factory->fromOptions([
      'base_uri' => 'https://api.petametrics.com/v1/',
    ]);
    $this->config = $config_factory->get('liftigniter.settings');
    $this->apiKey = $this->config->get('api_key');
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function get($url = '') {
    return $this->request($url, [
      'headers' => [
        'x-api-key' => $this->apiKey,
      ],
    ]);
  }

  /**
   * {@inheritdoc}
   */
  protected function getRequestJson($data) {
    return [
      'json' => array_merge(['apiKey' => $this->apiKey], $data),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function send($url = '', $data = []) {
    return $this->request($url, $this->getRequestJson($data), 'POST');
  }

  /**
   * {@inheritdoc}
   */
  public function delete($url = '', $data = []) {
    return $this->request($url, $this->getRequestJson($data), 'DELETE');
  }

  /**
   * {@inheritdoc}
   */
  public function request($url = '', $options = [], $method = 'GET') {
    try {
      $response = $this->httpClient->request($method, $url, $options);
      $code = $response->getStatusCode();
      if ($code == 200) {
        $body = Json::decode($response->getBody()->getContents());
        if ($body && isset($body['status']) && isset($body['diagnosticMsg'])) {
          $this->logger->info($body['status'] . ' - ' . $body['diagnosticMsg']);
        }
        return $body;
      }
    }
    catch (RequestException $e) {
      $response = Json::decode($e->getResponse()->getBody(TRUE)->getContents());
      $this->logger->error($response['status'] . ' - ' . $response['diagnosticMsg']);
      return $response;
    }
  }

}

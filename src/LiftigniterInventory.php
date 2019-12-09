<?php

namespace Drupal\liftigniter;

use Drupal\Core\Messenger\MessengerInterface;

/**
 * Class LiftigniterInventory.
 *
 * Class for Inventory REST API.
 */
class LiftigniterInventory {

  /**
   * LiftIgniter API Service.
   *
   * @var \Drupal\liftigniter\LiftigniterApi
   */
  protected $liftigniterApi;

  /**
   * Constructs an Inventory object.
   *
   * @param \Drupal\liftigniter\LiftigniterApi $liftigniter_api
   *   The HTTP Client Manager Factory service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(LiftigniterApi $liftigniter_api, MessengerInterface $messenger) {
    $this->liftigniterApi = $liftigniter_api;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public function getInventory($url) {
    return $this->liftigniterApi->get('inventory/' . rawurlencode($url));
  }

  /**
   * {@inheritdoc}
   */
  public function sendInventory($items = [], $version = 1) {
    $response = $this->liftigniterApi->send('inventory', ['version' => $version, 'items' => $items]);
    if ($response['statusCode'] == 200) {
      $this->messenger->addStatus('Inventory is been sent to LiftIgniter.');
    }
    else {
      $this->messenger->addError('There is an error while sending inventory to LiftIgniter. Kindly see the logs.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function deleteInventory($items = []) {
    $response = $this->liftigniterApi->send('inventory', ['items' => $items]);
    if ($response['statusCode'] == 200) {
      $this->messenger->addStatus('Inventory is been deleted from LiftIgniter.');
    }
    else {
      $this->messenger->addError('There is an error while Deleting inventory in LiftIgniter. Kindly see the logs.');
    }
  }

}

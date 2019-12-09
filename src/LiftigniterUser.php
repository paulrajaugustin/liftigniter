<?php

namespace Drupal\liftigniter;

use Drupal\Core\Messenger\MessengerInterface;

/**
 * Class LiftigniterUser.
 *
 * Class for User REST API.
 */
class LiftigniterUser {

  /**
   * LiftIgniter API Service.
   *
   * @var \Drupal\liftigniter\LiftigniterApi
   */
  protected $liftigniterApi;

  /**
   * Constructs an User object.
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
  public function getUser($user_id) {
    return $this->liftigniterApi->get('user/' . $user_id);
  }

  /**
   * {@inheritdoc}
   */
  public function addUser($users = [], $version = 1) {
    $response = $this->liftigniterApi->send('user', ['version' => $version, 'users' => $users]);
    if ($response['statusCode'] == 200) {
      $this->messenger->addStatus('User is been added to LiftIgniter.');
    }
    else {
      $this->messenger->addError('There is an error while adding user to LiftIgniter. Kindly see the logs.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function deleteUser($users = []) {
    $response = $this->liftigniterApi->send('user', ['users' => $users]);
    if ($response['statusCode'] == 200) {
      $this->messenger->addStatus('User is been deleted from LiftIgniter.');
    }
    else {
      $this->messenger->addError('There is an error while Deleting user in LiftIgniter. Kindly see the logs.');
    }
  }

}

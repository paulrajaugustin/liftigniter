<?php

namespace Drupal\liftigniter_ui\Routing;

use Symfony\Component\Routing\Route;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Defines dynamic routes.
 */
class LiftigniterRoutes {

  /**
   * Constructs a \Drupal\liftigniter_ui\Routing\LiftigniterRoutes instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $routes = [];
    $liftigniter_config_entities = [
      'widget',
      'template',
    ];
    if (\Drupal::moduleHandler()->moduleExists('liftigniter_inventory')) {
      $liftigniter_config_entities[] = 'inventory_mapping';
    }
    foreach ($liftigniter_config_entities as $liftigniter_config_entity) {
      foreach (['list', 'add', 'edit', 'delete'] as $action) {
        $routes[$this->getRouteName($liftigniter_config_entity, $action)] = new Route(
          $this->getRoutePath($liftigniter_config_entity, $action),
          $this->getRouteDefaults($liftigniter_config_entity, $action),
          [
            '_permission'  => 'administer liftigniter',
          ],
          [
            '_admin_route' => TRUE,
          ]
        );
      }
    }
    return $routes;
  }

  /**
   * {@inheritdoc}
   */
  protected function getRouteName($liftigniter_config_entity, $action) {
    if ($action != 'list') {
      return "entity." . $liftigniter_config_entity . '.' . $action . '_form';
    }
    return "entity." . $liftigniter_config_entity . '.' . $action;
  }

  /**
   * {@inheritdoc}
   */
  protected function getRoutePath($liftigniter_config_entity, $action) {
    $path = '/admin/config/services/liftigniter/';
    if ($action == 'add') {
      return $path . $liftigniter_config_entity . '/' . $action;
    }
    elseif ($action != 'list') {
      return $path . $liftigniter_config_entity . '/{' . $liftigniter_config_entity . '}/' . $action;
    }
    return $path . $liftigniter_config_entity;
  }

  /**
   * {@inheritdoc}
   */
  protected function getRouteDefaults($liftigniter_config_entity, $action) {
    if ($action != 'list') {
      return [
        '_entity_form' => $liftigniter_config_entity . '.' . $action,
      ];
    }
    return [
      '_entity_list' => $liftigniter_config_entity,
      '_title' => (string) $this->entityTypeManager->getDefinition($liftigniter_config_entity)->getLabel(),
    ];
  }

}

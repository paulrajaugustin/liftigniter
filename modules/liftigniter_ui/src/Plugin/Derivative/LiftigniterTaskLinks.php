<?php

namespace Drupal\liftigniter_ui\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides dynamic local tasks for Liftigniter User Interface.
 */
class LiftigniterTaskLinks extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $liftigniter_config_entities = [
      'widget' => [
        [
          'route_name' => 'entity.widget.edit_form',
          'base_route' => 'entity.widget.edit_form',
          'title' => 'Edit',
          'weight' => 1,
        ],
        [
          'route_name' => 'entity.widget.delete_form',
          'base_route' => 'entity.widget.edit_form',
          'title' => 'Delete',
          'weight' => 2,
        ],
        [
          'route_name' => 'entity.widget.list',
          'base_route' => 'liftigniter.settings',
          'title' => 'Widgets',
          'weight' => 3,
        ],
      ],
      'template' => [
        [
          'route_name' => 'entity.template.edit_form',
          'base_route' => 'entity.template.edit_form',
          'title' => 'Edit',
          'weight' => 1,
        ],
        [
          'route_name' => 'entity.template.delete_form',
          'base_route' => 'entity.template.edit_form',
          'title' => 'Delete',
          'weight' => 2,
        ],
        [
          'route_name' => 'entity.template.list',
          'base_route' => 'liftigniter.settings',
          'title' => 'Templates',
          'weight' => 2,
        ],
      ],
    ];
    if (\Drupal::moduleHandler()->moduleExists('liftigniter_inventory')) {
      $liftigniter_config_entities['inventory_mapping'] = [
        [
          'route_name' => 'entity.inventory_mapping.edit_form',
          'base_route' => 'entity.inventory_mapping.edit_form',
          'title' => 'Edit',
          'weight' => 1,
        ],
        [
          'route_name' => 'entity.inventory_mapping.delete_form',
          'base_route' => 'entity.inventory_mapping.edit_form',
          'title' => 'Delete',
          'weight' => 2,
        ],
        [
          'route_name' => 'entity.inventory_mapping.list',
          'base_route' => 'liftigniter.settings',
          'title' => 'Inventory Mapping',
          'weight' => 4,
        ],
      ];
    }
    foreach ($liftigniter_config_entities as $liftigniter_config_entity) {
      foreach ($liftigniter_config_entity as $local_tasks) {
        $this->derivatives[$local_tasks['route_name']] = $local_tasks + $base_plugin_definition;
      }
    }
    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}

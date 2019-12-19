<?php

namespace Drupal\liftigniter_ui\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides dynamic Menu Links for Liftigniter User Interface.
 */
class LiftigniterMenuLinks extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $liftigniter_menu_links = [
      'widget' => [
        "title" => "Widgets",
        "route_name" => "entity.widget.list",
        "parent" => "liftigniter.admin_config",
        "description" => "Manage Widget",
        "weight" => 2,
      ],
      'template' => [
        "title" => "Templates",
        "route_name" => "entity.template.list",
        "parent" => "liftigniter.admin_config",
        "description" => "Manage Template.",
        "weight" => 1,
      ],
    ];
    if (\Drupal::moduleHandler()->moduleExists('liftigniter_inventory')) {
      $liftigniter_menu_links['inventory_mapping'] = [
        "title" => "Inventory Mapping",
        "route_name" => "entity.inventory_mapping.list",
        "parent" => "liftigniter.admin_config",
        "description" => "Manage Inventory Mapping.",
        "weight" => 3,
      ];
    }
    foreach ($liftigniter_menu_links as $liftigniter_menu_link) {
      $this->derivatives[$liftigniter_menu_link['route_name']] = $liftigniter_menu_link + $base_plugin_definition;
    }
    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}

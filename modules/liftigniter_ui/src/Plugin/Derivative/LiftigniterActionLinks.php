<?php

namespace Drupal\liftigniter_ui\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides dynamic Action Links for Liftigniter User Interface.
 */
class LiftigniterActionLinks extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $liftigniter_action_links = [
      "widget" => [
        "route_name" => "entity.widget.add_form",
        "title" => "Add Widget",
        "appears_on" => [
          "entity.widget.list",
        ],
      ],
      "template" => [
        "route_name" => "entity.template.add_form",
        "title" => "Add Template",
        "appears_on" => [
          "entity.template.list",
        ],
      ],
    ];
    if (\Drupal::moduleHandler()->moduleExists('liftigniter_inventory')) {
      $liftigniter_action_links['inventory_mapping'] = [
        "route_name" => "entity.inventory_mapping.add_form",
        "title" => "Add Inventory Mapping",
        "appears_on" => [
          "entity.inventory_mapping.list",
        ],
      ];
    }
    foreach ($liftigniter_action_links as $liftigniter_action_link) {
      $this->derivatives[$liftigniter_action_link['route_name']] = $liftigniter_action_link + $base_plugin_definition;
    }
    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}

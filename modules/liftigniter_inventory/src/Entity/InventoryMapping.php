<?php

namespace Drupal\liftigniter_inventory\Entity;

use Drupal\liftigniter_inventory\InventoryMappingInterface;
use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines a InventoryMapping configuration entity class.
 *
 * @ConfigEntityType(
 *   id = "inventory_mapping",
 *   label = @Translation("Inventory Mapping"),
 *   fieldable = FALSE,
 *   config_prefix = "inventory_mapping",
 *   admin_permission = "administer liftigniter",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "status" = "status"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "entity_type",
 *     "entity_bundle",
 *     "mapping",
 *     "status"
 *   }
 * )
 */
class InventoryMapping extends ConfigEntityBase implements InventoryMappingInterface {

  /**
   * The ID of the Inventory Mapping.
   *
   * @var string
   */
  public $id;

  /**
   * The label of Inventory Mapping.
   *
   * @var string
   */
  public $label;

  /**
   * The Entity type of Inventory Mapping.
   *
   * @var string
   */
  public $entity_type;

  /**
   * The Entity Bundle of Inventory Mapping.
   *
   * @var string
   */
  public $entity_bundle;

  /**
   * The twig of the Inventory Mapping.
   *
   * @var text
   */
  public $mapping;

  /**
   * The enabled/disabled status of the Inventory Mapping entity.
   *
   * @var bool
   */
  public $status = TRUE;

}

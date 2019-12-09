<?php

namespace Drupal\liftigniter\Entity;

use Drupal\liftigniter\WidgetInterface;
use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines a Widget configuration entity class.
 *
 * @ConfigEntityType(
 *   id = "widget",
 *   label = @Translation("Widget"),
 *   fieldable = FALSE,
 *   config_prefix = "widget",
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
 *     "widget",
 *     "selector",
 *     "no_of_items",
 *     "max",
 *     "opts",
 *     "ab_testing",
 *     "renderer",
 *     "track",
 *     "status"
 *   }
 * )
 */
class Widget extends ConfigEntityBase implements WidgetInterface {

  /**
   * The ID of the Widget.
   *
   * @var string
   */
  public $id;

  /**
   * The label of Widget.
   *
   * @var string
   */
  public $label;

  /**
   * The Widget name.
   *
   * @var string
   */
  public $widget;

  /**
   * The Selector of Widget.
   *
   * @var string
   */
  public $selector;

  /**
   * The No. og items of Widget.
   *
   * @var string
   */
  public $no_of_items;

  /**
   * The Maximum no. of items of Widget.
   *
   * @var int
   */
  public $max;

  /**
   * The custom options of Widget.
   *
   * @var text
   */
  public $opts;

  /**
   * The AB Testing settings for Widget.
   *
   * @var array[]
   */
  public $ab_testing;

  /**
   * The Renderer settings for Widget.
   *
   * @var array[]
   */
  public $renderer;

  /**
   * The Tracking settings for Widget.
   *
   * @var array[]
   */
  public $track;

  /**
   * The enabled/disabled status of the Widget entity.
   *
   * @var bool
   */
  public $status = TRUE;

}

<?php

namespace Drupal\liftigniter\Entity;

use Drupal\liftigniter\TemplateInterface;
use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines a Template configuration entity class.
 *
 * @ConfigEntityType(
 *   id = "template",
 *   label = @Translation("Template"),
 *   fieldable = FALSE,
 *   config_prefix = "template",
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
 *     "twig",
 *     "status"
 *   }
 * )
 */
class Template extends ConfigEntityBase implements TemplateInterface {

  /**
   * The ID of the Template.
   *
   * @var string
   */
  public $id;

  /**
   * The label of Template.
   *
   * @var string
   */
  public $label;

  /**
   * The twig of the Template.
   *
   * @var text
   */
  public $twig;

  /**
   * The enabled/disabled status of the Template entity.
   *
   * @var bool
   */
  public $status = TRUE;

}

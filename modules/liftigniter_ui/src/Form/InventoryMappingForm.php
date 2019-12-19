<?php

namespace Drupal\liftigniter_ui\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class InventoryMappingForm.
 *
 * Form class for adding/editing inventory_mapping config entities.
 */
class InventoryMappingForm extends EntityForm {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs new EntityAliasTypeDeriver.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);

    $inventory_mapping = $this->entity;

    if ($this->operation == 'edit') {
      $form['#title'] = $this->t('Edit Inventory Mapping: @label', ['@label' => $inventory_mapping->label]);
    }

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $inventory_mapping->label,
      '#description' => $this->t("Inventory Mapping."),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#default_value' => $inventory_mapping->id,
      '#disabled' => !$inventory_mapping->isNew(),
      '#machine_name' => [
        'source' => ['label'],
        'exists' => [$this, 'exist'],
      ],
    ];
    $content_entity_types = [];
    $entity_type_definations = $this->entityTypeManager->getDefinitions();
    foreach ($this->entityTypeManager->getDefinitions() as $entity_type_id => $entity_type) {
      if ($entity_type instanceof ContentEntityType && $entity_type->getBundleEntityType()) {
        $content_entity_types[$entity_type_id] = $entity_type->getLabel();
      }
    }
    $form['entity_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Entity type'),
      '#default_value' => $inventory_mapping->entity_type,
      '#options' => $content_entity_types,
      '#required' => TRUE,
      // '#limit_validation_errors' => [['type']],
      // '#submit' => ['::submitSelectType'],
      // '#executes_submit_callback' => TRUE,
      // '#ajax' => [
      //   'callback' => '::ajaxReplacePatternForm',
      //   'wrapper' => 'pathauto-pattern',
      //   'method' => 'replace',
      // ],
    ];
    $form['mapping'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Mapping'),
      '#required' => TRUE,
      '#default_value' => $inventory_mapping->mapping,
      '#description' => $this->t('Enter Mapping for this Inventory Mapping.'),
    ];
    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Status'),
      '#default_value' => $inventory_mapping->status,
      '#description' => $this->t("The status of the Inventory Mapping."),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $inventory_mapping = $this->entity;
    $status = $inventory_mapping->save();

    if ($status) {
      $this->messenger()->addMessage(
        $this->t('Saved the %label Inventory Mapping.', [
          '%label' => $inventory_mapping->label(),
        ])
      );
    }
    else {
      $this->messenger()->addMessage(
        $this->t('The %label Inventory Mapping was not saved.', [
          '%label' => $inventory_mapping->label(),
        ])
      );
    }
    $form_state->setRedirect('entity.inventory_mapping.list');

  }

  /**
   * Helper function to check whether an Inventory Mapping config entity exists.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('inventory_mapping')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}

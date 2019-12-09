<?php

namespace Drupal\liftigniter_ui\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TemplateForm.
 *
 * Form class for adding/editing template config entities.
 */
class TemplateForm extends EntityForm {

  /**
   * Constructs an ExampleForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   The entityTypeManager.
   */
  public function __construct(EntityTypeManager $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
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

    $template = $this->entity;

    if ($this->operation == 'edit') {
      $form['#title'] = $this->t('Edit Template: @label', ['@label' => $template->label]);
    }

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $template->label,
      '#description' => $this->t("Template."),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#default_value' => $template->id,
      '#disabled' => !$template->isNew(),
      '#machine_name' => [
        'source' => ['label'],
        'exists' => [$this, 'exist'],
      ],
    ];
    $form['twig'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Twig'),
      '#required' => TRUE,
      '#default_value' => $template->twig,
      '#description' => $this->t('Enter Twig for this Template.'),
    ];
    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Status'),
      '#default_value' => $template->status,
      '#description' => $this->t("The status of the Template."),
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
    $template = $this->entity;
    $status = $template->save();

    if ($status) {
      $this->messenger()->addMessage(
        $this->t('Saved the %label Template.', [
          '%label' => $template->label(),
        ])
      );
    }
    else {
      $this->messenger()->addMessage(
        $this->t('The %label Template was not saved.', [
          '%label' => $template->label(),
        ])
      );
    }
    $form_state->setRedirect('entity.template.list');

  }

  /**
   * Helper function to check whether an Template configuration entity exists.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('template')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}

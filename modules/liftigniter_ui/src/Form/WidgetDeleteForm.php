<?php

namespace Drupal\liftigniter_ui\Form;

use Drupal\Core\Entity\EntityDeleteForm;

/**
 * Form that handles the removal of Widget entities.
 */
class WidgetDeleteForm extends EntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete this Widget: @label?', ['@label' => $this->entity->label]);
  }

  /**
   * {@inheritdoc}
   */
  protected function getCancelRoute() {
    return 'entity.widget.list';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submit(array $form, array &$form_state) {
    // Delete and set message.
    $this->entity->delete();
    $this->messenger()->addMessage($this->t('The Widget @label has been deleted.', ['@label' => $this->entity->label]));
    $form_state['redirect_route'] = $this->getCancelRoute();
  }

}

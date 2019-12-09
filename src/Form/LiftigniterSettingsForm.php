<?php

namespace Drupal\liftigniter\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Liftigniter settings for this site.
 */
class LiftigniterSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'liftigniter_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'liftigniter.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('liftigniter.settings');

    $form['enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable LiftIgniter'),
      '#description' => $this->t('Enabling will add the LiftIgniter script and renders widget.'),
      '#default_value' => $config->get('enable'),
    ];

    $form['js_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('JS Key'),
      '#default_value' => $config->get('js_key'),
      '#description' => $this->t('Enter the LiftIgniter JS Key.'),
    ];
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#default_value' => $config->get('api_key'),
      '#description' => $this->t('Enter the LiftIgniter API Key.'),
    ];
    $form['custom_config'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Custom Configuration'),
      '#default_value' => $config->get('custom_config'),
      '#description' => $this->t('Enter custom configurations to be added in LiftIgniter script.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve and save the configuration.
    $this->configFactory->getEditable('liftigniter.settings')
      ->set('enable', $form_state->getValue('enable'))
      ->set('js_key', $form_state->getValue('js_key'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('custom_config', $form_state->getValue('custom_config'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}

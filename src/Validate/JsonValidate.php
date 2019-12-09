<?php

namespace Drupal\liftigniter\Validate;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Serialization\Json;

/**
 * Form API callback. Validate element value.
 */
class JsonValidate {

  /**
   * Validates given element.
   *
   * @param array $element
   *   The form element to process.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   * @param array $form
   *   The complete form structure.
   */
  public static function validate(array &$element, FormStateInterface $form_state, array &$form) {
    $value = $element['#value'];
    if ($value === '') {
      return;
    }
    $name = empty($element['#title']) ? $element['#parents'][0] : $element['#title'];
    $decoded_json = Json::decode($value);
    $valid_json = is_string($value) && is_array($decoded_json) && (json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE;
    if (!$valid_json) {
      $form_state
        ->setError($element, t('%name must be a valid json.', [
          '%name' => $name,
        ]));
    }
  }

}

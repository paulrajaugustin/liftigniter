<?php

namespace Drupal\liftigniter_ui\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WidgetForm.
 *
 * Form class for adding/editing widget config entities.
 */
class WidgetForm extends EntityForm {

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

    // Load templates.
    $templates = $this->entityTypeManager->getStorage('template')
      ->loadByProperties([
        'status' => TRUE,
      ]);
    $templates_options = array_map(function ($template) {
      return $template->label;
    }, $templates);

    $widget = $this->entity;

    if ($this->operation == 'edit') {
      $form['#title'] = $this->t('Edit Widget: @label', ['@label' => $widget->label]);
    }

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Widget Name'),
      '#maxlength' => 255,
      '#default_value' => $widget->label,
      '#description' => $this->t("Name of the Widget."),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#default_value' => $widget->id,
      '#disabled' => !$widget->isNew(),
      '#machine_name' => [
        'source' => ['label'],
        'exists' => [$this, 'exist'],
      ],
    ];
    $form['vertical_tabs'] = [
      '#type' => 'vertical_tabs',
    ];
    $form['widget_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Details'),
      // '#tree' => TRUE,
      '#group' => 'vertical_tabs',
    ];
    $form['widget_details']['widget'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Widget"),
      '#default_value' => $widget->widget,
      '#description' => $this->t("Widget to register in LiftIgniter"),
      '#required' => TRUE,
    ];
    $form['widget_details']['selector'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Selector"),
      '#default_value' => $widget->selector,
      '#description' => $this->t("Selector for the widget"),
      '#required' => TRUE,
    ];
    $form['widget_details']['no_of_items'] = [
      '#type' => 'radios',
      '#title' => $this->t("No. of Items."),
      '#options' => [
        'dynamic' => $this->t("Dynamic (Use Element length as Max)"),
        'static' => $this->t("Static"),
      ],
      '#default_value' => $widget->no_of_items,
      '#description' => $this->t("Number of Items to be requested by the widget"),
      '#required' => TRUE,
    ];
    $form['widget_details']['max'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum no. of items'),
      '#min' => 1,
      '#default_value' => $widget->max,
      '#description' => $this->t('Enter Maximum no. of items to be requested by this Widget.'),
      '#states' => [
        'visible' => [
          'input[name="no_of_items"]' => [
            'value' => 'static',
          ],
        ],
        'required' => [
          'input[name="no_of_items"]' => [
            'value' => 'static',
          ],
        ],
      ],
    ];
    $form['widget_details']['opts'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Custom Options for the widget'),
      '#default_value' => $widget->opts,
      '#description' => $this->t('Enter custom options (opts) for this Widget in Json Format.'),
      '#element_validate' => [
        [
          'Drupal\liftigniter\Validate\JsonValidate',
          'validate',
        ],
      ],
    ];
    $form['ab_testing'] = [
      '#type' => 'details',
      '#title' => $this->t('AB Testing'),
      '#tree' => TRUE,
      '#group' => 'vertical_tabs',
    ];
    $form['ab_testing']['enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable'),
      '#default_value' => $widget->ab_testing['enable'],
      '#description' => $this->t('Enabling will add AB testing for this widget.'),
    ];
    $form['ab_testing']['slice'] = [
      '#type' => 'number',
      '#title' => $this->t('Slice'),
      '#min' => 0,
      '#max' => 99,
      '#default_value' => $widget->ab_testing['slice'],
      '#description' => $this->t('Enter the slice value for AB Testing.'),
      '#states' => [
        'visible' => [
          'input[name="ab_testing[enable]"]' => [
            'checked' => TRUE,
          ],
        ],
        'required' => [
          'input[name="ab_testing[enable]"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];
    $form['renderer'] = [
      '#type' => 'details',
      '#title' => $this->t('Render'),
      '#tree' => TRUE,
      '#group' => 'vertical_tabs',
    ];
    $form['renderer']['method'] = [
      '#type' => 'radios',
      '#title' => $this->t('Rendering Method'),
      '#options' => [
        'all' => $this->t("Render all Inventories"),
        'each' => $this->t("Render Inventories one by one"),
      ],
      '#default_value' => $widget->renderer['method'],
      '#description' => $this->t('Choose the method to render this widget.'),
      '#required' => TRUE,
    ];
    $form['renderer']['selector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Selector'),
      '#default_value' => $widget->renderer['selector'],
      '#description' => $this->t('Enter the selector to render this widget.'),
      '#required' => TRUE,
    ];
    $form['renderer']['template'] = [
      '#type' => 'select',
      '#title' => $this->t('Template'),
      '#options' => $templates_options,
      '#default_value' => $widget->renderer['template'],
      '#description' => $this->t('Choose template to render this widget.'),
      '#required' => TRUE,
    ];

    // Tracking.
    $form['track'] = [
      '#type' => 'details',
      '#title' => $this->t('Tracking'),
      '#tree' => TRUE,
      '#group' => 'vertical_tabs',
    ];
    foreach (['li' => 'LI', 'base' => 'Base'] as $key => $value) {
      $form['track'][$key] = [
        '#type' => 'fieldset',
        '#title' => $this->t(':value Tracking', [':value' => $value]),
      ];
      $form['track'][$key]['enable'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Enable :value', [':value' => $value]),
        '#default_value' => $widget->track[$key]['enable'],
        '#description' => $this->t('Enable :value tracking.', [':value' => $value]),
      ];
      $form['track'][$key]['method'] = [
        '#type' => 'radios',
        '#title' => $this->t('Tracking Method'),
        '#options' => [
          'anchor' => $this->t("Anchor Tracking"),
          'non_anchor' => $this->t("Non-Anchor Tracking"),
        ],
        '#default_value' => $widget->track[$key]['method'],
        '#description' => $this->t('Choose the method for tracking this widget.'),
        '#states' => [
          'visible' => [
            'input[name="track[' . $key . '][enable]"]' => [
              'checked' => TRUE,
            ],
          ],
          'required' => [
            'input[name="track[' . $key . '][enable]"]' => [
              'checked' => TRUE,
            ],
          ],
        ],
      ];
      $form['track'][$key]['selector'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Selector'),
        '#default_value' => $widget->track[$key]['selector'],
        '#description' => $this->t('Enter the selector for tracking this widget.'),
        '#states' => [
          'visible' => [
            'input[name="track[' . $key . '][enable]"]' => [
              'checked' => TRUE,
            ],
          ],
          'required' => [
            'input[name="track[' . $key . '][enable]"]' => [
              'checked' => TRUE,
            ],
          ],
        ],
      ];
      $form['track'][$key]['debug'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Debug'),
        '#default_value' => $widget->track[$key]['debug'],
        '#description' => $this->t('Enabling will debug anchor tracking for this widget.'),
        '#states' => [
          'visible' => [
            'input[name="track[' . $key . '][enable]"]' => [
              'checked' => TRUE,
            ],
            'input[name="track[' . $key . '][method]"]' => [
              'value' => 'anchor',
            ],
          ],
        ],
      ];
    }

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Publish'),
      '#default_value' => $widget->status,
      '#description' => $this->t("The status of the Widget."),
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
    $widget = $this->entity;
    $status = $widget->save();

    if ($status) {
      $this->messenger()->addMessage(
        $this->t('Saved the %label Widget.', [
          '%label' => $widget->label(),
        ])
      );
    }
    else {
      $this->messenger()->addMessage(
        $this->t('The %label Widget was not saved.', [
          '%label' => $widget->label(),
        ])
      );
    }
    $form_state->setRedirect('entity.widget.list');

  }

  /**
   * Helper function to check whether an Widget configuration entity exists.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('widget')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}

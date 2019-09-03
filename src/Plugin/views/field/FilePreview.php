<?php

/**
 * @file
 * Definition of Drupal\d8views\Plugin\views\field\NodeTypeFlagger.
 */

namespace Drupal\views_file_preview\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("file_preview")
 */
class FilePreview extends FieldPluginBase {

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * Define the available options
   * @return array
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['image_style'] = array('default' => 'thumbnail');

    return $options;
  }

  /**
   * Provide the options form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $styles = ImageStyle::loadMultiple();
    $options = [];
    foreach ($styles as $key => $type) {
      $options[$key] = $type->label();
    }
    $form['image_style'] = array(
      '#title' => $this->t('Which image style should be used?'),
      '#type' => 'select',
      '#default_value' => $this->options['image_style'],
      '#options' => $options,
    );

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    $file = $values->_entity;
    $image_style = $this->options['image_style'];
    $render = [
      '#theme' => 'image_style',
      '#style_name' => $image_style,
      '#uri' => $file->getFileUri(),
    ];
    return $render;
  }
}

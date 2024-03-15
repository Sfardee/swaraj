<?php
/**
 * @file
 * Contains \Drupal\jarvis_vision\Form\CKEdiorConfigForm.
 */

namespace Drupal\jarvis_vision\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

class CKEdiorConfigForm extends ConfigFormBase {

  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jarvis_ckeditor_settings';
  }

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   * An array of configuration object names that are editable if called in
   * conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['config.jarvis_vision'];
  }

  /**
   * Form constructor.
   *
   * @param array $form
   * An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * The current state of the form.
   *
   * @return array
   * The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    global $base_url;

    $config = $this->config('config.jarvis_vision');

    $form['css_paths'] = array(
      '#type' => 'textarea',
      '#title' => t('CKEditor CSS file path:'),
      '#default_value' => $config->get('css_paths') ? $config->get('css_paths') : '',
      '#description' => t('If "Define CSS" was selected above, enter path to a CSS file or a list of CSS files separated by a comma.
Available tokens: %t (path to theme, eg: themes/garland).
Example: /themes/garland/css/style.css, %t/css/style.css')
    );


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   * An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $values = $form_state->getValues();

    $this->config('config.jarvis_vision')
      ->set('css_paths', $values['css_paths'])
      ->save();
    
  }


}
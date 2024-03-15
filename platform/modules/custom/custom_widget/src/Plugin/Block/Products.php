<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Products' block.
 *
 * @Block(
 *   id = "products",
 *   admin_label = @Translation("Products"),
 *   category = @Translation("Custom")
 * )
 */
class Products extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    $type = array(
      '' => '-Select-',
      'd' => 'Detail On Hover',
      'nd' => 'No Detail On Hover'
    );
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config['title'],
    ];
    $form['subtitle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subtitle'),
      '#default_value' => $config['subtitle'],
    ];
    $form['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#options' => $type,
      '#default_value' => $config['type'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('title', $form_state->getValue('title'));
    $this->setConfigurationValue('subtitle', $form_state->getValue('subtitle'));
    $this->setConfigurationValue('type', $form_state->getValue('type'));
  }

  public function build()
  {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $product = $data = $engine = array();
    $config = $this->getConfiguration();
    $data['title'] = isset($config['title']) ? $config['title'] : '';
    $data['subtitle'] = isset($config['subtitle']) ? $config['subtitle'] : '';
    $data['type'] = isset($config['type']) ? $config['type'] : '';
    $data['language'] = $language;
    
    $type_terms = \Drupal::entityManager()->getStorage('taxonomy_term')
    ->loadByProperties(['vid' => 'product_engine_power']);
    foreach ($type_terms as $type_term) {
      if ($type_term->hasTranslation($language)) {
        $type_term = $type_term->getTranslation($language);
      }
      $details = _getEngineProducts($type_term->id());
      if($details != NULL){
        $product[$type_term->label()] = $details;
        $engine[$type_term->id()] = $type_term->label();
      }
    }
    $data['engine'] = $engine;
    $data['product'] = $product;
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_products',
    );
  }
}
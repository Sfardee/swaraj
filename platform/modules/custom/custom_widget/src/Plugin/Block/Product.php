<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Product' block.
 *
 * @Block(
 *   id = "product",
 *   admin_label = @Translation("Product"),
 *   category = @Translation("Custom")
 * )
 */
class Product extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
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
    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text'),
      '#default_value' => $config['text'],
    ];
    $form['subtext'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subtext'),
      '#default_value' => $config['subtext'],
    ];
    $form['link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link to All Products'),
      '#default_value' => $config['link'],
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
    $this->setConfigurationValue('text', $form_state->getValue('text'));
    $this->setConfigurationValue('subtext', $form_state->getValue('subtext'));
    $this->setConfigurationValue('link', $form_state->getValue('link'));
  }

  public function build()
  {
    $product = $data = $engine = array();
    $config = $this->getConfiguration();
    $product['title'] = isset($config['title']) ? $config['title'] : '';
    $product['subtitle'] = isset($config['subtitle']) ? $config['subtitle'] : '';
    $product['text'] = isset($config['text']) ? $config['text'] : '';
    $product['subtext'] = isset($config['subtext']) ? $config['subtext'] : '';
    $product['link'] = isset($config['link']) ? $config['link'] : '';
    
    $type_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('product_engine_power');
    foreach ($type_terms as $type_term) {
      $term_product = _gettermproduct($type_term->tid, 3);
      if($term_product != ''){
        $data[$type_term->tid] = $term_product;
        $engine[$type_term->tid] = $type_term->name;
      }
    }
    $product['product'] = $data;
    $product['engine'] = $engine;
    
    return array(
      '#data' => $product,
      '#theme' => 'swaraj_product',
    );
  }
}
<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Search' block.
 *
 * @Block(
 *   id = "search",
 *   admin_label = @Translation("Search"),
 *   category = @Translation("Custom")
 * )
 */
class Search extends BlockBase
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
  }

  public function build()
  {
    $data = array();
    $config = $this->getConfiguration();
    $data['title'] = isset($config['title']) ? $config['title'] : '';
    $data['subtitle'] = isset($config['subtitle']) ? $config['subtitle'] : '';
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_search',
    );
  }
}
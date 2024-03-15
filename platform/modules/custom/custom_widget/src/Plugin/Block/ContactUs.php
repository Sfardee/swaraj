<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'ContactUs' block.
 *
 * @Block(
 *   id = "contactus",
 *   admin_label = @Translation("ContactUs"),
 *   category = @Translation("Custom")
 * )
 */
class ContactUs extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config['text'],
    ];
    $form['subtext'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#default_value' => $config['subtext'],
    ];
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('text', $form_state->getValue('text'));
    $this->setConfigurationValue('subtext', $form_state->getValue('subtext'));
  }

  public function build()
  {
    $state = array();
    $config = $this->getConfiguration();
    $data['text'] = isset($config['text']) ? $config['text'] : '';
    $data['subtext'] = isset($config['subtext']) ? $config['subtext'] : '';
    $query = \Drupal::database()->select('dealer_state', 'd');
    $query->fields('d', ['id','state', 'state_lat', 'state_long']);
    $query->orderBy('d.state');
    $results = $query->execute()->fetchAll();
    foreach($results as $val){
      $state[$val->id]['ucstate'] = ucfirst(strtolower($val->state));
      $state[$val->id]['state'] = $val->state;
      $state[$val->id]['cord'] = $val->state_lat . "," . $val->state_long;
    }
    $data['state'] = $state;
    $config = \Drupal::config('config.custom_widget');
    $data['google_map_api_key'] = !empty($config->get('google_map_api_key'))? $config->get('google_map_api_key'): 'AIzaSyB4i2QQFtyO87OCzxhi0TQxWqZzKVZjSp8'; 
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_contactus',
    );
  }
}
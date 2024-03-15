<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Contact' block.
 *
 * @Block(
 *   id = "near",
 *   admin_label = @Translation("Near Me"),
 *   category = @Translation("Custom")
 * )
 */
class Near extends BlockBase
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
    $form['maptext'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Map Title'),
      '#default_value' => $config['maptext'],
    ];
    $form['mapsubtext'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Map Description'),
      '#default_value' => $config['mapsubtext'],
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
    $this->setConfigurationValue('maptext', $form_state->getValue('maptext'));
    $this->setConfigurationValue('mapsubtext', $form_state->getValue('mapsubtext'));
  }
  
  public function build()
  {
    $data = array();
    $config = $this->getConfiguration();
    $data['text'] = isset($config['text']) ? $config['text'] : '';
    $data['subtext'] = isset($config['subtext']) ? $config['subtext'] : '';
    $data['maptext'] = isset($config['maptext']) ? $config['maptext'] : '';
    $data['mapsubtext'] = isset($config['mapsubtext']) ? $config['mapsubtext'] : '';
    $query = \Drupal::database()->select('dealer_state', 'd');
    $query->fields('d', ['id','state', 'state_lat', 'state_long']);
    $query->orderBy('d.state');
    $results = $query->execute()->fetchAll();
    foreach($results as $val){
      $state[$val->id]['ucstate'] = ucfirst(strtolower($val->state));
      $state[$val->id]['state'] = $val->state;
      $state[$val->id]['cord'] = $val->state_lat . "," . $val->state_long;
    }

    $query = \Drupal::database()->select('dealer_lat_long', 'd');
    $query->fields('d', ['city']);
    $query->orderBy('d.city');
    $query->distinct();
    $results = $query->execute()->fetchAll();
    foreach($results as $val){
      $data1[$val->city] = ucfirst($val->city);
    }

    $query = \Drupal::database()->select('dealer_lat_long', 'd');
    $query->fields('d', ['id', 'state', 'city', 'lat', 'long']);
    $results = $query->execute()->fetchAll();
    foreach($results as $val){
      $data2[$val->city]['state'] = ucfirst($val->state);
      $data2[$val->city]['cord'] = $val->lat . "," . $val->long;
    }

    $city = array_merge($data1,$data2);
    $data['state'] = $state;
    $data['city'] = $city;
    $config = \Drupal::config('config.custom_widget');
    $data['google_map_api_key'] = !empty($config->get('google_map_api_key'))? $config->get('google_map_api_key'): 'AIzaSyB4i2QQFtyO87OCzxhi0TQxWqZzKVZjSp8';

    return array(
      '#data' => $data,
      '#theme' => 'swaraj_near',
    );
  }
}
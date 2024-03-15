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
 *   id = "contact",
 *   admin_label = @Translation("Contact"),
 *   category = @Translation("Custom")
 * )
 */
class Contact extends BlockBase
{
  public function build()
  {
    $type_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('product_engine_power');
    foreach ($type_terms as $type_term) {
      $details = _getEngineProducts($type_term->tid);
      if($details != NULL){
        $product[$type_term->name] = $details;
      }
    }
    $implement_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('implements_category');
    foreach ($implement_terms as $type_term) {
      $details = _getImplements($type_term->tid);
      if($details != NULL){
        $implement[$type_term->name] = $details;
      }
    }
    $time = array(
      "9:30 AM",
      "10:00 AM",
      "10:30 AM",
      "11:00 AM",
      "11:30 AM",
      "12:00 PM",
      "12:30 PM",
      "1:00 PM",
      "1:30 PM",
      "2:00 PM",
      "2:30 PM",
      "3:00 PM",
      "3:30 PM",
      "4:00 PM",
      "4:30 PM",
      "5:00 PM",
      "5:30 PM",
      "6:00 PM",
      "6:30 PM",
      "7:00 PM",
      "7:30 PM",
      "8:00 PM",
    );
    $person = array(
      'individual' => 'individual',
      'business organization' => 'business organization',
    );
    $for = array(
      'tractor demo' => 'tractor demo',
      'service/parts feedback' => 'service/parts feedback',
      'spare part' => 'spare part request',
      'purchase/buy' => 'purchase/buy',
      'price' => 'price',
      'become a dealer' => 'become a dealer',
      'others' => 'others',
    );
    $data['product'] = $product;
    $data['implement'] = $implement;
    $data['person'] = $person;
    $data['time'] = $time;
    $data['for'] = $for;
    $data['states'] = get_states();
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_contact',
    );
  }
}
<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'New Contact Us' block.
 *
 * @Block(
 *   id = "new_contact_us",
 *   admin_label = @Translation("New Contact Us"),
 *   category = @Translation("Custom")
 * )
 */
class NewContactUs extends BlockBase
{
  public function build()
  {
    \Drupal::service('page_cache_kill_switch')->trigger();
    $temp_store_factory = \Drupal::service('session_based_temp_store');
    $temp_store = $temp_store_factory->get('new_contact_us_form', 4800);
    $token = bin2hex(random_bytes(32));
    $temp_store->set('contact_us_form_csrf_token', $token);

    $tractors = array();
    $type_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('product_engine_power');
    foreach ($type_terms as $type_term) {
      $details = _getEngineProducts($type_term->tid);
      if($details != NULL) {
        $tractors[$type_term->name] = $details;
      }
    }
    $harvestors = array();
    $implement_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('implements_category');
    foreach ($implement_terms as $type_term) {
      $details = _getImplements($type_term->tid);
      if($details != NULL) {
        if (stripos($type_term->name, 'Harvester')) {
          $harvestors[$type_term->name] = $details;
        }
      }
    }
    $config = \Drupal::config('config.custom_widget');
    $data['terms_link'] = $config->get('terms_link');
    $data['tractors'] = $tractors;
    $data['harvestors'] = $harvestors;
    $data['states'] = get_states();
    $data['contact_us_form_csrf_token'] = $token;

    return array(
      '#data' => $data,
      '#theme' => 'swaraj_new_contact_us',
    );
  }
}
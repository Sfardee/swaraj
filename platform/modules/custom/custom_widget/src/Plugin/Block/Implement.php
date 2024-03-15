<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Implement' block.
 *
 * @Block(
 *   id = "implement",
 *   admin_label = @Translation("Implements"),
 *   category = @Translation("Custom")
 * )
 */
class Implement extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    $node = array();
    $ids = isset($config['nid']) ? $config['nid'] : '';
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    foreach ($ids as $nid) {
      $temp_node = Node::load($nid['target_id']);
      if($temp_node->hasTranslation($language)){
        $temp_node = $temp_node->getTranslation($language);
      }
      $node[] = $temp_node;
    }
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config['title'],
    ];
    $form['tab'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tab Name'),
      '#default_value' => $config['tab'],
    ];
    $form['nid'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Implements'),
      '#target_type' => 'node',
      '#selection_settings' => array(
        'target_bundles' => array('features_implements'),
      ),
      '#tags' => TRUE,
      '#default_value' => $node,
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
    $this->setConfigurationValue('nid', $form_state->getValue('nid'));
    $this->setConfigurationValue('tab', $form_state->getValue('tab'));
  }

  public function build()
  {
    $result = $data = array();
    $config = $this->getConfiguration();
    $data['title'] = isset($config['title']) ? $config['title'] : '';
    $data['tab'] = isset($config['tab']) ? $config['tab'] : '';
    $ids = isset($config['nid']) ? $config['nid'] : '';
   
    foreach ($ids as $nid) {
      $node = Node::load($nid['target_id']);
      $result[$nid['target_id']]['title'] = $node->title->value;
      $result[$nid['target_id']]['image'] = _getmediaurl($node->field_image->target_id, 'image');
      $result[$nid['target_id']]['desc'] = $node->body->value;
    }
    
    $data['result'] = $result;
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_implement',
    );
  }
}
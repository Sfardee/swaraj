<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Similar' block.
 *
 * @Block(
 *   id = "similar",
 *   admin_label = @Translation("Similar"),
 *   category = @Translation("Custom")
 * )
 */
class Similar extends BlockBase
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
    $form['nid'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Products'),
      '#target_type' => 'node',
      '#selection_settings' => array(
        'target_bundles' => array('product'),
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
  }

  public function build()
  {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $product = $data = array();
    $config = $this->getConfiguration();
    $data['title'] = isset($config['title']) ? $config['title'] : '';
    $ids = isset($config['nid']) ? $config['nid'] : '';
   
    foreach ($ids as $nid) {
      $node = Node::load($nid['target_id']);
      if($node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $product[$nid['target_id']]['title'] = $node->title->value;
      $product[$nid['target_id']]['image'] = _getmediaurl($node->field_image->target_id, 'image');
      $product[$nid['target_id']]['link'] = 
        \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $nid['target_id']])
    ->setOption('language', \Drupal::languageManager()->getCurrentLanguage())->toString();
    }
    
    $data['product'] = $product;
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_similar',
    );
  }
}
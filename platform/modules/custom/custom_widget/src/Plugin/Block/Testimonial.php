<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Testimonial' block.
 *
 * @Block(
 *   id = "testimonial",
 *   admin_label = @Translation("Testimonial"),
 *   category = @Translation("Custom")
 * )
 */
class Testimonial extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    $type_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('testimonial_type');
    $type['-select-'] = $this->t('Type');
    foreach ($type_terms as $type_term) {
      $type_term = Term::load($type_term->tid);  
      $type[$type_term->id()] = $type_term->getName();
    }
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
    $this->setConfigurationValue('text', $form_state->getValue('text'));
    $this->setConfigurationValue('subtext', $form_state->getValue('subtext'));
    $this->setConfigurationValue('type', $form_state->getValue('type'));
  }

  public function build()
  {
    $data = array();
    $config = $this->getConfiguration();
    $testi['text'] = isset($config['text']) ? $config['text'] : '';
    $testi['subtext'] = isset($config['subtext']) ? $config['subtext'] : '';
    $type = isset($config['type']) ? $config['type'] : '';
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'testimonials');
    $query->condition('status', NODE_PUBLISHED);
    $query->condition('field_type', $type);
    $query->sort('field_node_weight', 'ASC');
    $entity_ids = $query->execute();
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid);
      if($node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $data[$nid]['body'] = $node->body->value;
      $data[$nid]['image'] = _getmediaurl($node->field_image->target_id, 'image');
      $data[$nid]['mobile_image'] = _getmediaurl($node->field_mobile_image->target_id, 'image');
      $data[$nid]['author'] = $node->field_author->value;
      $data[$nid]['location'] = $node->field_author_location->value;
    }
    $testi['data'] = $data;

    return array(
      '#data' => $testi,
      '#theme' => 'swaraj_testimonial',
    );
  }
}
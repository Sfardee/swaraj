<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides a 'Specification' block.
 *
 * @Block(
 *   id = "specification",
 *   admin_label = @Translation("Specification"),
 *   category = @Translation("Custom")
 * )
 */
class Specification extends BlockBase
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
    $form['tab'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tab Name'),
      '#default_value' => $config['tab'],
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
    $this->setConfigurationValue('tab', $form_state->getValue('tab'));
  }

  public function build()
  {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $data = $spec = array();
    $config = $this->getConfiguration();
    $data['title'] = isset($config['title']) ? $config['title'] : '';
    $data['tab'] = isset($config['tab']) ? $config['tab'] : '';
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      // You can get nid and anything else you need from the node object.
      $nid = $node->id();
      $paraid = $node->field_specification;
      foreach($paraid as $val){
        $en_para = Paragraph::load($val->target_id);
        $para = $en_para->getTranslation($language);
        $spec[$val->target_id]['name'] = $para->field_name->value;
        $spec[$val->target_id]['desc'] = $para->field_description->value;
      }
    }
    $data['spec'] = $spec;
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_specification',
    );
  }
}
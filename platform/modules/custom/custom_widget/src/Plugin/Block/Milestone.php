<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Milestone' block.
 *
 * @Block(
 *   id = "milestone",
 *   admin_label = @Translation("Milestone"),
 *   category = @Translation("Custom")
 * )
 */
class Milestone extends BlockBase
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
    $form['desc'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Body'),
      '#format' => 'full_html',
      '#default_value' => $config['desc']['value'],
    );

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
    $this->setConfigurationValue('desc', $form_state->getValue('desc'));
  }

  public function build()
  {
    $mile = $data = $engine = array();
    $config = $this->getConfiguration();
    $data['title'] = isset($config['title']) ? $config['title'] : '';
    $data['subtitle'] = isset($config['subtitle']) ? $config['subtitle'] : '';
    $data['desc'] = isset($config['desc']) ? $config['desc']['value'] : '';
    $data['year'] = date('Y');
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'milestone');
    $query->condition('status', NODE_PUBLISHED);
    $query->sort('title' , 'DESC'); 
    $entity_ids = $query->execute();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid);
      if($node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $title = $node->title->value;
      $para_id = $node->get('field_milestone')->getValue();
      foreach ($para_id as $key => $val) { 
        $para = Paragraph::load($val['target_id']);
        if ($para->hasTranslation($language)) {
          $para = $para->getTranslation($language);
        }
        $mile[$title][$val['target_id']]['title'] = $para->field_name->value;
        $mile[$title][$val['target_id']]['subtext'] = $para->field_number->value;
        $mile[$title][$val['target_id']]['desc'] = $para->field_description->value;
      }
    }
    $data['mile'] = $mile;

    return array(
      '#data' => $data,
      '#theme' => 'swaraj_milestone',
    );
  }
}
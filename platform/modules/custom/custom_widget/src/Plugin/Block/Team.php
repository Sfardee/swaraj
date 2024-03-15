<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Team' block.
 *
 * @Block(
 *   id = "team",
 *   admin_label = @Translation("Team"),
 *   category = @Translation("Custom")
 * )
 */
class Team extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $val = isset($config['comm']) ? Node::load($config['comm']) : '';
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
    $form['comm'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Testimonial'),
      '#target_type' => 'node',
      '#selection_settings' => array(
        'target_bundles' => array('testimonials'),
      ),
      '#tags' => TRUE,
      '#default_value' => $val,
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
    $this->setConfigurationValue('comm', $form_state->getValue('comm'));
  }

  public function build()
  {
    $pages = $data = $testi = array();
    $config = $this->getConfiguration();
    $data['title'] = isset($config['title']) ? $config['title'] : '';
    $data['subtitle'] = isset($config['subtitle']) ? $config['subtitle'] : '';
    $ids = isset($config['nid']) ? $config['nid'] : '';
    $comm = isset($config['comm'])? $config['comm']: array();
    foreach ($comm as $nid) {
      $node = Node::load($nid['target_id']);
      
      $data['author']=$node->field_author->value;
      $data['desc']=$node->body->value;
      $data['desi']=$node->field_wheel_drive->value;
      $data['img']=_getmediaurl($node->field_big_image->target_id, 'image');
    }
    
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_team',
    );
  }
}
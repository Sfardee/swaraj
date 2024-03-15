<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Yourself' block.
 *
 * @Block(
 *   id = "yourself",
 *   admin_label = @Translation("Yourself"),
 *   category = @Translation("Custom")
 * )
 */
class Yourself extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    $node = array();
    $ids = isset($config['nid']) ? $config['nid'] : '';
    foreach ($ids as $nid) {
      $node[] = Node::load($nid['target_id']);
    }
    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config['text'],
    ];
    $form['tab'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tab Name'),
      '#default_value' => $config['tab'],
    ];
    $form['nid'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Articles'),
      '#target_type' => 'node',
      '#selection_settings' => array(
        'target_bundles' => array('article'),
      ),
      '#tags' => TRUE,
      '#maxlength' => 1000,
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
    $this->setConfigurationValue('text', $form_state->getValue('text'));
    $this->setConfigurationValue('tab', $form_state->getValue('tab'));
    $this->setConfigurationValue('nid', $form_state->getValue('nid'));
  }

  public function build()
  {
    $result = $video = array();
    $config = $this->getConfiguration();
    $data['text'] = isset($config['text']) ? $config['text'] : '';
    $data['tab'] = isset($config['tab']) ? $config['tab'] : '';
    $ids = isset($config['nid']) ? $config['nid'] : '';

    foreach ($ids as $nid) {
      $node = Node::load($nid['target_id']);
      $video_link = $node->field_video->value;
      if($video_link){
        $result[$nid['target_id']]['type'] = 'video';
        $video[$nid['target_id']] = $video_link;
      }
      $result[$nid['target_id']]['title'] = $node->title->value;
      $result[$nid['target_id']]['image'] = _getmediaurl($node->field_image->target_id, 'image');
      $result[$nid['target_id']]['desc'] = $node->body->value;
      $result[$nid['target_id']]['link'] = !is_null($node->field_cta->uri) ? Url::fromUri($node->field_cta->uri)->toString() : '';
      $result[$nid['target_id']]['link_title'] = $node->field_cta->title;
      $result[$nid['target_id']]['short'] = $node->field_short_description->value;
      $result[$nid['target_id']]['date'] = date('dS M, y', $node->created->value);
      $result[$nid['target_id']]['category'] = $node->type->value;
    }
    
    $data['result'] = $result;
    $data['video'] = $video;
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_yourself',
    );
  }
}
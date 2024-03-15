<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Gallery2' block.
 *
 * @Block(
 *   id = "gallery2",
 *   admin_label = @Translation("Gallery2"),
 *   category = @Translation("Custom")
 * )
 */
class Gallery2 extends BlockBase
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
      '#title' => $this->t('Gallery'),
      '#target_type' => 'node',
      '#selection_settings' => array(
        'target_bundles' => array('gallery'),
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
    $images = $gallery = $details = array();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $config = $this->getConfiguration();
    $gallery['title'] = isset($config['title']) ? $config['title'] : '';
    $entity_ids = isset($config['nid']) ? $config['nid'] : '';
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid['target_id']);
      if($node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $para_id = $node->get('field_gallery')->getValue();
      $data = array();
      $video_count = $image_count = 0;
      foreach ($para_id as $key => $val) { 
        $para = Paragraph::load($val['target_id']);
        if ($para->hasTranslation($language)) {
          $para = $para->getTranslation($language);
        }
        if(_getmediaurl($para->field_model_image->target_id, 'image')){
          $data[$val['target_id']]['image'] = _getmediaurl($para->field_model_image->target_id, 'image');
          $image_count++;
        }
        if($para->field_thousand_text->value){
          $data[$val['target_id']]['video'] = $para->field_thousand_text->value;
          $video_count++;
        }
        $data[$val['target_id']]['text'] = $para->field_name->value;
      }
      $node->set('field_wheel_drive', $image_count);
      $node->set('field_pto_hp', $video_count);
      $node->save();
      $images[$nid['target_id']][] = $data;
      $details[$nid['target_id']]['images'] = $image_count;
      $details[$nid['target_id']]['videos'] = $video_count;
      $details[$nid['target_id']]['title'] = $node->title->value;
      $details[$nid['target_id']]['img'] = _getmediaurl($node->field_image->target_id, 'image');
    }
    $gallery['gallery'] = $images;
    $gallery['details'] = $details;

    return array(
      '#data' => $gallery,
      '#theme' => 'swaraj_gallery2',
    );
  }
}
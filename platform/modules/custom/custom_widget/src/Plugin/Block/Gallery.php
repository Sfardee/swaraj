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
 * Provides a 'Gallery' block.
 *
 * @Block(
 *   id = "gallery",
 *   admin_label = @Translation("Gallery"),
 *   category = @Translation("Custom")
 * )
 */
class Gallery extends BlockBase
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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('title', $form_state->getValue('title'));
  }

  public function build()
  {
    $images = $gallery = array();
    $config = $this->getConfiguration();
    $gallery['title'] = isset($config['title']) ? $config['title'] : '';
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'gallery');
    $query->condition('status', NODE_PUBLISHED);
    $query->sort('changed' , 'ASC'); 
    $entity_ids = $query->execute();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid);
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
      $images[$nid][] = $data;
    }
    $gallery['gallery'] = $images;
    $view = \Drupal\views\Views::getView('gallery');
		$view->setDisplay('gallery');
		$gallery['view'] = $view->buildRenderable();
    // \Drupal::logger('debugging')->warning(print_r($gallery, TRUE));
    return array(
      '#data' => $gallery,
      '#theme' => 'swaraj_gallery',
    );
  }
}
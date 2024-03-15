<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'News' block.
 *
 * @Block(
 *   id = "news",
 *   admin_label = @Translation("News"),
 *   category = @Translation("Custom")
 * )
 */
class News extends BlockBase
{

   public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    $val = isset($config['nid']) ? Node::load($config['nid']) : '';
    $option = array(
      '' => 'Select',
      'csr' => 'CSR',
      'news' => 'News',
      'article' => 'Article',
    );
    $form['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#options' => $option,
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
    $this->setConfigurationValue('type', $form_state->getValue('type'));
  }

  public function build()
  {
    $data = $video = array();
    $config = $this->getConfiguration();
    $type = isset($config['type']) ? $config['type'] : '';
    $view = \Drupal\views\Views::getView('news');
		$view->setDisplay($type);
		$data['view_display'] = $view->buildRenderable();
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'news');
    $query->condition('status', NODE_PUBLISHED);
    $entity_ids = $query->execute();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid);
      if($node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $video_link = $node->field_video->value;
      if($video_link){
        $video[$nid] = $video_link;
      }
    }
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'csr');
    $query->condition('status', NODE_PUBLISHED);
    $entity_ids = $query->execute();
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid);
      $video_link = $node->field_video->value;
      if($video_link){
        $video[$nid] = $video_link;
      }
    }
    $data['video'] = $video;
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_news',
    );
  }
}
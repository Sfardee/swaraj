<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'SearchData' block.
 *
 * @Block(
 *   id = "search_data",
 *   admin_label = @Translation("SearchData"),
 *   category = @Translation("Custom")
 * )
 */
class SearchData extends BlockBase
{  
  /**
   * {@inheritdoc}
   */
  public function build()
  {
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
    return [
      '#data' => $data,
      '#theme' => 'swaraj_search_data',
    ];
  }
}
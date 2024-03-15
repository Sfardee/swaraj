<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'ArticleDetails' block.
 *
 * @Block(
 *   id = "articledetails",
 *   admin_label = @Translation("ArticleDetails"),
 *   category = @Translation("Custom")
 * )
 */
class ArticleDetails extends BlockBase
{

  public function build()
  {
    $result = array();
    $node = \Drupal::routeMatch()->getParameter('node');
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if ($node instanceof \Drupal\node\NodeInterface) {
      $nid = $node->id();
      $type = $node->bundle();
      $nodeids = array();
      $query = \Drupal::entityQuery('node');
      $query->condition('type', $type);
      $query->condition('status', NODE_PUBLISHED);
      $query->sort('changed', 'ASC');
      $entity_ids = $query->execute();
      foreach($entity_ids as $id){
        $node_load = Node::load($id);
        if($node->hasTranslation($language)){
          $node = $node->getTranslation($language);
        }
        if($node_load->body->value){
          $nodeids[] = $id;
        }
      }
      $i = 0;
      $found = false;
      foreach ($nodeids as $nodeid) {
        if ($nodeid == $nid) {
          $found = true;
          break;
        }
        $i++;
      }
      
      if ($found && count($nodeids) > 1) {
          if ($i < count($nodeids)-1) {
            $next = $nodeids[$i+1];
            $result['next'] = Url::fromRoute('entity.node.canonical', ['node' => $next])->toString();
          } else {
            $result['next'] = '';
          }
        if ($i > 0) {
          $prev = $nodeids[$i-1];
          $result['prev'] = Url::fromRoute('entity.node.canonical', ['node' => $prev])->toString();
        } else {
          $result['prev'] = '';
        }
      } else {
        $result['next'] = '';
        $result['prev'] = '';
      } 
      $result['title'] = $node->title->value;
      $result['desc'] = $node->body->value;
      $result['date'] = date('dS M, y', $node->created->value);
      $result['author'] = $node->field_author->value;
      $result['author_img'] = _getmediaurl($node->field_similar_image->target_id, 'image');
      $result['all'] = $type;
    }
    
    return array(
      '#data' => $result,
      '#theme' => 'swaraj_article_details',
    );
  }
}
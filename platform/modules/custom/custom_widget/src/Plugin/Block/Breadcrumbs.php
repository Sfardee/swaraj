<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Url;

/**
 * Provides a 'Breadcrumbs' block.
 *
 * @Block(
 *   id = "breadcrumbs",
 *   admin_label = @Translation("Breadcrumbs"),
 *   category = @Translation("Custom")
 * )
 */
class Breadcrumbs extends BlockBase
{

  public function build()
  {
    $data = array();
    $node = \Drupal::routeMatch()->getParameter('node');
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if($node->hasTranslation($language)){
      $node = $node->getTranslation($language);
    }
    if ($node instanceof \Drupal\node\NodeInterface) {
      $nid = $node->id();
      $type = $node->bundle();
      if($type == "article"){
        $para_id = $node->get('field_breadcrumbs')->getValue();
        foreach ($para_id as $key => $val) {
          $para = Paragraph::load($val['target_id']);
          $res['title'] = $para->field_name->value;
          $link = $para->field_link->uri;
          $res['link'] = !empty($link) ? Url::fromUri($link) : '';
          $page_breadcrumbs[] = $res;
        }
        $data['page_breadcrumbs'] = $page_breadcrumbs;
      }
    }
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_breadcrumbs',
    );
  }
}
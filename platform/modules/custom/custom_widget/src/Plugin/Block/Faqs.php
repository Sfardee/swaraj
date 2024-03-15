<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Faqs' block.
 *
 * @Block(
 *   id = "faqs",
 *   admin_label = @Translation("Faqs"),
 *   category = @Translation("Custom")
 * )
 */
class Faqs extends BlockBase
{

  public function build()
  {
    $faq = $type = array();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'faq');
    $query->condition('status', NODE_PUBLISHED);
    $entity_ids = $query->execute();
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid);
      if($node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $term = Term::load($node->field_faq_category->target_id); 
      if($term->hasTranslation($language)){
        $term = $term->getTranslation($language);
      }
      $type[] = $term->getName();
      $faq[$term->getName()][$nid]['ques'] = $node->field_short_description->value;
      $faq[$term->getName()][$nid]['ans'] = $node->field_answer->value;
    }
    $data['data'] = $faq;
    $data['type'] = array_unique($type);

    return array(
      '#data' => $data,
      '#theme' => 'swaraj_faq',
    );
  }
}
<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Share' block.
 *
 * @Block(
 *   id = "share",
 *   admin_label = @Translation("Share"),
 *   category = @Translation("Custom")
 * )
 */
class Share extends BlockBase
{

  public function build()
  {
    global $base_url;
    // $config = \Drupal::config('config.custom_widget');
    // $data['facebook'] = $config->get('facebook_link');
    // $data['twitter'] = $config->get('twitter_link');
    // $data['linkedin'] = $config->get('linkedin_link');
    $node = \Drupal::routeMatch()->getParameter('node');
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if($node->hasTranslation($language)){
      $node = $node->getTranslation($language);
    }
    if ($node instanceof \Drupal\node\NodeInterface) {
      $nid = $node->id();
      $data['title'] = $node->title->value;
      $options = ['absolute' => TRUE];
      $data['link'] = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $nid], $options)->toString();
    }

    return array(
      '#data' => $data,
      '#theme' => 'swaraj_share',
    );
  }
}
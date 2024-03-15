<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Similar Article' block.
 *
 * @Block(
 *   id = "similarArticle",
 *   admin_label = @Translation("Similar Article"),
 *   category = @Translation("Custom")
 * )
 */
class SimilarArticle extends BlockBase
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
      '#title' => $this->t('Products'),
      '#target_type' => 'node',
      '#selection_settings' => array(
        'target_bundles' => array('article'),
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
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $article = $video = $data = array();
    $config = $this->getConfiguration();
    $article['title'] = isset($config['title']) ? $config['title'] : '';
    $ids = isset($config['nid']) ? $config['nid'] : '';
    
    foreach ($ids as $nid) {
      $node = Node::load($nid['target_id']);
      if($node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $nid = $nid['target_id'];
      $video_link = $node->field_video->value;
      if($video_link){
        $data[$nid]['type'] = 'video';
        $video[$nid] = $video_link;
      }
      $data[$nid]['nid'] = $nid;
      $data[$nid]['title'] = $node->title->value;
      $data[$nid]['body'] = $node->body->value;
      $data[$nid]['link'] = !is_null($node->field_cta->uri) ? Url::fromUri($node->field_cta->uri)->toString() : '';
      $data[$nid]['link_title'] = $node->field_cta->title;
      $data[$nid]['image'] = _getmediaurl($node->field_image->target_id, 'image');
      $data[$nid]['short'] = $node->field_short_description->value;
      $data[$nid]['date'] = date('dS M, y', $node->created->value);
    }
    
    $article['data'] = $data;
    $article['video'] = $video;

    return array(
      '#data' => $article,
      '#theme' => 'swaraj_similar_article',
    );
  }
}
<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Article' block.
 *
 * @Block(
 *   id = "article",
 *   admin_label = @Translation("Article"),
 *   category = @Translation("Custom")
 * )
 */
class Article extends BlockBase
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
    );
    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config['text'],
    ];
    $form['subtext'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#default_value' => $config['subtext'],
    ];
    $form['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#options' => $option,
      '#default_value' => $config['type'],
    ];
    $form['view'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('View All'),
      '#default_value' => $config['view'],
    ];
    $form['nid'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Link'),
      '#target_type' => 'node',
      '#selection_settings' => array(
        'target_bundles' => array('page'),
      ),
      '#tags' => TRUE,
      '#default_value' => $val,
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
    $this->setConfigurationValue('subtext', $form_state->getValue('subtext'));
    $this->setConfigurationValue('type', $form_state->getValue('type'));
    $this->setConfigurationValue('view', $form_state->getValue('view'));
    $this->setConfigurationValue('nid', $form_state->getValue('nid'));
  }

  public function build()
  {
    $data = array();
    $video = array();
    $config = $this->getConfiguration();
    $article['text'] = isset($config['text']) ? $config['text'] : '';
    $article['subtext'] = isset($config['subtext']) ? $config['subtext'] : '';
    $type = isset($config['type']) ? $config['type'] : '';
    $article['view'] = isset($config['view']) ? $config['view'] : '';
    $ids = isset($config['nid']) ? $config['nid'] : '';
    $view = $article['view'];
    $query = \Drupal::entityQuery('node');
    $query->condition('type', $type);
    $query->condition('status', NODE_PUBLISHED);
    if($article['view']){
      $query->range(0, 3);
    }
    $query->sort('changed' , 'DESC'); 
    $entity_ids = $query->execute();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid);
      if($node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $video_link = $node->field_video->value;
      if($video_link){
        $data[$nid]['type'] = 'video';
        $video[$nid] = $video_link;
      }
      $data[$nid]['nid'] = $nid;
      $data[$nid]['title'] = $node->title->value;
      $data[$nid]['body'] = $node->body->value;
      if($type == 'news'){
        $data[$nid]['category'] = _getNameByTid($node->field_news_category->target_id);
      } else {
        $data[$nid]['category'] = _getNameByTid($node->field_csr_category->target_id);
      }
      $data[$nid]['link'] = !is_null($node->field_cta->uri) ? Url::fromUri($node->field_cta->uri)->toString() : '';
      $data[$nid]['link_title'] = $node->field_cta->title;
      $data[$nid]['image'] = _getmediaurl($node->field_image->target_id, 'image');
      $data[$nid]['short'] = $node->field_short_description->value;
      $data[$nid]['target'] = $node->field_target->value;
      $data[$nid]['date'] = date('dS M, y', $node->created->value);
    }
    $article['data'] = $data;
    $article['video'] = $video;
    if($ids){
      foreach ($ids as $nid) {
        $node = Node::load($nid['target_id']);
        $article['link'] = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'. $nid['target_id']);
      }
    }

    return array(
      '#data' => $article,
      '#theme' => 'swaraj_article',
    );
  }
}
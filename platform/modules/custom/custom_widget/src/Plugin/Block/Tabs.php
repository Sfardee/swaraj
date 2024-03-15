<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides a 'Tabs' block.
 *
 * @Block(
 *   id = "tabs",
 *   admin_label = @Translation("Tabs"),
 *   category = @Translation("Custom")
 * )
 */
class Tabs extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    $node = array();
    $ids = isset($config['nid']) ? $config['nid'] : array();
    foreach ($ids as $nid) {
      $node[] = Node::load($nid['target_id']);
    }
    $top = isset($config['top'])? $config['top']: 0;
    $form['nid'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Pages'),
      '#target_type' => 'node',
      '#selection_settings' => array(
        'target_bundles' => array('page'),
      ),
      '#tags' => TRUE,
      '#default_value' => $node,
    ];
    $form['top'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Scroll Top'),
      '#default_value' => $top,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('nid', $form_state->getValue('nid'));
    $this->setConfigurationValue('top', $form_state->getValue('top'));
  }

  public function build()
  {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $data = array();
    $config = $this->getConfiguration();
    $ids = isset($config['nid']) ? $config['nid'] : '';
    $data['top'] = isset($config['top']) ? $config['top'] : '';
    foreach ($ids as $nid) {
      $node = Node::load($nid['target_id']);
      if($node != NULL && $node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $pages[$node->title->value] = 
         \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $nid['target_id']])
    ->setOption('language', \Drupal::languageManager()->getCurrentLanguage())->toString();
        //\Drupal::service('path.alias_manager')->getAliasByPath('/' . $language . '/node/'. $nid['target_id'], $language);
    }
   // kint($pages);exit;
    $data['pages'] = $pages;
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_tabs',
    );
  }
}
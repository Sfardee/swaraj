<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Member' block.
 *
 * @Block(
 *   id = "member",
 *   admin_label = @Translation("Member"),
 *   category = @Translation("Custom")
 * )
 */
class Member extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
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
    $pages = $data = $details = array();
    $config = $this->getConfiguration();
    $data['title'] = isset($config['title']) ? $config['title'] : '';
    $view = \Drupal\views\Views::getView('team');
		$view->setDisplay('team');
    $data['team'] = $view->buildRenderable();
		
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'team_member');
    $query->condition('status', NODE_PUBLISHED);
    $query->sort('field_node_weight', 'ASC');
    $entity_ids = $query->execute();
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    
    $i = 0;
    foreach ($entity_ids as $nid) {
      $node = Node::load($nid);
      if($node->hasTranslation($language)){
        $node = $node->getTranslation($language);
      }
      $details[$i]['nid'] = $nid;
      $details[$i]['name'] = $node->title->value;
      $details[$i]['desi'] = $node->field_author->value;
      $details[$i]['twitter'] = $node->field_pto_hp->value;
      $details[$i]['linkedin'] = $node->field_wheel_drive->value;
      $details[$i]['body'] = $node->body->value;
      $details[$i]['image'] = _getmediaurl($node->field_image->target_id, 'image');
      $i++; 
    }
    $data['details'] = $details;
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_member',
    );
  }
}
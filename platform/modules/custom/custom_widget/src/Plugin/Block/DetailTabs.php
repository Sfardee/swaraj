<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides a 'DetailTabs' block.
 *
 * @Block(
 *   id = "detailtabs",
 *   admin_label = @Translation("DetailTabs"),
 *   category = @Translation("Custom")
 * )
 */
class DetailTabs extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
  
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Tab Names'),
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
    $data = array();
    $config = $this->getConfiguration();
    $title = isset($config['title']) ? $config['title'] : '';
    $tabs = explode(',', $title);
    foreach($tabs as $tab){
      $name = explode('-', $tab);
      $data[trim($name[0])] = trim($name[1]);
    }
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_detailtabs',
    );
  }
}
<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'ImplementList' block.
 *
 * @Block(
 *   id = "implementlist",
 *   admin_label = @Translation("ImplementList"),
 *   category = @Translation("Custom")
 * )
 */
class ImplementList extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config['title'],
      '#maxlength' => '255',
    ];
    $form['subtitle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subtitle'),
      '#default_value' => $config['subtitle'],
      '#maxlength' => '255',
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
    $this->setConfigurationValue('subtitle', $form_state->getValue('subtitle'));
  }

  public function build()
  {
    $implement = $data = $cat = array();
    $config = $this->getConfiguration();
    $data['title'] = isset($config['title']) ? $config['title'] : '';
    $data['subtitle'] = isset($config['subtitle']) ? $config['subtitle'] : '';
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $type_terms = \Drupal::entityManager()->getStorage('taxonomy_term')
      ->loadByProperties(['vid' => 'implements_category']);
    foreach ($type_terms as $type_term) {
      if ($type_term->hasTranslation($language)) {
        $type_term = $type_term->getTranslation($language);
      }
      $implement[$type_term->label()] = _getImplements($type_term->id());
      $cat[$type_term->id()] = $type_term->label();
    }
    $data['cat'] = $cat;
    $data['implement'] = $implement;
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_implements',
    );
  }
}
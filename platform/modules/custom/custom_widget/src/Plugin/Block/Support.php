<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Support' block.
 *
 * @Block(
 *   id = "support",
 *   admin_label = @Translation("Support"),
 *   category = @Translation("Custom")
 * )
 */
class Support extends BlockBase
{

  public function blockForm($form, FormStateInterface $form_state)
  {
    $form = parent::blockForm($form, $form_state);
    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text'),
      '#default_value' => $config['text'],
    ];
    $form['subtext'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Time'),
      '#default_value' => $config['subtext'],
    ];
    $form['mobile'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mobile'),
      '#default_value' => $config['mobile'],
    ];
    $form['whatsapp'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Whatsapp'),
      '#default_value' => $config['whatsapp'],
    ];
    $form['mail'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#default_value' => $config['mail'],
    ];
    $form['link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Feedback Link'),
      '#default_value' => $config['link'],
    ];
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    $this->setConfigurationValue('text', $form_state->getValue('text'));
    $this->setConfigurationValue('subtext', $form_state->getValue('subtext'));
    $this->setConfigurationValue('mobile', $form_state->getValue('mobile'));
    $this->setConfigurationValue('whatsapp', $form_state->getValue('whatsapp'));
    $this->setConfigurationValue('mail', $form_state->getValue('mail'));
    $this->setConfigurationValue('link', $form_state->getValue('link'));
  }

  public function build()
  {
    $data = array();
    $config = $this->getConfiguration();
    $data['text'] = isset($config['text']) ? $config['text'] : '';
    $data['subtext'] = isset($config['subtext']) ? $config['subtext'] : '';
    $data['mobile'] = isset($config['mobile']) ? $config['mobile'] : '';
    $data['whatsapp'] = isset($config['whatsapp']) ? $config['whatsapp'] : '';
    $data['mail'] = isset($config['mail']) ? $config['mail'] : '';
    $data['link'] = isset($config['link']) ? $config['link'] : '';
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_support',
    );
  }
}
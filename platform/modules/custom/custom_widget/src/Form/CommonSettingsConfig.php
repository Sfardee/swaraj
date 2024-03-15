<?php

/**
 * @file
 * Contains \Drupal\custom_widget\Form\SocialMediaConfig.
 */

namespace Drupal\custom_widget\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\file\Entity\File;

class CommonSettingsConfig extends ConfigFormBase
{

  public function __construct(ConfigFactoryInterface $config_factory)
  {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'swaraj_common_settings';
  }

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   * An array of configuration object names that are editable if called in
   * conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames()
  {
    return ['config.custom_widget'];
  }

  /**
   * Form constructor.
   *
   * @param array $form
   * An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * The current state of the form.
   *
   * @return array
   * The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    global $base_url;

    $config = $this->config('config.custom_widget');
    $langcodes = \Drupal::languageManager()->getLanguages();
    $langOptions = [];
    foreach ($langcodes as $key => $val) {
      $langOptions[$key] = $val->getName();
    }
    $form['toll_free_number'] = array(
      '#type' => 'textfield',
      '#title' => t('Toll Free Number'),
      '#default_value' => $config->get('toll_free_number') ? $config->get('toll_free_number') : '',
      '#description' => t('Toll free number - displayed in the header of all the pages in desktop')
    );
    $form['language_setting'] = [
      '#type' => 'select',
      '#title' => 'Language (ContactUs menu, Footer text Only)',
      '#options' => $langOptions,
      '#required' => TRUE,
      '#default_value' => \Drupal::languageManager()->getCurrentLanguage()->getId()
    ];
    $form['contact_us_main_menu'] = array(
      '#type' => 'textarea',
      '#title' => t('Contact Us - main menu'),
      '#default_value' => $config->get('contact_us_main_menu') ? $config->get('contact_us_main_menu') : '',
      '#description' => t('Contact us - address displayed in main menu.')
    );

    $form['contact_us_footer_menu'] = array(
      '#type' => 'textarea',
      '#title' => t('Contact Us - footer menu'),
      '#default_value' => $config->get('contact_us_footer_menu') ? $config->get('contact_us_footer_menu') : '',
      '#description' => t('Contact us - address displayed in footer menu.')
    );

    $form['footer_disclaimer_text'] = array(
      '#type' => 'textarea',
      '#title' => t('Footer disclaimer text'),
      '#default_value' => $config->get('footer_disclaimer_text') ? $config->get('footer_disclaimer_text') : '',
      '#description' => t('Disclaimer text displayed in footer.')
    );

    $form['footer_copyright_text'] = array(
      '#type' => 'textarea',
      '#title' => t('Footer copyright text'),
      '#default_value' => $config->get('footer_copyright_text') ? $config->get('footer_copyright_text') : '',
      '#description' => t('Copyright text displayed in footer.')
    );

    $form['privacy_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Privacy Policy Link'),
      '#default_value' => $config->get('privacy_link') ? $config->get('privacy_link') : '',
    );

    $form['terms_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Terms of Use Link'),
      '#default_value' => $config->get('terms_link') ? $config->get('terms_link') : '',
    );

    $form['contact_us_thank_you_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Thank You Page Link for Contact Us form'),
      '#default_value' => $config->get('contact_us_thank_you_link') ? $config->get('contact_us_thank_you_link') : '',
    );

    $form['facebook_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Facebook Link'),
      '#default_value' => $config->get('facebook_link') ? $config->get('facebook_link') : '',
    );

    $form['twitter_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Twitter Link'),
      '#default_value' => $config->get('twitter_link') ? $config->get('twitter_link') : '',
    );

    $form['linkedin_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Linkedin Link'),
      '#default_value' => $config->get('linkedin_link') ? $config->get('linkedin_link') : '',
    );

    $form['youtube_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Youtube Link'),
      '#default_value' => $config->get('youtube_link') ? $config->get('youtube_link') : '',
    );

    $form['android_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Android App Link'),
      '#default_value' => $config->get('android_link') ? $config->get('android_link') : '',
    );

    $form['apple_link'] = array(
      '#type' => 'textfield',
      '#title' => t('Apple App Link'),
      '#default_value' => $config->get('apple_link') ? $config->get('apple_link') : '',
    );

    $form['faq_link'] = array(
      '#type' => 'textfield',
      '#title' => t('FAQ Link'),
      '#default_value' => $config->get('faq_link') ? $config->get('faq_link') : '',
    );

    $form['show_date'] = array(
      '#type' => 'checkbox',
      '#title' => t('Show Datepicker Filter'),
      '#default_value' => $config->get('show_date') ? $config->get('show_date') : '',
    );

    $form['main_logo_green'] = array(
      '#type'          => 'managed_file',
      '#title'         => t('Choose png file for logo'),
      '#upload_location' => 'public://logos/',
      '#default_value' => $config->get('main_logo_green') ? $config->get('main_logo_green') : '',
      '#description'   => t('Website logo - green background.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png'],
      ],
    );

    $form['main_logo_white'] = array(
      '#type'          => 'managed_file',
      '#title'         => t('Choose png file for logo'),
      '#upload_location' => 'public://logos/',
      '#default_value' => $config->get('main_logo_white') ? $config->get('main_logo_white') : '',
      '#description'   => t('Website logo - white background.'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png'],
      ],
    );

    $form['google_map_api_key'] = array(
      '#type' => 'textfield',
      '#title' => t('Google Map API Key'),
      '#default_value' => $config->get('google_map_api_key') ? $config->get('google_map_api_key') : '',
    );

    $form['tractor_list_nid'] = array(
      '#type' => 'textfield',
      '#title' => t('Tractor Listing Page Node Id'),
      '#default_value' => $config->get('tractor_list_nid') ? $config->get('tractor_list_nid') : '44',
    );
    
    $form['implements_list_nid'] = array(
      '#type' => 'textfield',
      '#title' => t('Implements Listing Page Node Id'),
      '#default_value' => $config->get('implements_list_nid') ? $config->get('implements_list_nid') : '128',
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   * An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);

    $values = $form_state->getValues();

    $form_file = $form_state->getValue('main_logo_white', 0);
    if (isset($form_file[0]) && !empty($form_file[0])) {
      $file = File::load($form_file[0]);
      $file->setPermanent();
      $file->save();
      $main_logo_white = $file->getFileUri();
    }
    $form_file = $form_state->getValue('main_logo_green', 0);
    if (isset($form_file[0]) && !empty($form_file[0])) {
      $file = File::load($form_file[0]);
      $file->setPermanent();
      $file->save();
      $main_logo_green = $file->getFileUri();
    }
    $langCode = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $sel_lang = $form_state->getValue('language_setting');
    if ($form_state->getValue('language_setting') != NULL) {
      $variables = [
        'contact_us_main_menu' => 'contact_us_main_menu_' . $sel_lang,
        'contact_us_footer_menu' => 'contact_us_footer_menu_' . $sel_lang,
        'footer_disclaimer_text' => 'footer_disclaimer_text_' . $sel_lang,
        'footer_copyright_text' => 'footer_copyright_text_' . $sel_lang
      ];
      foreach ($variables as $variable_key => $variable_val) {
        //print $variable_val . '-' . $values[$variable_key] . '<br>';
        \Drupal::state()->set($variable_val, $values[$variable_key]);
      }
      
      /*foreach ($variables as $variable_key => $variable_val) {
        kint(\Drupal::state()->get($variable_val));
      }*/
    }//exit;
    $this->config('config.custom_widget')
      ->set('toll_free_number', $values['toll_free_number'])
      ->set('contact_us_main_menu', $values['contact_us_main_menu'])
      ->set('contact_us_footer_menu', $values['contact_us_footer_menu'])
      ->set('footer_disclaimer_text', $values['footer_disclaimer_text'])
      ->set('footer_copyright_text', $values['footer_copyright_text'])
      ->set('privacy_link', $values['privacy_link'])
      ->set('terms_link', $values['terms_link'])
      ->set('contact_us_thank_you_link', $values['contact_us_thank_you_link'])
      ->set('facebook_link', $values['facebook_link'])
      ->set('twitter_link', $values['twitter_link'])
      ->set('linkedin_link', $values['linkedin_link'])
      ->set('youtube_link', $values['youtube_link'])
      ->set('android_link', $values['android_link'])
      ->set('apple_link', $values['apple_link'])
      ->set('google_map_api_key', $values['google_map_api_key'])
      ->set('tractor_list_nid', $values['tractor_list_nid'])
      ->set('implements_list_nid', $values['implements_list_nid'])
      ->set('faq_link', $values['faq_link'])
      ->set('show_date', $values['show_date'])
      ->save();
    
  
    if (isset($main_logo_green)) {
      $this->config('config.custom_widget')
      ->set('main_logo_green', $main_logo_green)
      ->save();
    }
    if (isset($main_logo_white)) {
      $this->config('config.custom_widget')
      ->set('main_logo_white', $main_logo_white)
      ->save();
    }
    
  }
}

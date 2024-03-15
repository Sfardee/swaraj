<?php

/**
 * @file
 * Contains \Drupal\custom_widget\Form\ImportForm.
 */

namespace Drupal\custom_widget\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

class ImportForm extends ConfigFormBase
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
    return 'swaraj_import_form';
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
    $module_handler = \Drupal::service('module_handler');
    $module_path = $module_handler->getModule('custom_widget')->getPath();
    $sample_fileurl =  ($config->get('dealer_importer_file_uri'))? file_create_url($config->get('dealer_importer_file_uri')): Url::fromUri('base:'.$module_path . '/swaraj-dealer-import.csv')->toString(TRUE)->getGeneratedUrl();
    
    $form['description'] = array(
      '#markup' => '<p>Use this form to upload a CSV file of Data.</p><p>Here is the <a href="' . $sample_fileurl .'" target="_blank">file</a> uploaded last time  for reference. Please do not modify first line and note that this line will be ignored as it is header. Do not remove any entry, but mark deale inactive if required and append new dealer data at the end of the file. Also, make sure state names are correct as per contact form master data.</p>',
    );

    $form['import_csv'] = array(
      '#type' => 'managed_file',
      '#title' => t('Upload file here'),
      '#upload_location' => 'public://importcsv/',
      '#default_value' => '',
      "#upload_validators"  => array("file_validate_extensions" => array("csv")),
      '#states' => array(
        'visible' => array(
          ':input[name="File_type"]' => array('value' => t('Upload Your File')),
        ),
      ),
    );

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Upload CSV'),
      '#button_type' => 'primary',
    );

    return $form;
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
    /* Fetch the array of the file stored temporarily in database */
    $csv_file = $form_state->getValue('import_csv');

    /* Load the object of the file by it's fid */
    $file = File::load( $csv_file[0] );

    /* Set the status flag permanent of the file object */
    $file->setPermanent();

    /* Save the file in database */
    $file->save();

    $this->config('config.custom_widget')->set('dealer_importer_file_uri', $file->getFileUri())->save();
    // You can use any sort of function to process your data. The goal is to get each 'row' of data into an array
    // If you need to work on how data is extracted, process it here.
    $data = $this->csvtoarray($file->getFileUri(), ',');
    foreach($data as $row) {
      $operations[] = ['\Drupal\custom_widget\Plugin\addImportContent::addImportContentItem', [$row]];
    }

    $batch = array(
      'title' => t('Importing Data...'),
      'operations' => $operations,
      'init_message' => t('Import is starting.'),
      'finished' => '\Drupal\custom_widget\Plugin\addImportContent::addImportContentItemCallback',
    );
    batch_set($batch);
    
  }

   public function csvtoarray($filename='', $delimiter){

    if(!file_exists($filename) || !is_readable($filename)) return FALSE;
    $header = NULL;
    $data = array();

    if (($handle = fopen($filename, 'r')) !== FALSE ) {
      while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
      {
        if(!$header){
          $header = $row;
        }else{
          $data[] = array_combine($header, $row);
        }
      }
      fclose($handle);
    }

    return $data;
  }

}

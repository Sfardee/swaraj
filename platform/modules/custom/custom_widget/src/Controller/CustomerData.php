<?php

/**
 * @file
 * Contains \Drupal\first_module\Controller\SwarajSettings.
 */

namespace Drupal\custom_widget\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\webform\Entity\Webform;
use Drupal\webform\WebformSubmissionForm;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Flood\FloodInterface;
use Drupal\Component\Utility\Crypt;

class CustomerData extends ControllerBase
{
  /**
   * The flood service.
   *
   * @var \Drupal\Core\Flood\FloodInterface
   */
  protected $flood;

  public function __construct(FloodInterface $flood)
  {
    $this->flood = $flood;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('flood')
    );
  }

  /**
   * This function is called by main contact us form
   *
   * @return void
   */
  public function contactSubmit()
  {
    $person = \Drupal::request()->request->get('person');
    $request = \Drupal::request()->request->get('request');
    $name = \Drupal::request()->request->get('name');
    $email = \Drupal::request()->request->get('email');
    $number = \Drupal::request()->request->get('number');
    $company = !empty(\Drupal::request()->request->get('company')) ? (\Drupal::request()->request->get('company')) : '';
    $city = !empty(\Drupal::request()->request->get('city')) ? (\Drupal::request()->request->get('city')) : '';
    $state = !empty(\Drupal::request()->request->get('state')) ? (\Drupal::request()->request->get('state')) : '';
    $tehsil = !empty(\Drupal::request()->request->get('tehsil')) ? (\Drupal::request()->request->get('tehsil')) : '';
    $district = !empty(\Drupal::request()->request->get('district')) ? (\Drupal::request()->request->get('district')) : '';
    $vehicle = !empty(\Drupal::request()->request->get('vehicle')) ? (\Drupal::request()->request->get('vehicle')) : '';
    if (strtolower($vehicle) == 'tractor') {
      $model = !empty(\Drupal::request()->request->get('model')) ? implode(',', \Drupal::request()->request->get('model')) : '';
    } else {
      $model = !empty(\Drupal::request()->request->get('immodel')) ? implode(',', \Drupal::request()->request->get('immodel')) : '';
    }
    $spare = !empty(\Drupal::request()->request->get('spare')) ? (\Drupal::request()->request->get('spare')) : '';
    $date = !empty(\Drupal::request()->request->get('date')) ? (\Drupal::request()->request->get('date')) : '';
    if (strlen($date) < 6) {
      $date = '';
    }
    if ($date != '') {
      $date = str_replace('.', '-', $date);
      $date = str_replace('/', '-', $date);
      $date_arr = explode('-', $date);
      $date = str_pad($date_arr[0], 2, '0', STR_PAD_LEFT).'-'.str_pad($date_arr[1], 2, '0', STR_PAD_LEFT).'-'.str_pad($date_arr[2], 4, '20', STR_PAD_LEFT);
    }

    $time = !empty(\Drupal::request()->request->get('time')) ? (\Drupal::request()->request->get('time')) : '';
    if ($date != '' && $time == '') {
      $time = '9:30 AM';
    }
    $type = !empty(\Drupal::request()->request->get('type')) ? (\Drupal::request()->request->get('type')) : '';
    if($type == 'Information'){
      $title = !empty(\Drupal::request()->request->get('title1')) ? (\Drupal::request()->request->get('title1')) : '';
      $message = !empty(\Drupal::request()->request->get('message1')) ? (\Drupal::request()->request->get('message1')) : '';
    } else {
      $title = !empty(\Drupal::request()->request->get('title2')) ? (\Drupal::request()->request->get('title2')) : '';
      $message = !empty(\Drupal::request()->request->get('message2')) ? (\Drupal::request()->request->get('message2')) : '';
    }
    $token = sha1(mt_rand(1, 90000) . 'SALT');
    $email_recipients = custom_widget_get_feedback_email_recipients($request, $state, $vehicle);
    $isfarmmachinery = ($vehicle == 'tractor')? 'N': 'Y';
    if ($date != '') {
      $requestdate = substr($date,6).'-'.substr($date,3,2).'-'.substr($date,0,2) . ' ' . str_replace(' ', ':00 ', str_pad($time, 8, '0', STR_PAD_LEFT));
    } else {
      $requestdate = '';
    }
    $values = [
      'webform_id' => 'contact',
      'entity_type' => NULL,
      'entity_id' => NULL,
      'in_draft' => FALSE,
      'uid' => '1',
      'langcode' => 'en',
      'token' => $token,
      'uri' => '/contact',
      'remote_addr' =>  \Drupal::request()->getClientIp(),
      'data' => [
        'person' => $person,
        'request' => $request,
        'name' => $name,
        'subject' => $number,
        'email' => $email,
        'company' => $company,
        'city' => $city,
        'state' => $state,
        'tehsil' => $tehsil,
        'district' => $district,
        'vehicle' => $vehicle,
        'model' => $model,
        'spare_part' => $spare,
        'date' => $date,
        'time' => $time,
        'type' => $type,
        'title' => $title,
        'message' => $message,
        'mail_recipients' => $email_recipients,
        'isfarmmachinery' => $isfarmmachinery,
        'requestdate' => $requestdate,
      ],
    ];

    // Check webform is open.
    $webform = Webform::load($values['webform_id']);
    $is_open = WebformSubmissionForm::isOpen($webform);

    if ($is_open === TRUE) {
      // Validate submission.
      $errors = WebformSubmissionForm::validateFormValues($values);
      $result['id'] = 0;
      $result['msg'] = 'error';
      // Check there are no validation errors.
      if (!empty($errors)) {
        return new JsonResponse($result);
      }
      else {
        // Submit values and get submission ID.
        $webform_submission = WebformSubmissionForm::submitFormValues($values);
        // print $webform_submission->id();
        $result['id'] = $webform_submission->id();
        $result['msg'] = 'success';
        return new JsonResponse($result);
      }
    }
  }

  /**
   * This function is called by main New contact us form
   *
   * @return void
   */
  public function NewContactSubmit()
  {
    $result = array();
    $token = \Drupal::request()->request->get('contact_us_form_csrf_token');
    $temp_store_factory = \Drupal::service('session_based_temp_store');
    $temp_store = $temp_store_factory->get('new_contact_us_form', 4800);
    $csrf_token = $temp_store->get('contact_us_form_csrf_token');
    $temp_store->delete('contact_us_form_csrf_token');
    if ($csrf_token == $token) {
      $requestType = \Drupal::request()->request->get('selectService');
      $request = 'Tractor';
      if ($requestType == 'service-field') {
        $request = 'Service/Parts';
      }
      if ($requestType == 'farm-field') {
        $request = 'Farm Machinery';
      }
      if ($requestType == 'harvesters-field') {
        $request = 'Harvestors';
      }
      $name = \Drupal::request()->request->get('name');
      $email = \Drupal::request()->request->get('email_id');
      $number = \Drupal::request()->request->get('number');
      $state = !empty(\Drupal::request()->request->get('state')) ? (\Drupal::request()->request->get('state')) : '';
      $tehsil = !empty(\Drupal::request()->request->get('tehsil')) ? (\Drupal::request()->request->get('tehsil')) : '';
      $district = !empty(\Drupal::request()->request->get('district')) ? (\Drupal::request()->request->get('district')) : '';
      $models = '';
      if (strtolower($requestType) == 'tractor-field') {
        $models = !empty(\Drupal::request()->request->get('tractor_model')) ? implode(',', \Drupal::request()->request->get('tractor_model')) : '';
      } 
      if (strtolower($requestType) == 'harvesters-field') {
        $models = !empty(\Drupal::request()->request->get('harvestor_model')) ? implode(',', \Drupal::request()->request->get('harvestor_model')) : '';
      }
      $message = !empty(\Drupal::request()->request->get('message')) ? (\Drupal::request()->request->get('message')) : '';
      $isfarmmachinery = (strtolower($requestType) == 'tractor-field')? 'N': 'Y';

      
      // server side validation of data
      if ($name == '' || $number == '' || $state == '' || $tehsil == '' || $district == '') {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }
      if (strlen($name) < 3 || strlen($name) > 50 || strlen($number) != 10) {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }
      if (!preg_match("/^[A-za-z][A-Za-z '.]+$/", $name)) {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }
      if ($email != '') { 
        if (!preg_match('/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/', $email)) {
          $result['id'] = 0;
          $result['msg'] = 'Data validation error';
          return new JsonResponse($result);
        }
      }
      if (!preg_match("/^[6789]\d{9}$/", $number)) {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }

      if ($models != '') {
        if ($isfarmmachinery == 'N') { 
          $type_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('product_engine_power');
          $products = array();
          foreach ($type_terms as $type_term) {
            $details = _getEngineProducts($type_term->tid);
            if($details != NULL){
              foreach ($details as $detail) {
                $products[] = $detail['cdms_identifier'];
              }
            }
          }
        } else {
          $products = array();
          $implement_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('implements_category');
          foreach ($implement_terms as $type_term) {
            $details = _getImplements($type_term->tid);
            if($details != NULL) {
              if (stripos($type_term->name, 'Harvester')) {
                foreach ($details as $detail) {
                  $products[] = $detail['cdms_identifier'];
                }
              }
            }
          }
        }
        //$models = \Drupal::request()->request->get('model');
        foreach ($models as $val) {
          if (!in_array($val, $products)) {
            $result['id'] = 0;
            $result['msg'] = 'Data validation error';
            return new JsonResponse($result);
          }
        }
      }
      if (validate_location1_data($state, $district, $tehsil) < 1) {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }
      
      $token = sha1(mt_rand(1, 90000) . 'SALT');
      $email_recipients = custom_widget_get_new_feedback_email_recipients($request, $state);
      $values = [
        'webform_id' => 'contact_us_new',
        'entity_type' => NULL,
        'entity_id' => NULL,
        'in_draft' => FALSE,
        'uid' => '1',
        'langcode' => 'en',
        'token' => $token,
        'uri' => '/contact_us_new',
        'remote_addr' =>  \Drupal::request()->getClientIp(),
        'data' => [
          'request' => $request,
          'name' => $name,
          'subject' => $number,
          'email' => $email,
          'state' => $state,
          'tehsil' => $tehsil,
          'district' => $district,
          'isfarmmachinery' => $isfarmmachinery,
          'models' => $models,
          'message' => $message,
          'mail_recipients' => $email_recipients,
          'consent' => 1
        ],
      ];

      // Check webform is open.
      $webform = Webform::load($values['webform_id']);
      $is_open = WebformSubmissionForm::isOpen($webform);

      $text = 'contact_us_form';
      $hashedValue = Crypt::hashBase64($text);
      $floodIdentifier = \Drupal::request()->getClientIp() . $hashedValue;
 
      $this->flood->register('custom_widget.flood_protected_contact_us_form', 60, $floodIdentifier);

      $text = 'contact_us_form';
      $hashedValue = Crypt::hashBase64($text);
      $floodIdentifier = \Drupal::request()->getClientIp() . $hashedValue;
 
      if (!$this->flood->isAllowed('custom_widget.flood_protected_contact_us_form', 2, 60, $floodIdentifier)) {
        $result['id'] = 0;
        $result['msg'] = 'You cannot submit the form more than 1 time in 1 minute. Please, try again later.';
        return new JsonResponse($result, 429);
      } 

      if ($is_open === TRUE) {
        // Validate submission.
        $errors = WebformSubmissionForm::validateFormValues($values);
        $result['id'] = 0;
        $result['msg'] = 'error';
        // Check there are no validation errors.
        if (!empty($errors)) {
          return new JsonResponse($result);
        }
        else {
          // Submit values and get submission ID.
          $webform_submission = WebformSubmissionForm::submitFormValues($values);
          // print $webform_submission->id();
          $result['id'] = $webform_submission->id();
          $result['msg'] = 'success';
          $config = \Drupal::config('config.custom_widget');
          $result['thank_you'] = $config->get('contact_us_thank_you_link');
          return new JsonResponse($result);
        }
      }
    } else {
      $result['id'] = 0;
      $text = 'contact_us_form';
      $hashedValue = Crypt::hashBase64($text);
      $floodIdentifier = \Drupal::request()->getClientIp() . $hashedValue;
 
      if (!$this->flood->isAllowed('custom_widget.flood_protected_contact_us_form', 1, 60, $floodIdentifier)) {
        $result['msg'] = 'You cannot submit the form more than 1 time in 1 minute. Please, try again later.';
        return new JsonResponse($result, 429);
      } else {
        $result['msg'] = 'Token error';
        return new JsonResponse($result, 401);
      }
    }
  }

  /**
   * This function is called by dealer pages - contact form
   *
   * @return void
   */
  public function dealerContactSubmit()
  {
    $name = \Drupal::request()->request->get('name');
    $number = \Drupal::request()->request->get('number');
    $state = \Drupal::request()->request->get('state');
    $district = !empty(\Drupal::request()->request->get('district')) ? (\Drupal::request()->request->get('district')) : '';
    $message = !empty(\Drupal::request()->request->get('message')) ? (\Drupal::request()->request->get('message')) : '';
    $dealerId = \Drupal::request()->request->get('dealer_id');
    $dealerName = \Drupal::request()->request->get('dealer_name');
    $dealerState = \Drupal::request()->request->get('dealer_state');
    $dealerEmail = \Drupal::request()->request->get('dealer_email');
    $token = sha1(mt_rand(1, 90000) . 'SALT');
    $values = [
      'webform_id' => 'dealer_pages_contact_us_form',
      'entity_type' => NULL,
      'entity_id' => NULL,
      'in_draft' => FALSE,
      'uid' => '1',
      'langcode' => 'en',
      'token' => $token,
      'uri' => '/dealer_pages_contact_us_form',
      'remote_addr' =>  \Drupal::request()->getClientIp(),
      'data' => [
        'name' => $name,
        'subject' => $number,
        'state' => $state,
        'district' => $district,
        'message' => $message,
        'dealer_id' => $dealerId,
        'dealer_name' => $dealerName,
        'dealer_email' => $dealerEmail,
        'dealer_state' => $dealerState,
        'consent' => 1,
      ],
    ];

    // Check webform is open.
    $webform = Webform::load($values['webform_id']);
    $is_open = WebformSubmissionForm::isOpen($webform);

    if ($is_open === TRUE) {
      // Validate submission.
      $errors = WebformSubmissionForm::validateFormValues($values);
      $result['id'] = 0;
      $result['msg'] = 'error';
      // Check there are no validation errors.
      if (!empty($errors)) {
        return new JsonResponse($result);
      }
      else {
        // Submit values and get submission ID.
        $webform_submission = WebformSubmissionForm::submitFormValues($values);
        // print $webform_submission->id();
        $result['id'] = $webform_submission->id();
        $result['msg'] = 'success';
        return new JsonResponse($result);
      }
    }
  }

  /**
   * This function is called by enquiry form
   *
   * @return void
   */
  public function enquirySubmit()
  {
    $result = array();
    $token = \Drupal::request()->request->get('enquiry_form_csrf_token');
    $temp_store_factory = \Drupal::service('session_based_temp_store');
    $temp_store = $temp_store_factory->get('enquiry_form', 4800);
    $csrf_token = $temp_store->get('enquiry_form_csrf_token');
    if ($csrf_token == $token) {
      $temp_store->delete('enquiry_form_csrf_token');
      $name = \Drupal::request()->request->get('name');
      $number = \Drupal::request()->request->get('number');
      $model = !empty(\Drupal::request()->request->get('model')) ? implode(',', \Drupal::request()->request->get('model')) : '';
      $state = \Drupal::request()->request->get('state');
      $district = \Drupal::request()->request->get('district');
      $city = \Drupal::request()->request->get('city_village');
      $expected_date_of_delivery = !empty(\Drupal::request()->request->get('expected_date_of_delivery')) ? (\Drupal::request()->request->get('expected_date_of_delivery')) : '';

      // server side validation of data
      if ($name == '' || $number == '' || $model == '' || $state == '' || $city == '' || $district == '') {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }
      if (strlen($name) < 3 || strlen($name) > 50 || strlen($number) != 10) {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }
      if (!preg_match("/^[A-za-z][A-Za-z '.]+$/", $name)) {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }
      if (!preg_match("/^[6789]\d{9}$/", $number)) {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }

      $type_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('product_engine_power');
      $products = array();
      foreach ($type_terms as $type_term) {
        $details = _getEngineProducts($type_term->tid);
        if($details != NULL){
          foreach ($details as $detail) {
            $products[] = $detail['cdms_identifier'];
          }
        }
      }
      $models = \Drupal::request()->request->get('model');
      foreach ($models as $val) {
        if (!in_array($val, $products)) {
          $result['id'] = 0;
          $result['msg'] = 'Data validation error';
          return new JsonResponse($result);
        }
      }
      if (validate_location_data($state, $district, $city) < 1) {
        $result['id'] = 0;
        $result['msg'] = 'Data validation error';
        return new JsonResponse($result);
      }

      if ($expected_date_of_delivery != '') {
        if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/", $expected_date_of_delivery)) {
          $result['id'] = 0;
          $result['msg'] = 'Data validation error';
          return new JsonResponse($result);
        }
      }

      $token = sha1(mt_rand(1, 90000) . 'SALT');
      $values = [
        'webform_id' => 'enquiry_form',
        'entity_type' => NULL,
        'entity_id' => NULL,
        'in_draft' => FALSE,
        'uid' => '1',
        'langcode' => 'en',
        'token' => $token,
        'uri' => '/enquiry_form',
        'remote_addr' =>  \Drupal::request()->getClientIp(),
        'data' => [
          'name' => $name,
          'subject' => $number,
          'model' => $model,
          'state' => $state,
          'district' => $district,
          'city' => $city,
          'expected_date_of_delivery' => $expected_date_of_delivery,
          'consent' => 1,
        ],
      ];

      // Check webform is open.
      $webform = Webform::load($values['webform_id']);
      $is_open = WebformSubmissionForm::isOpen($webform);

      $text = 'enquiry_form';
      $hashedValue = Crypt::hashBase64($text);
      $floodIdentifier = \Drupal::request()->getClientIp() . $hashedValue;
 
      $this->flood->register('custom_widget.flood_protected_enquiry_form', 60, $floodIdentifier);

      $text = 'enquiry_form';
      $hashedValue = Crypt::hashBase64($text);
      $floodIdentifier = \Drupal::request()->getClientIp() . $hashedValue;
 
      if (!$this->flood->isAllowed('custom_widget.flood_protected_enquiry_form', 2, 60, $floodIdentifier)) {
        $result['id'] = 0;
        $result['msg'] = 'You cannot submit the form more than 1 time in 1 minute. Please, try again later.';
        return new JsonResponse($result, 429);
      } 

      if ($is_open === TRUE) {
        // Validate submission.
        $errors = WebformSubmissionForm::validateFormValues($values);
        $result['id'] = 0;
        $result['msg'] = 'error';
        // Check there are no validation errors.
        if (!empty($errors)) {
          return new JsonResponse($result);
        }
        else {
          // Submit values and get submission ID.
          $webform_submission = WebformSubmissionForm::submitFormValues($values);
          // print $webform_submission->id();
          $result['id'] = $webform_submission->id();
          $result['msg'] = 'success';
          return new JsonResponse($result);
        }
      }
    } else {
      $temp_store->delete('enquiry_form_csrf_token');
      $result['id'] = 0;
      $text = 'enquiry_form';
      $hashedValue = Crypt::hashBase64($text);
      $floodIdentifier = \Drupal::request()->getClientIp() . $hashedValue;
 
      if (!$this->flood->isAllowed('custom_widget.flood_protected_enquiry_form', 1, 60, $floodIdentifier)) {
        $result['msg'] = 'You cannot submit the form more than 1 time in 1 minute. Please, try again later.';
        return new JsonResponse($result, 429);
      } else {
        $result['msg'] = 'Token error';
        return new JsonResponse($result, 401);
      }
    }
  }

  public function checkDate()
  {
    $config = \Drupal::config('config.custom_widget');
    $datepicker = $config->get('show_date');
    if($datepicker){
      return new JsonResponse('show');
    } else {
      return new JsonResponse('hide');
    }
  }

  public function dealerFilter()
  {
    $lat = \Drupal::request()->request->get('lat');
    $long = \Drupal::request()->request->get('long');
    $pin = \Drupal::request()->request->get('pin');
    $city = \Drupal::request()->request->get('city');

    /*if(empty($pin)){
      $query = \Drupal::database()->query("SELECT pin FROM dealer_lat_long where city='".trim($city)."' order by id desc limit 0,1");
      $result8 = $query->fetchCol();
      $pin = $result8[0];
    }*/

    $city_cord = \Drupal::request()->request->get('city_cord');
    $limit = \Drupal::request()->request->get('limit');
    $results = array();

    $query1 = \Drupal::database()->select('dealer_lat_long', 'd');
    $query1->fields('d', ['dealer_id', 'lat', 'long']);
    if($pin != ''){
      if(is_numeric($pin)){
        $query1->condition('d.pin', $pin);
      } else {
        $query1->condition('d.city', strtolower($pin));
      }
      $results = $query1->execute()->fetchAll();
    }
    else{
      $query1->condition('d.city', trim($city));
      $results = $query1->execute()->fetchAll();
    }


    if (count($results) === 0) {
      if ($city_cord != 'NULL') {
        $city_cord = explode(',', $city_cord);
        $lat = $city_cord[0];
        $long = $city_cord[1];
        $pin = null;
        $query2 = \Drupal::database()->select('dealer_lat_long', 'd');
        $query2->fields('d', ['dealer_id', 'lat', 'long']);
        $results = $query2->execute()->fetchAll();
      }
    }

    $result1 = $result2 = $result3 = array();
    if(!empty($results)){
      foreach($results as $val){
        if(empty($pin)){
          $distance = distance($lat, $long, $val->lat, $val->long, 'K');
          if($distance <= 10){
            $result1[$distance] = $val->dealer_id;
          }
          else if($distance <= 15){
            $result2[$distance] = $val->dealer_id;
          }
          else {
            $result3[$distance] = $val->dealer_id;
          }
        } else {
          $result1[] = $val->dealer_id;
        }
      }
      $result = !empty($result1) ? $result1 : (!empty($result2) ? $result2 : $result3);
      ksort($result);
      if($limit == 3) {
        $result = array_slice($result, 0, 3, true);
      }
      foreach($result as $dist => $id){
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'dealer');
        $query->condition('title', $id);
        $entity_ids = $query->execute();
        $nid = array_shift($entity_ids);
        $node = Node::load($nid);
        $data[$nid]['title'] = $node->field_dealer_name->value;
        $data[$nid]['description'] = str_replace(array(',,', ', ,'),',', $node->field_address_line_1->value . ', ' . $node->field_address_line_2->value . ', ' . $node->field_city->value . ':- ' . $node->field_pincode->value . ', ' . $node->field_district->value . ', ' . $node->field_state->value);
        $data[$nid]['lat'] = $node->field_latitude->value;
        $data[$nid]['long'] = $node->field_longitude->value;
        $data[$nid]['email'] = $node->field_email->value;
        $data[$nid]['contactN'] = $node->field_mobile->value;
        $data[$nid]['salesHN'] = $node->field_person->value;
        $data[$nid]['dist'] = $dist;
        $data[$nid]['city'] = strtolower($node->field_city->value);
        $data[$nid]['pin'] = $node->field_pincode->value;
        $data[$nid]['image'] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']."/themes/swaraj/images/map-pin.png";
      }
      // \Drupal::logger('debugging')->warning(print_r($data, TRUE));
      return new JsonResponse($data);
    } else {
      $data = array("No Results Found");
      return new JsonResponse($data);
    }
  }

  public function getCustomerData(){ 
    $user = User::load(1);
    user_login_finalize($user);
    $leads = array();
    $token = "hrbjqgbJd0q2LCp40IMVCEMCgRHwUkeD-AQKjMR_QkE";
    $time = \Drupal::request()->get('timestamp');
    $req_token = \Drupal::request()->get('token');
    $query = \Drupal::entityQuery('webform_submission');
    if($time){
      $query->condition('created', $time, '>=');
    }
    $results = $query->execute();
    if($results){
      foreach($results as $id){
        $forms = WebformSubmission::load($id);
        $data = $forms->getData();
        $leads[$id]['CustomerName'] = $data['name'];
        $leads[$id]['MobileNo'] = $data['subject'];
        $leads[$id]['State'] = $data['state'];
        $leads[$id]['city'] = $data['city'];
        $leads[$id]['tehsil'] = $data['tehsil'];
        $leads[$id]['district'] = $data['district'];
        if (strtolower($data['vehicle']) == 'tractor') {
          $leads[$id]['PrefferedModel'] = $data['model'];
        } else {
          $leads[$id]['PrefferedModel'] = '';
        }
        $leads[$id]['EnquiryDate'] = date('d/m/Y', $forms->getCreatedTime());
      }
    }
    $user = User::load(0);
    user_login_finalize($user);
    if($leads && $token == $req_token){
      return new JsonResponse($leads);
    } else {
      $leads = "No Results Found";
      return new JsonResponse($leads);
    }
  }

  /**
   * get district list
   *
   * @return void
   */
  public function getDistrict(request $request)
  {
    $state = $request->query->get('state');
    $districts = get_district($state);
    return new JsonResponse($districts);
  }

  /**
   * get tehsil list
   *
   * @return void
   */
  public function getTehsil(request $request)
  {
    $state = $request->query->get('state');
    $district = $request->query->get('district');
    $tehsil = get_tehsil($state, $district);
    return new JsonResponse($tehsil);
  }

  /**
   * get village list
   *
   * @return void
   */
  public function getVillage(request $request)
  {
    $state = $request->query->get('state');
    $district = $request->query->get('district');
    $tehsil = $request->query->get('tehsil');
    $village = get_village($state, $district, $tehsil);
    return new JsonResponse($village);
  }

  /**
   * get city / village list for enquiry form
   *
   * @return void
   */
  public function getCityVillage(request $request)
  {
    $state = $request->query->get('state');
    $district = $request->query->get('district');
    $cityVillage = get_city_village($state, $district);
    return new JsonResponse($cityVillage);
  }

  /**
   * get city list - for map
   *
   * @return void
   */
  public function getCity(request $request)
  {
    $state = $request->query->get('state');
    $city = get_city($state);
    ksort($city);
    return new JsonResponse($city);
  }
}
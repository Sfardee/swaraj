<?php
namespace Drupal\custom_widget\Plugin;

use Drupal\node\Entity\Node;

class addImportContent {

  public static function addImportContentItem($item, &$context){

    $context['sandbox']['current_item'] = $item;
    $message = 'Creating ' . utf8_encode(trim($item['dlrCode']));
    $results = array();
    $deal_dist = utf8_encode(trim($item['dlrdist']));
    $query = \Drupal::entityQuery('node');
    $query->condition('type', $deal_dist);
    $query->condition('title', utf8_encode(trim($item['dlrCode'])));
    $entity_id = $query->execute();
    if (empty($entity_id)) {
      create_node($item);
    } else {
      create_node($item, array_shift($entity_id));
    }
    $context['message'] = $message;
    $context['results'][] = $item;
  }


  function addImportContentItemCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One item processed.', '@count items processed.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
    drupal_set_message($message);
  }
}

// This function actually creates each item as a node as type 'Page'
function create_node($item, $nid = NULL) {
  $node_data['type'] = utf8_encode(trim($item['dlrdist']));
  $node_data['title'] = utf8_encode(trim($item['dlrCode']));
  $node_data['field_zone']['value'] = utf8_encode(trim($item['zone']));
  $node_data['field_dealer']['value'] = utf8_encode(trim($item['dealer']));
  $node_data['field_doa']['value'] = utf8_encode(trim($item['doa']));
  $node_data['field_dealer_name']['value'] = utf8_encode(trim($item['dealershipName']));
  $node_data['field_address_line_1']['value'] = utf8_encode(trim($item['addr1']));
  $node_data['field_address_line_2']['value'] = utf8_encode(trim($item['addr2']));
  $node_data['field_city']['value'] = utf8_encode(trim($item['city']));
  $node_data['field_district']['value'] = utf8_encode(trim($item['district']));
  $node_data['field_state']['value'] = utf8_encode(trim($item['state']));
  $node_data['field_pincode']['value'] = utf8_encode(trim($item['pincode']));
  $node_data['field_longitude']['value'] = utf8_encode(trim($item['longitude']));
  $node_data['field_latitude']['value'] = utf8_encode(trim($item['latitude']));
  $node_data['field_person']['value'] = utf8_encode(trim($item['contactPerson']));
  $node_data['field_email']['value'] = utf8_encode(trim($item['emailId']));
  $node_data['field_mobile']['value'] = utf8_encode(trim($item['mobileNo']));
  $node_data['field_phone']['value'] = utf8_encode(trim($item['phoneNo']));
  $node_data['field_constitution']['value'] = utf8_encode(trim($item['CONSTITUTION']));
  $node_data['field_lst']['value'] = utf8_encode(trim($item['LST']));
  $node_data['field_cst']['value'] = utf8_encode(trim($item['CST']));
  $node_data['field_gstin']['value'] = utf8_encode(trim($item['GSTIN']));
  $node_data['field_pan_no']['value'] = utf8_encode(trim($item['PanNo']));
  $node_data['field_working_hours_day1']['value'] = utf8_encode(trim($item['working-hours-Day1']));
  $node_data['field_working_hours_day2']['value'] = utf8_encode(trim($item['working-hours-Day2']));
  $node_data['field_working_hours_day3']['value'] = utf8_encode(trim($item['working-hours-Day3']));
  $node_data['field_working_hours_day4']['value'] = utf8_encode(trim($item['working-hours-Day4']));
  $node_data['field_working_hours_day5']['value'] = utf8_encode(trim($item['working-hours-Day5']));
  $node_data['field_working_hours_day6']['value'] = utf8_encode(trim($item['working-hours-Day6']));
  $node_data['field_working_hours_day7']['value'] = utf8_encode(trim($item['working-hours-Day7']));

  // store data in custom table for easy search of dealer
  if (trim($item['dlrdist']) == 'dealer') {
    $query = \Drupal::database()->delete('dealer_lat_long');
    $query->condition('dealer_id', utf8_encode(trim($item['dlrCode'])));
    $query->execute();
    $data = db_insert('dealer_lat_long')
      ->fields(array(
          'dealer_id' => utf8_encode(trim($item['dlrCode'])),
          'lat' => utf8_encode(trim($item['latitude'])),
          'long' => utf8_encode(trim($item['longitude'])),
          'pin' => utf8_encode(trim($item['pincode'])),
          'city' => strtolower(utf8_encode(trim($item['city']))),
          'state' => strtolower(utf8_encode(trim($item['state']))),
        )
      )->execute();
  }
  // Setting a simple textfield to add a unique ID so we can use it to query against if we want to manipulate this data again.
  if(!empty($nid)){
    $node = Node::load($nid);
    $node->field_zone->value = utf8_encode(trim($item['zone']));
    $node->field_dealer->value = utf8_encode(trim($item['dealer']));
    $node->field_doa->value = utf8_encode(trim($item['doa']));
    $node->field_dealer_name->value = utf8_encode(trim($item['dealershipName']));
    $node->field_address_line_1->value = utf8_encode(trim($item['addr1']));
    $node->field_address_line_2->value = utf8_encode(trim($item['addr2']));
    $node->field_city->value = utf8_encode(trim($item['city']));
    $node->field_district->value = utf8_encode(trim($item['district']));
    $node->field_state->value = utf8_encode(trim($item['state']));
    $node->field_pincode->value = utf8_encode(trim($item['pincode']));
    $node->field_longitude->value = utf8_encode(trim($item['longitude']));
    $node->field_latitude->value = utf8_encode(trim($item['latitude']));
    $node->field_person->value = utf8_encode(trim($item['contactPerson']));
    $node->field_email->value = utf8_encode(trim($item['emailId']));
    $node->field_mobile->value = utf8_encode(trim($item['mobileNo']));
    $node->field_phone->value = utf8_encode(trim($item['phoneNo']));
    $node->field_constitution->value = utf8_encode(trim($item['CONSTITUTION']));
    $node->field_lst->value = utf8_encode(trim($item['LST']));
    $node->field_cst->value = utf8_encode(trim($item['CST']));
    $node->field_gstin->value = utf8_encode(trim($item['GSTIN']));
    $node->field_pan_no->value = utf8_encode(trim($item['PanNo']));
    $node->field_working_hours_day1->value = utf8_encode(trim($item['working-hours-Day1']));
    $node->field_working_hours_day2->value = utf8_encode(trim($item['working-hours-Day2']));
    $node->field_working_hours_day3->value = utf8_encode(trim($item['working-hours-Day3']));
    $node->field_working_hours_day4->value = utf8_encode(trim($item['working-hours-Day4']));
    $node->field_working_hours_day5->value = utf8_encode(trim($item['working-hours-Day5']));
    $node->field_working_hours_day6->value = utf8_encode(trim($item['working-hours-Day6']));
    $node->field_working_hours_day7->value = utf8_encode(trim($item['working-hours-Day7']));
  } else {
    $node = Node::create($node_data);
  }
  if(trim($item['activeFlag']) == 1){
   $node->status = NODE_PUBLISHED;
  } else {
   $node->status = NODE_NOT_PUBLISHED;
  }
  // copy panel page body
  $config = \Drupal::service('entity_type.manager')->getStorage('config_pages')->load('dealer_pages_configurations'); 
  $panel_node_id = $config->get('field_node_id_of_dealer_panel_pg')->getValue()[0]['value'];
  if (is_numeric($panel_node_id)) {
    $panel_node = Node::load($panel_node_id);
    $node->panelizer = $panel_node->panelizer;
  }
  $node->set('field_meta_tags', serialize([
    'title' => '[node:field_dealer_name] | [site:name]',
    ]));
  $node->save();
}

<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a 'Implements Details' block.
 *
 * @Block(
 *   id = "implementsdetails",
 *   admin_label = @Translation("Implements Details"),
 *   category = @Translation("Custom")
 * )
 */
class ImplementsDetails extends BlockBase
{

  public function build()
  {
    $data = $media_gallery = array();
    $node = \Drupal::routeMatch()->getParameter('node');
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if($node->hasTranslation($language)){
      $node = $node->getTranslation($language);
    }
    if ($node instanceof \Drupal\node\NodeInterface) {
      $nid = $node->id();
      if (trim(strip_tags($node->body->value)) == '') {
        $config = \Drupal::config('config.custom_widget');
        $list_nid = !empty($config->get('implements_list_nid'))? $config->get('implements_list_nid'): '128';
        // redirect to implement listing
        $url = Url::fromRoute('entity.node.canonical', ['node' => $list_nid])->toString();
        $response = new RedirectResponse($url, '302');
        $response->send();
        exit;
      }
      $para_id = $node->get('field_breadcrumbs')->getValue();
      foreach ($para_id as $key => $val) {
        $para = Paragraph::load($val['target_id']);
        if ($para->hasTranslation($language)) {
          $para = $para->getTranslation($language);
        }
        $res['title'] = $para->field_name->value;
        $link = $para->field_link->uri;
        $res['link'] = !empty($link) ? Url::fromUri($link) : '';
        $page_breadcrumbs[] = $res;
      }
      $data['page_breadcrumbs'] = $page_breadcrumbs;
      $data['title'] = !empty($node->title->value) ? $node->title->value : '';
      $data['rthp'] = !empty($node->field_horsepower->value) ? $node->field_horsepower->value : '';
      $data['ehp'] = !empty($node->field_engine->value) ? $node->field_engine->value : '';
      $data['nb'] = !empty($node->field_pto_hp->value) ? $node->field_pto_hp->value : '';
      $data['bsz'] = !empty($node->field_brakes->value) ? $node->field_brakes->value : '';
      $data['cbw'] = !empty($node->field_cylinders->value) ? $node->field_cylinders->value : '';
      $data['sz'] = !empty($node->field_wheel_drive->value) ? $node->field_wheel_drive->value : '';
      $data['gtc'] = !empty($node->field_gearbox->value) ? $node->field_gearbox->value : '';
      $data['bw'] = !empty($node->field_latitude->value) ? $node->field_latitude->value : '';
      $data['psr'] = !empty($node->field_longitude->value) ? $node->field_longitude->value : '';
      $data['nob'] = !empty($node->field_address->value) ? $node->field_address->value : '';
      $data['desc'] = !empty($node->body->value) ? $node->body->value : '';
      $data['warimg'] = !empty($node->field_warranty->target_id) ? _getmediaurl($node->field_warranty->target_id, 'image') : '';
      $data['pdf'] = !empty($node->field_brochure->target_id) ? _getmediaurl($node->field_brochure->target_id, 'file') : '';
      $options = ['absolute' => TRUE];
      $data['link'] = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $nid], $options)->toString();
      $ids = $node->field_implement_image;
      foreach($ids as $i => $id) {
        $media_gallery[] = !empty($id->target_id) ? _getproductmediaurl($id->target_id) : '';
      }
      $data['media_gallery'] = $media_gallery;
    }
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_implements_details',
    );
  }
}
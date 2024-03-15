<?php

namespace Drupal\custom_widget\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Url;

/**
 * Provides a 'Details' block.
 *
 * @Block(
 *   id = "details",
 *   admin_label = @Translation("Details"),
 *   category = @Translation("Custom")
 * )
 */
class Details extends BlockBase
{

  public function build()
  {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    
    $data = $media_gallery = array();
    $node = \Drupal::routeMatch()->getParameter('node');
    if($node->hasTranslation($language)){
      $node = $node->getTranslation($language);
    }
    if ($node instanceof \Drupal\node\NodeInterface) {
      $nid = $node->id();
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
      $data['title'] = !empty($node->title->value) ? str_ireplace('swaraj ', '', $node->title->value) : '';
      $data['title_new'] = !empty($node->title->value) ? $node->title->value : '';
      $data['hp'] = !empty($node->field_horsepower->value) ? $node->field_horsepower->value : '';
      $data['cc'] = !empty($node->field_engine->value) ? $node->field_engine->value : '';
      $data['pto'] = !empty($node->field_pto_hp->value) ? $node->field_pto_hp->value : '';
      $data['br'] = !empty($node->field_brakes->value) ? $node->field_brakes->value : '';
      $data['cyl'] = !empty($node->field_cylinders->value) ? $node->field_cylinders->value : '';
      $data['wd'] = !empty($node->field_wheel_drive->value) ? $node->field_wheel_drive->value : '';
      $data['gb'] = !empty($node->field_gearbox->value) ? $node->field_gearbox->value : '';
      $data['desc'] = !empty($node->body->value) ? $node->body->value : '';
      $data['warimg'] = !empty($node->field_warranty->target_id) ? _getmediaurl($node->field_warranty->target_id, 'image') : '';
      $data['pdf'] = !empty($node->field_brochure->target_id) ? _getmediaurl($node->field_brochure->target_id, 'file') : '';
      $options = ['absolute' => TRUE];
      $data['link'] = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $nid], $options)->toString();
      $ids = $node->field_image;
      foreach($ids as $i => $id) {
        $media_gallery[] = !empty($id->target_id) ? _getproductmediaurl($id->target_id) : '';
      }
      $data['media_gallery'] = $media_gallery;
    }
    
    return array(
      '#data' => $data,
      '#theme' => 'swaraj_product_details',
    );
  }
}
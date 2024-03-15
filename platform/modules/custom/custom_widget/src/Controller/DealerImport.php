<?php

/**
 * @file
 * Contains \Drupal\custom_widget\Controller\DealerImport.
 */

namespace Drupal\custom_widget\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Access\AccessResult; 
use Drupal\Core\Form\FormInterface;

class DealerImport extends ControllerBase
{
  public function dealerImport()
  {
    $form = \Drupal::formBuilder()->getForm('Drupal\custom_widget\Form\ImportForm');
    return $form;
  }
}
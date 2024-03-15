<?php

/**
 * @file
 * Contains \Drupal\first_module\Controller\SwarajSettings.
 */

namespace Drupal\custom_widget\Controller;

use Drupal\Core\Controller\ControllerBase;

class SwarajSettings extends ControllerBase
{
  public function content()
  {
    return array(
      '#markup' => '<h1>Swaraj System settings</h1>',
    );
  }
}
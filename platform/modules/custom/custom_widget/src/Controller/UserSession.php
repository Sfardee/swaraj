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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserSession extends ControllerBase
{
  public function userSessionUpdate()
  {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    if (\Drupal::currentUser()->id() > 0) {
      $user->setLastAccessTime(REQUEST_TIME);
      $user->save();
      $result['msg'] = 'ok';
    } else {
      $result['msg'] = 'logout';
      drupal_set_message('Your session was expired due to session timeout', 'info');
    }
    return new JsonResponse($result);
  }

}
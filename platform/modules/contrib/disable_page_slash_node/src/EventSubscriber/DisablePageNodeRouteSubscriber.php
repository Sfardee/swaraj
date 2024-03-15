<?php

namespace Drupal\disable_page_slash_node\EventSubscriber;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class DisablePageNodeRouteSubscriber
 *
 * @package Drupal\disable_page_slash_node\Routing
 */
class DisablePageNodeRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('system.site_information_settings')) {
      $route->setDefault('_form', 'Drupal\disable_page_slash_node\Form\DisablePageNodeSiteInformationForm');
    }
  }

}

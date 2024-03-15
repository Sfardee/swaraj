<?php
/**
 * @file `src/Routing/Enhancer/HomepageEnhancer.php`
 *
 * The HomepageEnhancer class is responsible for supplying the current homepage
 * node that should be displayed dependent on the schedule.
 *
 * This module assumes the `homepage` content type with the `field_schedule`
 * daterange field has been created.
 */

namespace Drupal\custom_widget\Routing\Enhancer;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Routing\EnhancerInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class HomepageEnhancer implements EnhancerInterface
{

    /**
     * The query factory.
     *
     * @var \Drupal\Core\Entity\Query\QueryFactoryInterface
     */
    protected $entity_query;

    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entity_type_manager;

    /**
     * Constructs an HomepageEnhancer instance.
     *
     * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
     *   The entity resolver manager.
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     *   The entity type manager.
     */
    public function __construct(QueryFactory $entity_query, EntityTypeManagerInterface $entity_type_manager)
    {
        $this->entity_query = $entity_query;
        $this->entity_type_manager = $entity_type_manager;
    }

    /**
     * {@inheritdoc}
     */
    public function enhance(array $defaults, Request $request)
    {
        if (!empty($defaults['node'])) {
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                $protocol = stripos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0 ? 'https://' : 'http://';
            } elseif (isset($_SERVER['SERVER_PROTOCOL'])) {
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
            } else {
                $protocol = 'http://';
            }
            $baseUrl = $protocol . strtolower($_SERVER['HTTP_HOST']);
            if (stripos($baseUrl, 'enquiryform.')) {
                if ($_SERVER['REQUEST_URI'] == '/') {
                    $config = \Drupal::service('entity_type.manager')->getStorage('config_pages')->load('enquiry_form_configuration'); 
                    $nid = $config->get('field_node_id_of_enquiry_form_pa')->getValue()[0]['value'];
                }
            }

            if (!empty($nid)) {
                // Get the Symfony route object.
                $route = $defaults[RouteObjectInterface::ROUTE_OBJECT];
                // If we don't set the default nid route match won't work meaning that access checks will fail and node context won't be available.
                $route->setDefault('node', $nid);
                // This is the enhancement part, setting the node object.
                $defaults['node'] = $this->entity_type_manager->getStorage('node')->load($nid);
                \Drupal::service('page_cache_kill_switch')->trigger();
            }
        }

        return $defaults;
    }

    /**
     * {@inheritdoc}
     */
    public function applies(Route $route)
    {
        // Only applies to the homepage route.
        return true;
    }

}

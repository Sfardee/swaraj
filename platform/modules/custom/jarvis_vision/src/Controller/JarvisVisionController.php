<?php

namespace Drupal\jarvis_vision\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Plugin\Context\ContextHandlerInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Render\RendererInterface;
use Drupal\layout_plugin\Plugin\Layout\LayoutPluginManagerInterface;
use Drupal\panels\Storage\PanelsStorageManagerInterface;
use Drupal\panels_ipe\Helpers\RemoveBlockRequestHandler;
use Drupal\panels_ipe\Helpers\UpdateLayoutRequestHandler;
use Drupal\panels_ipe\PanelsIPEBlockRendererTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\user\SharedTempStoreFactory;
use Drupal\panels_ipe\Controller\PanelsIPEPageController;

class JarvisVisionController extends PanelsIPEPageController {

  /**
   * The Panels storage manager.
   *
   * @var \Drupal\panels\Storage\PanelsStorageManagerInterface
   */
  protected $panelsStorage;

  /**
   * @var \Drupal\user\SharedTempStore
   */
  protected $tempStore;

  /**
   * Drupal AJAX compatible route for rendering a given Block Plugin's form.
   *
   * @param string $panels_storage_type
   *   The id of the storage plugin.
   * @param string $panels_storage_id
   *   The id within the storage plugin for the requested Panels display.
   * @param string $plugin_id
   *   The requested Block Plugin ID.
   * @param string $block_uuid
   *   The Block UUID, if this is an existing Block.
   *
   * @return Response
   */
  public function getBlockStylePluginForm($panels_storage_type, $panels_storage_id, $plugin_id, $block_uuid = NULL) {
    $panels_display = $this->loadPanelsDisplay($panels_storage_type, $panels_storage_id);

    // Build a Block Plugin configuration form.
    $form = $this->formBuilder()->getForm('Drupal\jarvis_vision\Form\JvBlockStyleForm', $plugin_id, $panels_display, $block_uuid);

    // Return the rendered form as a proper Drupal AJAX response.
    $response = new AjaxResponse();
    $command = new AppendCommand('.ipe-block-form', $form);
    $response->addCommand($command);
    return $response;
  }
}
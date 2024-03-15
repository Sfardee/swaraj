<?php

namespace Drupal\disable_page_slash_node\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;


/**
 * Class RedirectNodeRouteSubscriber
 *
 * @package Drupal\disable_page_slash_node\EventSubscriber
 */
class RedirectNodeRouteSubscriber implements EventSubscriberInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * RedirectNodeRouteSubscriber constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   The language manager.
   */
  public function __construct(ConfigFactoryInterface $configFactory, LanguageManagerInterface $languageManager) {
    $this->configFactory = $configFactory;
    $this->languageManager = $languageManager;
  }

  /**
   * Redirect path /node to a single slash.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The GetResponseEvent to process.
   */
  public function redirectNodeUrl(GetResponseEvent $event) {

    $config = $this->configFactory->get('system.site');
    $pageSlashNode = $config->get('site_disable_page_node');

    if (!$pageSlashNode) {

      $request = $event->getRequest();
      $getRequestUri = $request->getRequestUri();

      if ($getRequestUri === '/node') {

        $options = [];

        if ($this->languageManager->isMultilingual()) {
          $getLanguage = $this->languageManager->getCurrentLanguage();
          $options = ['language' => $getLanguage];
        }

        $url = Url::fromRoute('<front>', [], $options);
        $response = new RedirectResponse($url->toString());
        $response->send();

      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['redirectNodeUrl', 1000];
    return $events;
  }

}

<?php
namespace Drupal\custom_widget\EventSubscriber;

use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Routing\TrustedRedirectResponse;

class CustomWidgetSubscriber implements EventSubscriberInterface {

  public function checkForSessionIpBinding(GetResponseEvent $event) {
    $current_user = \Drupal::currentUser();
    $current_path = \Drupal::service('path.current')->getPath();
    if ($current_path == '/user/login') {
      $query = \Drupal::database()->select('sessions', 's');
      $query->join('users_field_data', 'u', 'u.uid = s.uid');
      $query->fields('s', ['sid']);
      $query->condition('s.timestamp', REQUEST_TIME - 60, '<');
      $results = $query->execute()->fetchAll();
      foreach($results as $usr_session) {
        \Drupal::database()->delete('sessions')
        ->condition('sid', $usr_session->sid, '=')
        ->execute();
      }      
    }
    if ($current_user->id() > 0 && $current_path != '/user/logout') {
        $last_access_time = $current_user->getLastAccessedTime();
        $diff = REQUEST_TIME - $last_access_time;
        $tempstore = \Drupal::service('user.private_tempstore')->get('custom_widget');
        $just_login = $tempstore->get('user_just_login');
        if ($just_login == 'no' && $diff > 30) {
          \Drupal::logger('user')->error('user session expired due to browser close');
          drupal_set_message('Your session was expired due to session timeout', 'info');
          if ($current_path == '/user-session-update') {
            $result['msg'] = 'logout';
            return new JsonResponse($result);
          } else {
            $event->setResponse(new RedirectResponse('/user/logout'));
          }
        } elseif ($just_login == 'no') {
          $user_session_ip = $tempstore->get('user_ip');
          $user_current_ip = \Drupal::request()->getClientIp(); 
          // \Drupal::logger('user')->error('user_session_ip-'.$user_session_ip);
          // \Drupal::logger('user')->error('user_current_ip-'.$user_current_ip);
          /* IP check disabled.
          if ($user_current_ip != $user_session_ip) {
              \Drupal::logger('user')->error('user session expired due to IP change');
              drupal_set_message('Your session was expired due to IP / network change', 'info');
              $tempstore->set('user_ip', '');
              if ($current_path == '/user-session-update') {
                $result['msg'] = 'logout';
                return new JsonResponse($result);
              } else {
                $event->setResponse(new RedirectResponse('/user/logout'));
              }
          }
          */
        } else {
          $tempstore->set('user_just_login', 'no');
        }
    }
  }

  /**
   * function to manage dealer and enquiryform subdomain
   *
   * @param GetResponseEvent $event
   * @return void
   */
  public function checkForRedirection(GetResponseEvent $event) {
    if (stripos($_SERVER['REQUEST_URI'], 'get-district') === FALSE && stripos($_SERVER['REQUEST_URI'], 'get-city-village') === FALSE && stripos($_SERVER['REQUEST_URI'], 'get-city') === FALSE && stripos($_SERVER['REQUEST_URI'], 'thank-you') === FALSE && stripos($_SERVER['REQUEST_URI'], 'dealer-contact-submit') === FALSE && stripos($_SERVER['REQUEST_URI'], 'enquiry-submit') === FALSE) {
      // dealer subdomain specific code
      if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $protocol = stripos($_SERVER['HTTP_X_FORWARDED_PROTO'],'https') === 0 ? 'https://' : 'http://';
      } elseif (isset($_SERVER['SERVER_PROTOCOL'])) {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
      } else {
        $protocol = 'http://';
      }
      $baseUrl = $protocol . strtolower($_SERVER['HTTP_HOST']);
      $attr = $event->getRequest()->attributes;
      if(null !== $attr &&
        null !== $attr->get('node') &&
        $attr->get('node')->get('type')->getString() == 'dealer') {
          if (stripos($baseUrl, 'www.')) {
            $redirectURL = $protocol . str_replace('www.', 'dealer.', $_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];
            \Drupal::service('page_cache_kill_switch')->trigger();
            $event->setResponse(new TrustedRedirectResponse($redirectURL));
          } elseif (stripos($baseUrl, 'enquiryform.')) {
            $redirectURL = $protocol . str_replace('enquiryform.', 'dealer.', $_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];
            \Drupal::service('page_cache_kill_switch')->trigger();
            $event->setResponse(new TrustedRedirectResponse($redirectURL));
          } elseif (!stripos($baseUrl, 'enquiryform.') && !stripos($baseUrl, 'www.') && !stripos($baseUrl, 'dealer.')) {
            $redirectURL = $protocol . 'dealer.' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            \Drupal::service('page_cache_kill_switch')->trigger();
            $event->setResponse(new TrustedRedirectResponse($redirectURL));
          }
      } else {
        // special case of enquiry form sub-domain
        if (stripos($baseUrl, 'enquiryform.')) {
          if (stripos($_SERVER['REQUEST_URI'], 'thank-you') === FALSE && stripos($_SERVER['REQUEST_URI'], 'get-district') === FALSE && stripos($_SERVER['REQUEST_URI'], 'get-city-village') === FALSE && stripos($_SERVER['REQUEST_URI'], 'enquiry-submit') === FALSE && $_SERVER['REQUEST_URI'] !== '/') {
            $redirectURL = $protocol . $_SERVER['HTTP_HOST'];
            \Drupal::service('page_cache_kill_switch')->trigger();
            $event->setResponse(new TrustedRedirectResponse($redirectURL));
          }
        }
        if (stripos($baseUrl, 'dealer.')) {
          $redirectURL = $protocol . str_replace('dealer.', 'www.', $_SERVER['HTTP_HOST']) . $_SERVER['REQUEST_URI'];
          \Drupal::service('page_cache_kill_switch')->trigger();
          $event->setResponse(new TrustedRedirectResponse($redirectURL));
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkForSessionIpBinding');
    $events[KernelEvents::REQUEST][] = array('checkForRedirection');
    return $events;
  }

}

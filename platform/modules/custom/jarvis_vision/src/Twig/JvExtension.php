<?php

/**
 * @file
 * Contains \Drupal\jarvis_vision\Twig\JvExtension.
 */

namespace Drupal\jarvis_vision\Twig;

use Drupal\Core\Template\TwigExtension;
use Twig_SimpleFunction;
use Drupal\Core\Url;

class JvExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'jarvis_vision';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('url_from_uri', array($this, 'urlFromUri')),
      new \Twig_SimpleFunction('hexdec', array($this, 'hexColorCodeToRgb')),
      new \Twig_SimpleFunction('echopre', array($this, 'echopre'), array('is_safe' => array('html'))),
    );
  }

  public function getFilters() {
    return [
      new \Twig_SimpleFilter('t', array($this, 't')),
    ];
  }

  /**
     * Convert the internal links into proper alias or node path.
     * Each argument defines the size of the windowin the next higher level
     * E.g. array_window($arr, 4, 3) will create a number of branches, with 3 windows having a size of 4 each. 
     * The node must all have a size variable otherwise the size is assumed to be 1.
     *
     * @param  string  $link
     * @return string
     */
  public function urlFromUri($link) {
    if(!empty($link))
      $output = Url::fromUri($link);
    else
      $output = $link;

    return $output;
  }

  public function hexColorCodeToRgb($color_code) {
    $r = hexdec(substr($color_code, 0, 2));
    $g = hexdec(substr($color_code, 2, 2));
    $b = hexdec(substr($color_code, 4, 2));
    return array($r, $g, $b);
  }

  public function echopre($array) {
    echopre($array);
  }

  public function t($string) {
    if(!empty($string) && is_string($string)) { 
      $string = t($string); 
    }
    return $string;
  }
}
<?php
namespace Drupal\jarvis_vision;

use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Url;

class JarvisTranslation {

  protected $langcode;
  protected $language;

  public function __construct() {
    $this->langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $this->language = \Drupal::languageManager()->getLanguage($this->langcode);
  }

  // -- Get Translated Node
  public function getNode($nid, $node = array()) {
    if(empty($node)) {
      $node = Node::load($nid);
    }
    return \Drupal::service('entity.repository')->getTranslationFromContext($node, $this->langcode);
  }

  // -- Get Translated Term
  public function getTerm($tid, $term = array()) {
    $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();

    if(empty($term)) {
      $term = Term::load($tid);
    }
    return \Drupal::service('entity.repository')->getTranslationFromContext($term, $this->langcode);
  }

  // -- Get Term name by ID, will return based on the current language.
  public function getTermNameById($tid, $term = array()) {
    $term = $this->getTerm($tid, $term);

    return $term->get('name')->value;
  }

  // -- Get Term ID by Name & vocabulary, will return based on the current language.
  public function getTidByName($name = NULL, $vid = NULL) {
    $properties = [];
    if (!empty($name)) {
      $properties['name'] = $name;
    }
    if (!empty($vid)) {
      $properties['vid'] = $vid;
    }
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadByProperties($properties);
    $term = reset($terms);

    return !empty($term) ? $term->id() : 0;
  }

  // Get the Node title by node Id, will return the title based on current langauge.
  public function getNodeTitleById($nid, $node=array()) {
    $node = $this->getNode($nid, $node);

    return $node->get('title')->value;
  }

  public function urlFromUri($uri, $options=array()) {
    $options['language'] = $this->language;
    
    $url = Url::fromUri($uri, $options);
    return $url->toString();
  }

  public function loadTermTree($vid, $parent = 0, $max_depth = NULL, $load_entities = FALSE) {
    $terms = array();

    $term_list = \Drupal::service('entity_type.manager')->getStorage("taxonomy_term")->loadTree($vid, $parent, $max_depth, $load_entities);
    foreach ($term_list as $term) {
      $terms[$term->tid] = $this->getTerm($term->tid);
    }

    return $terms;
  }

  public function getTranslatedTerm($tid){
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $term = taxonomy_term_load($tid);
    if ($term->hasTranslation($language)) {
      $tid = $term->id();
      $translated_term = \Drupal::service('entity.repository')->getTranslationFromContext($term, $language);
      $term = $translated_term->getName();
    }
    return $term;
  }
}
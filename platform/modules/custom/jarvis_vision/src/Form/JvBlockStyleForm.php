<?php

namespace Drupal\jarvis_vision\Form;

use Drupal\Core\Form\FormState;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Component\Serialization\Exception\InvalidDataTypeException;
use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\Context\ContextHandlerInterface;
use Drupal\Core\Plugin\ContextAwarePluginAssignmentTrait;
use Drupal\Core\Render\RendererInterface;
use Drupal\user\SharedTempStoreFactory;
use Drupal\panels\Plugin\DisplayVariant\PanelsDisplayVariant;
use Drupal\panels_ipe\PanelsIPEBlockRendererTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\ConfigFormBase;


/**
 * Edit config variable form.
 */
class JvBlockStyleForm extends ConfigFormBase {

  use ContextAwarePluginAssignmentTrait;

  use PanelsIPEBlockRendererTrait;

  /**
   * @var \Drupal\Component\Plugin\PluginManagerInterface $blockManager
   */
  protected $blockManager;

  /**
   * @var \Drupal\Core\Render\RendererInterface $renderer
   */
  protected $renderer;

  /**
   * @var \Drupal\user\SharedTempStore
   */
  protected $tempStore;

  /**
   * The Panels storage manager.
   *
   * @var \Drupal\panels\Plugin\DisplayVariant\PanelsDisplayVariant
   */
  protected $panelsDisplay;

  /**
   * Constructs a new PanelsIPEBlockPluginForm.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $block_manager
   * @param \Drupal\Core\Plugin\Context\ContextHandlerInterface $context_handler
   * @param \Drupal\Core\Render\RendererInterface $renderer
   * @param \Drupal\user\SharedTempStoreFactory $temp_store_factory
   */
  public function __construct(PluginManagerInterface $block_manager, ContextHandlerInterface $context_handler, RendererInterface $renderer, SharedTempStoreFactory $temp_store_factory) {
    $this->blockManager = $block_manager;
    $this->contextHandler = $context_handler;
    $this->renderer = $renderer;
    $this->tempStore = $temp_store_factory->get('panels_ipe');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.block'),
      $container->get('context.handler'),
      $container->get('renderer'),
      $container->get('user.shared_tempstore')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jv_block_style_form';
  }


  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   * An array of configuration object names that are editable if called in
   * conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['config.jarvis_vision.block_styles'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $plugin_id = NULL, PanelsDisplayVariant $panels_display = NULL, $uuid = NULL) {
    
    $block_custom_config = $this->config('config.jarvis_vision.block_styles');

    // We require these default arguments.
    if (!$plugin_id || !$panels_display) {
      return FALSE;
    }

    // Save the panels display for later.
    $this->panelsDisplay = $panels_display;

    // Grab the current layout's regions.
    $regions = $panels_display->getRegionNames();

    // If $uuid is present, a block should exist.
    if ($uuid) {
      /** @var \Drupal\Core\Block\BlockBase $block_instance */
      $block_instance = $panels_display->getBlock($uuid);
    }
    else {
      // Create an instance of this Block plugin.
      /** @var \Drupal\Core\Block\BlockBase $block_instance */
      $block_instance = $this->blockManager->createInstance($plugin_id);
    }

    // Determine the current region.
    if ($block_custom_config->get($uuid)['custom_classes']) {
      $custom_classes = $block_custom_config->get($uuid)['custom_classes'];
    }
    else {
      $custom_classes = '';
    }

    // Wrap the form so that our AJAX submit can replace its contents.
    $form['#prefix'] = '<div id="jarvis-vision-block-style-plugin-form-wrapper" class="vision-block-frm">';
    $form['#suffix'] = '</div>';

    // Add our various card wrappers.
    $form['flipper'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'flipper',
      ],
    ];

    $form['flipper']['front'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'front',
      ],
    ];

    $form['flipper']['back'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'back',
      ],
    ];

    $form['#attributes']['class'][] = 'jv-block-style-form flip-container';

    // Get the base configuration form for this block.
    $form['flipper']['front']['settings'] = $block_instance->buildConfigurationForm([], $form_state);
    $form['flipper']['front']['settings']['context_mapping'] = $this->addContextAssignmentElement($block_instance, $this->panelsDisplay->getContexts());
    $form['flipper']['front']['settings']['#tree'] = TRUE;

    // Add the block ID, variant ID to the form as values.
    $form['plugin_id'] = ['#type' => 'value', '#value' => $plugin_id];
    $form['variant_id'] = ['#type' => 'value', '#value' => $panels_display->id()];
    $form['uuid'] = ['#type' => 'value', '#value' => $uuid];


    // Custom Block style settings.
    // -- Container Style
    $form['flipper']['form_title'] = [
      '#markup' => '<h4>Block Custom Style</h4>',
    ];
    
    $form['flipper']['container_width'] = [
      '#title' => $this->t('Container Width'),
      '#type' => 'radios',
      '#options' => array('none' => 'No Container', 'container' => 'Container (Box-width)', 'container-fluid' => 'Container-Fluid (Fluid-width)'),
      '#required' => FALSE,
      '#description' => '<p>Small Container only applies to specific widgets like Testimonials. Box-width constrains at about 960px for Desktop. Fluid-width utilizes 100% of the browser width (Mostly applicable for hero banners)</p>'
    ];
    if(!empty($block_custom_config->get($uuid)['container_width'])) {
      $form['flipper']['container_width']['#default_value'] = $block_custom_config->get($uuid)['container_width'];
    }

    // -- Anchor name for the block
    $anchor = !empty($block_custom_config->get($uuid)['anchor']) ? $block_custom_config->get($uuid)['anchor'] : '';
    $form['flipper']['anchor'] = [
      '#title' => $this->t('Anchor Name'),
      '#type' => 'textfield',
      '#required' => FALSE,
      '#default_value' => $anchor,
      '#description' => '<p>Anchor name for internal page links. Use terms without spaces (E.g. speakers-section)</p>'
    ];

    $form['flipper']['anchor']['#prefix'] = '<div class="vision-form-body"><hr class="seperator">';
    $form['flipper']['custom_classes']['#suffix'] = '</div>';
    

    // -- Text alignment 
    $text_alignments = array(
                  '' => 'Default Alignment', 
                  'left' => 'Left Alignment', 
                  'center' => 'Center Alignment',
                  'right' => 'Right Alignment',
                  'justify' => 'Justify Alignment',
                );
    $text_alignment = !empty($block_custom_config->get($uuid)['text_alignment']) ? $block_custom_config->get($uuid)['text_alignment'] : '';
    $form['flipper']['text_alignment'] = [
      '#title' => $this->t('Text Alignment'),
      '#type' => 'select',
      '#options' => $text_alignments,
      '#required' => FALSE,
      '#default_value' => $text_alignment,
      '#description' => '<p>Overrides the default text-align where applicable</p>'
    ];

    $form['flipper']['text_alignment']['#suffix'] = '<hr>';

    $distance_options = array('' => 'auto');
    foreach(range(0, 200, 10) as $o) {
      $distance_options["$o"] = $o;
    }

    $margin_top = !empty($block_custom_config->get($uuid)['margin_top']) ? $block_custom_config->get($uuid)['margin_top'] : '';
    $form['flipper']['margin_top'] = array(
      '#title' => t('Top Margin'),
      '#type' => 'select',
      '#required' => FALSE,
      '#options' => $distance_options,
      '#prefix' => '<div class="row form-group"><div class="col-sm-3">',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('small form-control')),
      '#default_value' => $margin_top,
    );

    $margin_right = !empty($block_custom_config->get($uuid)['margin_right']) ? $block_custom_config->get($uuid)['margin_right'] : '';
    $form['flipper']['margin_right'] = array(
      '#title' => t('Right Margin'),
      '#type' => 'select',
      '#required' => FALSE,
      '#options' => $distance_options,
      '#prefix' => '<div class="col-sm-3">',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('small form-control')),
      '#default_value' => $margin_right,
    );

    $margin_bottom = !empty($block_custom_config->get($uuid)['margin_bottom']) ? $block_custom_config->get($uuid)['margin_bottom'] : '';
    $form['flipper']['margin_bottom'] = array(
      '#title' => t('Bottom Margin'),
      '#type' => 'select',
      '#required' => FALSE,
      '#options' => $distance_options,
      '#prefix' => '<div class="col-sm-3">',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('small form-control')),
      '#default_value' => $margin_bottom,
    );

    $margin_left = !empty($block_custom_config->get($uuid)['margin_left']) ? $block_custom_config->get($uuid)['margin_left'] : '';
    $form['flipper']['margin_left'] = array(
      '#title' => t('Left Margin'),
      '#type' => 'select',
      '#required' => FALSE,
      '#options' => $distance_options,
      '#prefix' => '<div class="col-sm-3">',
      '#suffix' => '</div></div>',
      '#attributes' => array('class' => array('small form-control')),
      '#default_value' => $margin_left,
    );

    $padding_top = !empty($block_custom_config->get($uuid)['padding_top']) ? $block_custom_config->get($uuid)['padding_top'] : '';
    $form['flipper']['padding_top'] = array(
      '#title' => t('Top Padding'),
      '#type' => 'select',
      '#required' => FALSE,
      '#options' => $distance_options,
      '#prefix' => '<div class="row form-group"><div class="col-sm-3">',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('small form-control')),
      '#default_value' => $padding_top,
    );

    $padding_right = !empty($block_custom_config->get($uuid)['padding_right']) ? $block_custom_config->get($uuid)['padding_right'] : '';
    $form['flipper']['padding_right'] = array(
      '#title' => t('Right Padding'),
      '#type' => 'select',
      '#required' => FALSE,
      '#options' => $distance_options,
      '#prefix' => '<div class="col-sm-3">',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('small form-control')),
      '#default_value' => $padding_right,
    );

    $padding_bottom = !empty($block_custom_config->get($uuid)['padding_bottom']) ? $block_custom_config->get($uuid)['padding_bottom'] : '';
    $form['flipper']['padding_bottom'] = array(
      '#title' => t('Bottom Padding'),
      '#type' => 'select',
      '#required' => FALSE,
      '#options' => $distance_options,
      '#prefix' => '<div class="col-sm-3">',
      '#suffix' => '</div>',
      '#attributes' => array('class' => array('small form-control')),
      '#default_value' => $padding_bottom,
    );

    $padding_left = !empty($block_custom_config->get($uuid)['padding_left']) ? $block_custom_config->get($uuid)['padding_left'] : '';
    $form['flipper']['padding_left'] = array(
      '#title' => t('Left Padding'),
      '#type' => 'select',
      '#required' => FALSE,
      '#options' => $distance_options,
      '#prefix' => '<div class="col-sm-3">',
      '#suffix' => '</div></div>',
      '#attributes' => array('class' => array('small form-control')),
      '#default_value' => $padding_left,
    );

    // -- Custom CSS Classes
    $form['flipper']['custom_classes'] = [
      '#title' => $this->t('Additional CSS Classes'),
      '#type' => 'textfield',
      '#required' => FALSE,
      '#default_value' => $custom_classes,
      '#description' => '<p>E.g. no-margin to remove all margin in the given stripe or without-container to skip hero banner</p>'
    ];

    // Add an add button, which is only used by our App.
    $form['submit'] = [
      '#type' => 'button',
      '#value' => $uuid ? $this->t('Update') : $this->t('Add'),
      '#ajax' => [
        'callback' => '::submitForm',
        'wrapper' => 'panels-ipe-block-plugin-form-wrapper',
        'method' => 'replace',
        'progress' => [
          'type' => 'throbber',
          'message' => '',
        ],
      ],
    ];

    return $form;
  }

  /**
   * Executes the block plugin's submit handlers.
   *
   * @param \Drupal\Core\Block\BlockPluginInterface $block_instance
   *   The block instance.
   * @param array $form
   *   The full form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The full form state.
   */
  protected function submitBlock(BlockPluginInterface $block_instance, array $form, FormStateInterface $form_state) {
    $block_form_state = (new FormState())->setValues($form_state->getValue('settings'));
    $block_instance->submitConfigurationForm($form['flipper']['front']['settings'], $block_form_state);
    if ($block_instance instanceof ContextAwarePluginInterface) {
      $block_instance->setContextMapping($block_form_state->getValue('context_mapping', []));
    }
    // Update the original form values.
    $form_state->setValue('settings', $block_form_state->getValues());

    // -- Save the custom field information in custom storage place.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Return early if there are any errors.
    if ($form_state->hasAnyErrors()) {
      return $form;
    }

    // If a temporary configuration for this variant exists, use it.
    $temp_store_key = $this->panelsDisplay->id();
    if ($variant_config = $this->tempStore->get($temp_store_key)) {
      $this->panelsDisplay->setConfiguration($variant_config);
    }

    $block_instance = $this->getBlockInstance($form_state);

    // Submit the block configuration form.
    $this->submitBlock($block_instance, $form, $form_state);

    // Set the block styles appropriately.
    $block_config = $block_instance->getConfiguration();
    $block_config['custom_classes'] = $form_state->getValue('custom_classes');

    $block_uuid = $form_state->getValue('uuid');
    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.custom_classes", $form_state->getValue('custom_classes'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.container_width", $form_state->getValue('container_width'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.anchor", $form_state->getValue('anchor'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.text_alignment", $form_state->getValue('text_alignment'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.margin_top", $form_state->getValue('margin_top'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.margin_right", $form_state->getValue('margin_right'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.margin_bottom", $form_state->getValue('margin_bottom'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.margin_left", $form_state->getValue('margin_left'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.padding_top", $form_state->getValue('padding_top'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.padding_right", $form_state->getValue('padding_right'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.padding_bottom", $form_state->getValue('padding_bottom'))
        ->save();

    $this->config('config.jarvis_vision.block_styles')
        ->set("$block_uuid.padding_left", $form_state->getValue('padding_left'))
        ->save();    

    // Determine if we need to update or add this block.
    if ($uuid = $form_state->getValue('uuid')) {
      $this->panelsDisplay->updateBlock($uuid, $block_config);
    }
    else {
      $uuid = $this->panelsDisplay->addBlock($block_config);
    }

    // Set the tempstore value.
    $this->tempStore->set($this->panelsDisplay->id(), $this->panelsDisplay->getConfiguration());

    // Assemble data required for our App.
    $build = $this->buildBlockInstance($block_instance, $this->panelsDisplay);

    // Bubble Block attributes to fix bugs with the Quickedit and Contextual
    // modules.
    $this->bubbleBlockAttributes($build);

    // Add our data attribute for the Backbone app.
    $build['#attributes']['data-block-id'] = $uuid;

    $plugin_definition = $block_instance->getPluginDefinition();

    $block_model = [
      'uuid' => $uuid,
      'label' => $block_instance->label(),
      'id' => $block_instance->getPluginId(),
      'region' => $block_config['region'],
      'custom_classes' => $block_config['custom_classes'],
      'provider' => $block_config['provider'],
      'plugin_id' => $plugin_definition['id'],
      'html' => $this->renderer->render($build),
    ];

    $form['build'] = $build;

    // Add Block metadata and HTML as a drupalSetting.
    $form['#attached']['drupalSettings']['panels_ipe']['updated_block'] = $block_model;

    return $form;
  }

  /**
   * Loads or creates a Block Plugin instance suitable for rendering or testing.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return \Drupal\Core\Block\BlockPluginInterface
   *   The Block Plugin instance.
   */
  protected function getBlockInstance(FormStateInterface $form_state) {
    // If a UUID is provided, the Block should already exist.
    if ($uuid = $form_state->getValue('uuid')) {
      // If a temporary configuration for this variant exists, use it.
      $temp_store_key = $this->panelsDisplay->id();
      if ($variant_config = $this->tempStore->get($temp_store_key)) {
        $this->panelsDisplay->setConfiguration($variant_config);
      }

      // Load the existing Block instance.
      $block_instance = $this->panelsDisplay->getBlock($uuid);
    }
    else {
      // Create an instance of this Block plugin.
      /** @var \Drupal\Core\Block\BlockBase $block_instance */
      $block_instance = $this->blockManager->createInstance($form_state->getValue('plugin_id'));
    }

    return $block_instance;
  }

  /**
   * Removes the "form" theme wrapper from all nested elements of the given
   * render array.
   *
   * @param array $content
   *   A render array that could potentially contain a nested form.
   *
   * @return array
   *   The potentially modified render array.
   */
  protected function removeFormWrapperRecursive(array $content) {
    if (is_array($content)) {
      // If this block is rendered as a form, we'll need to disable its wrapping
      // element.
      if (isset($content['#theme_wrappers'])
        && ($key = array_search('form', $content['#theme_wrappers'])) !== FALSE) {
        unset($content['#theme_wrappers'][$key]);
      }

      // Perform the same operation on child elements.
      foreach (Element::getVisibleChildren($content) as $key) {
        $content[$key] = $this->removeFormWrapperRecursive($content[$key]);
      }
    }

    return $content;
  }
}

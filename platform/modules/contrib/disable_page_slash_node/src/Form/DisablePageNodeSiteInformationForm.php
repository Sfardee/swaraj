<?php

namespace Drupal\disable_page_slash_node\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * Configure site information settings for this site.
 *
 * @internal
 */
class DisablePageNodeSiteInformationForm extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Retrieve the system.site configuration.
    $site_config = $this->config('system.site');

    // Get the original form from the class we are extending
    $form = parent::buildForm($form, $form_state);

    $form['front_page']['site_disable_page_node'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Retain <a href="@url">/node</a> as an active url?', [
        '@url' => $this->aliasManager->getAliasByPath('/node'),
      ]),
      '#default_value' => $site_config->get('site_disable_page_node') ?? TRUE,
      '#states' => [
        'invisible' => [
          ':input[name=site_frontpage]' => [
            'value' => '/node',
          ],
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $siteFrontPage = $form_state->getValue('site_frontpage');
    $disablePageNode = $form_state->getValue('site_disable_page_node');

    if ($siteFrontPage === '/node') {
      $disablePageNode = TRUE;
    }

    $this->config('system.site')
      ->set('site_disable_page_node', $disablePageNode)
      ->save();

    parent::submitForm($form, $form_state);

  }

}

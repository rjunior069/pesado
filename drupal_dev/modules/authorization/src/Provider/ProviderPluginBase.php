<?php

namespace Drupal\authorization\Provider;

use Drupal\authorization\Plugin\ConfigurablePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base class for Authorization provider plugins.
 */
abstract class ProviderPluginBase extends ConfigurablePluginBase implements ProviderInterface {

  public $type = 'provider';
  public $handlers = [];

  public function submitRowForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    // Create an array of just the provider values
    $provider_mappings = [];
    foreach ($values as $key => $value) {
      if (isset($value['consumer_mappings'])) {
        $provider_mappings[] = $value['provider_mappings'];
      }
    }
    $form_state->setValue('provider_mappings', $provider_mappings);

    parent::submitRowForm($form, $form_state);
  }

  public function getHandlers() {
    return $this->handlers;
  }

  public function getProposals($user, $op, $provider_mapping) {
    return NULL;
  }

  public function filterProposals($proposals, $op, $provider_mapping) {
    return [];
  }

  public function sanitizeProposals($proposals, $op) {}

}

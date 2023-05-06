<?php

namespace Drupal\custom_ajax_loader\Plugin\ajax_loader;

use Drupal\ajax_loader\ThrobberPluginBase;

/**
 * Class ThrobberFlight.
 *
 * Add plugin for custom throbber.
 *
 * @Throbber(
 *   id = "throbber_flight",
 *   label = @Translation("Flight")
 * )
 */
class ThrobberFlight extends ThrobberPluginBase {

  /**
   * Function to set markup.
   *
   * @inheritdoc
   */
  protected function setMarkup(): string {
    return '<div class="ajax-throbber sk-flight"></div>';
  }

  /**
   * Function to set css file.
   *
   * @inheritdoc
   */
  protected function setCssFile(): string {
    $module_handler = \Drupal::service('module_handler');
    $module_path = $module_handler->getModule('custom_ajax_loader')->getPath();
    return '/' . $module_path . '/css/flight.css';
  }

}

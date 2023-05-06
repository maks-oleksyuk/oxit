<?php

namespace Drupal\custom_subscriber\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;

/**
 * Controller for Field Image Tooltips.
 */
class ChangedTooltipsDataController extends ControllerBase {

  /**
   * Returns node content for popup.
   *
   * @return array
   *   Returns a node.
   */
  public function tooltip(EntityInterface $node, $js = 'nojs') {
    $view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
    $content = $view_builder->view($node, 'tooltip');
    if ($js == 'ajax') {
      $response = new AjaxResponse();
      $response->addCommand(new OpenModalDialogCommand('', $content, [
        'dialogClass' => 'tooltip-modal',
        'width' => '376',
      ]));
      return $response;
    }
    else {
      return $content;
    }
  }

}

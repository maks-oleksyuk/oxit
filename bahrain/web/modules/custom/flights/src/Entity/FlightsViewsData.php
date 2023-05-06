<?php

namespace Drupal\flights\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Flights entities.
 */
class FlightsViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $entity_type_id = $this->entityType->id();
    $t_arguments = ['@entity_type_label' => $this->entityType->getLabel()];
    if ($this->entityType->hasLinkTemplate('notify-form')) {
      $data['flights']['notify_' . $entity_type_id] = [
        'field' => [
          'title' => $this->t('Link to notification @entity_type_label', $t_arguments),
          'help' => $this->t('Provide an notification link to the @entity_type_label.', $t_arguments),
          'id' => 'entity_link_notify',
        ],
      ];
    }
    return $data;
  }

}

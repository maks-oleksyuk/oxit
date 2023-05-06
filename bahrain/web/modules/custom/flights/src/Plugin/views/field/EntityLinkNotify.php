<?php

namespace Drupal\flights\Plugin\views\field;

use Drupal\views\ResultRow;
use Drupal\views\Plugin\views\field\EntityLink;

/**
 * Field handler to present a link to notify an entity.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("entity_link_notify")
 */
class EntityLinkNotify extends EntityLink {

  /**
   * {@inheritdoc}
   */
  protected function getEntityLinkTemplate() {
    return 'notify-form';
  }

  /**
   * {@inheritdoc}
   */
  protected function renderLink(ResultRow $row) {
    $this->options['alter']['query'] = $this->getDestinationArray();
    return parent::renderLink($row);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultLabel() {
    return $this->t('notify');
  }

}

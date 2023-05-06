<?php

namespace Drupal\flights\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the flights entity edit forms.
 */
class FlightsForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New flights %label has been created.', $message_arguments));
      $this->logger('flights')->notice('Created new flights %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The flights %label has been updated.', $message_arguments));
      $this->logger('flights')->notice('Updated new flights %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.flights.canonical', ['flights' => $entity->id()]);
  }

}

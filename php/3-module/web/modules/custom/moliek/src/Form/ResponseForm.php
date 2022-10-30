<?php

namespace Drupal\moliek\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityForm;

/**
 * Form controller for the response entity edit forms.
 */
class ResponseForm extends ContentEntityForm {

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
      $this->messenger()
        ->addStatus($this->t('New response %label has been created.', $message_arguments));
      $this->logger('moliek')
        ->notice('Created new response %label', $logger_arguments);
    }
    else {
      $this->messenger()
        ->addStatus($this->t('The response %label has been updated.', $message_arguments));
      $this->logger('moliek')
        ->notice('Updated new response %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.response.canonical', ['response' => $entity->id()]);
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);
    $form['#attached']['library'][] = 'moliek/inputmask';
    $form['#prefix'] = '<div id="response-form"';
    $form['#suffix'] = '</div>';
    $form['actions']['submit']['#ajax'] = [
      'callback' => '::submitAjax',
      'wrapper' => 'response-form',
      'progress' => 'none',
    ];
    return $form;
  }

  /**
   * AJAX validation and confirmation of the form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array|\Drupal\core\Ajax\AjaxResponse
   *   Return form or Ajax response.
   */
  public function submitAjax(array $form, FormStateInterface $form_state) {
    if ($form_state->hasAnyErrors()) {
      return $form;
    }
    $response = new AjaxResponse();
    $response->addCommand(new RedirectCommand('/guestbook'));
    return $response;
  }

}

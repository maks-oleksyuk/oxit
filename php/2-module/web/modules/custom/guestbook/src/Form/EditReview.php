<?php

namespace Drupal\guestbook\Form;

use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for editing cats.
 */
class EditReview extends GuestBookForm {

  /**
   * The results of the query to the table are given.
   *
   * @var object
   */
  protected object $response;

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'guestbook_edit_review';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, int $id = NULL): array {
    $database = \Drupal::database();
    $result = $database->select('guestbook', 'g')
      ->fields('g', ['id', 'name', 'email', 'phone', 'text', 'ava', 'img'])
      ->condition('id', $id)
      ->execute()->fetch();
    $this->response = $result;
    $form = parent::buildForm($form, $form_state);
    $form['name']['#default_value'] = $result->name;
    $form['name']['#prefix'] = '<div class="error-massage-modal"></div>';
    $form['email']['#default_value'] = $result->email;
    $form['email']['#ajax']['event'] = 'change';
    $form['phone']['#default_value'] = $result->phone;
    $form['text']['#default_value'] = $result->text;
    if ($result->ava) {
      $form['ava']['#default_value'][] = $result->ava;
    }
    if ($result->img) {
      $form['img']['#default_value'][] = $result->img;
    }
    $form['submit']['#value'] = $this->t('Confirm');
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function emailValidator(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $email = $form_state->getValue('email');
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $response->addCommand(new InvokeCommand("input[id^='edit-email--']", 'removeClass', ['is-invalid']));
      $response->addCommand(new InvokeCommand("input[id^='edit-email--']", 'addClass', ['is-valid']));
    }
    else {
      $response->addCommand(new InvokeCommand("input[id^='edit-email--']", 'removeClass', ['is-valid']));
      $response->addCommand(new InvokeCommand("input[id^='edit-email--']", 'addClass', ['is-invalid']));
    }
    if (empty($email)) {
      $response->addCommand(new InvokeCommand("input[id^='edit-email--']", 'removeClass', ['is-valid']));
      $response->addCommand(new InvokeCommand("input[id^='edit-email--']", 'removeClass', ['is-invalid']));
    }
    return $response;
  }

  /**
   * Output errors in the modal window of the edit form.
   */
  public function submitAjax(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    if ($form_state->getErrors()) {
      foreach ($form_state->getErrors() as $err) {
        $response->addCommand(new MessageCommand($err, '.error-massage-modal', ['type' => 'error']));
      }
      $form_state->clearErrors();
    }
    else {
      $url = Url::fromRoute('guestbook.content');
      $response->addCommand(new RedirectCommand($url->toString()));
    }
    return $response;
  }

  /**
   * {@inheritDoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /**
     * A variable that stores the user avatar ID if available.
     *
     * @var int
     */
    $ava = NULL;
    /**
     * A variable that stores the ID of the image added to the response,
     * if available.
     *
     * @var int
     */
    $img = NULL;

    // Check if the photo is in shape and whether it is different from the past.
    if ($form_state->getValue('ava')[0] && $form_state->getValue('ava')[0] != $this->response->ava) {
      $ava = $form_state->getValue('ava')[0];
      $file = File::load($ava);
      $file->setPermanent();
      $file->save();
    }
    elseif ($form_state->getValue('ava')[0]) {
      $ava = $this->response->ava;
    }
    elseif ($this->response->ava) {
      File::load($this->response->ava)->delete();
    }

    // Check if the photo is in shape and whether it is different from the past.
    if ($form_state->getValue('img')[0] && $form_state->getValue('img')[0] != $this->response->img) {
      $img = $form_state->getValue('img')[0];
      $file = File::load($img);
      $file->setPermanent();
      $file->save();
    }
    elseif ($form_state->getValue('img')[0]) {
      $img = $this->response->img;
    }
    elseif ($this->response->img) {
      File::load($this->response->img)->delete();
    }

    // Create an array of data to update.
    $updated = [
      'name' => $form_state->getValue('name'),
      'email' => $form_state->getValue('email'),
      'phone' => $form_state->getValue('phone'),
      'text' => $form_state->getValue('text'),
      'ava' => $ava,
      'img' => $img,
    ];

    // Connect to the database and send a request.
    $database = \Drupal::database();
    $database->update('guestbook')
      ->condition('id', $this->response->id)
      ->fields($updated)
      ->execute();

    // Display a message about the successful editing of the review.
    $this->messenger()
      ->addMessage($this->t('Review successfully edited.'), 'status');
  }

}

<?php

namespace Drupal\guestbook\Form;

use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\MessageCommand;

/**
 * Implements the form of adding feedback.
 */
class GuestBookForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'guestbook_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['name'] = [
      '#required' => TRUE,
      '#type' => 'textfield',
      '#pattern' => '[A-z0-9\s]*',
      '#title' => $this->t('Your name:'),
      '#placeholder' => $this->t("Enter name"),
    ];
    $form['email'] = [
      '#type' => 'email',
      '#required' => TRUE,
      '#description' => ' ',
      '#title' => $this->t('Your email:'),
      '#placeholder' => $this->t("name@example.com"),
      '#ajax' => [
        'event' => 'keyup',
        'keypress' => TRUE,
        'progress' => 'none',
        'callback' => '::emailValidator',
      ],
      '#attached' => [
        'library' => [
          'guestbook/ajax-patch',
        ],
      ],
    ];
    $form['phone'] = [
      '#type' => 'tel',
      '#required' => TRUE,
      '#title' => $this->t('Your phone number:'),
      '#pattern' => '[+](380)\(\d{2}\)-\d{3}-\d{2}-\d{2}',
      '#placeholder' => $this->t('+380(__)-___-__-__'),
      '#attached' => [
        'library' => [
          'guestbook/inputmask',
        ],
      ],
      '#attributes' => [
        'data-inputmask' => "'mask': '+380(99)-999-99-99'",
      ],
    ];
    $form['text'] = [
      '#type' => 'textarea',
      '#required' => TRUE,
      '#resizable' => 'none',
      '#title' => $this->t('Your massage:'),
      '#placeholder' => $this->t("It was a wonderful night spent with you Drupal"),
    ];
    $form['ava'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Add your profile picture:'),
      '#upload_location' => 'public://avatars',
      '#upload_validators' => [
        'file_validate_size' => [2097152],
        'file_validate_extensions' => ['jpeg jpg png'],
      ],
    ];
    $form['img'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Add an image to the review:'),
      '#upload_location' => 'public://img',
      '#upload_validators' => [
        'file_validate_size' => [5242880],
        'file_validate_extensions' => ['jpeg jpg png'],
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t("Submit"),
      '#ajax' => [
        'event' => 'click',
        'progress' => 'none',
        'callback' => '::submitAjax',
      ],
    ];
    return $form;
  }

  /**
   * Ajax email validation.
   */
  public function emailValidator(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $email = $form_state->getValue('email');
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $response->addCommand(new InvokeCommand("input[name='email']", 'removeClass', ['is-invalid']));
      $response->addCommand(new InvokeCommand("input[name='email']", 'addClass', ['is-valid']));
      $response->addCommand(new InvokeCommand("div[id='edit-email--description']", 'removeClass', ['invalid-feedback']));
      $response->addCommand(new InvokeCommand("div[id='edit-email--description']", 'addClass', ['valid-feedback']));
      $response->addCommand(new HtmlCommand("div[id='edit-email--description']", 'Looks good!'));
    }
    else {
      $response->addCommand(new InvokeCommand("input[name='email']", 'removeClass', ['is-valid']));
      $response->addCommand(new InvokeCommand("input[name='email']", 'addClass', ['is-invalid']));
      $response->addCommand(new InvokeCommand("div[id='edit-email--description']", 'removeClass', ['valid-feedback']));
      $response->addCommand(new InvokeCommand("div[id='edit-email--description']", 'addClass', ['invalid-feedback']));
      $response->addCommand(new HtmlCommand("div[id='edit-email--description']", 'Please provide a valid email.'));
    }
    if (empty($email)) {
      $response->addCommand(new InvokeCommand("input[name='email']", 'removeClass', ['is-valid']));
      $response->addCommand(new InvokeCommand("input[name='email']", 'removeClass', ['is-invalid']));
      $response->addCommand(new InvokeCommand("div[id='edit-email--description']", 'removeClass', ['invalid-feedback']));
      $response->addCommand(new InvokeCommand("div[id='edit-email--description']", 'removeClass', ['invalid-feedback']));
      $response->addCommand(new HtmlCommand("div[id='edit-email--description']", ''));
    }
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $val = $form_state->getValue('name');
    if (strlen($val) < 2 || strlen($val) > 100) {
      $form_state->setErrorByName('Name', $this->t("Name must be between 2 and 100 characters long"));
    }
  }

  /**
   * Display a message about adding a form.
   */
  public function submitAjax(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    if ($form_state->getErrors()) {
      foreach ($form_state->getErrors() as $err) {
        $response->addCommand(new MessageCommand($err, NULL, ['type' => 'error']));
      }
      $form_state->clearErrors();
    }
    else {
      $response->addCommand(new MessageCommand($this->t('Your response has been saved successfully.'), NULL, ['type' => 'status'], TRUE));
      $form_state->setRebuild(TRUE);
    }
    $this->messenger()->deleteAll();
    return $response;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $ava = $form_state->getValue('ava');
    $img = $form_state->getValue('img');

    // Save files if they exist.
    if ($ava) {
      $file = File::load($ava[0]);
      $file->setPermanent();
      $file->save();
    }
    if ($img) {
      $file = File::load($img[0]);
      $file->setPermanent();
      $file->save();
    }

    // Connect to the database and send a request.
    $database = \Drupal::database();
    $database->insert('guestbook')
      ->fields([
        'name' => $form_state->getValue('name'),
        'email' => $form_state->getValue('email'),
        'phone' => $form_state->getValue('phone'),
        'text' => $form_state->getValue('text'),
        'ava' => $ava[0],
        'img' => $img[0],
        'created' => time(),
      ])->execute();
  }

}

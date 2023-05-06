<?php

namespace Drupal\flights\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implement a form for notifications flights.
 */
class NotificationForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'notification_flights_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $entity = $this->getEntity();
    $status = $entity->get('state')->value;
    if ($status == 'cancelled' || $status == 'landed') {
      $form = $this->buildStatusForm($form, $form_state);
    }
    elseif ($status == 'on time' || $status == 'early') {
      $form = $this->buildMainForm($form, $form_state);
    }
    return $form;
  }

  /**
   * Build main form registration.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structures.
   */
  protected function buildMainForm(array $form, FormStateInterface $form_state): array {
    $entity = $this->getEntity();
    $number = $entity->get('flight_number')->value;
    $form['#prefix'] = '<div id="fly-notify-form">';
    $form['#suffix'] = '</div>';
    $form['title'] = [
      '#type' => 'item',
      '#title' => $this->t("Welcome to Bahrain Airport flight update service"),
    ];
    $form['number'] = [
      '#type' => 'item',
      '#title' => $this->t("Flight Number ") . $number,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#required' => TRUE,
      '#description_display' => 'before',
      '#description' => $this->t('Simply enter your email address here to receive personalized flight updates.'),
      '#placeholder' => $this->t("Enter Email Address"),
      '#attached' => [
        'library' => [
          'flights/notify-css',
        ],
      ],
    ];
    $form['note'] = [
      '#type' => 'item',
      '#title' => $this->t('Note'),
      '#description' => $this->t('This notification is active only for 24 hrs starting from the moment of the request.'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t("Register"),
      '#submit' => ['::submitForm'],
      '#ajax' => [
        'event' => 'click',
        'progress' => 'none',
        'callback' => '::submitAjax',
        'wrapper' => 'fly-notify-form',
      ],
    ];
    return $form;
  }

  /**
   * Build status form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structures.
   */
  protected function buildStatusForm(array $form, FormStateInterface $form_state): array {
    $entity = $this->getEntity();
    $status = $entity->get('state')->value;
    $number = $entity->get('flight_number')->value;
    $form['note'] = [
      '#type' => 'item',
      '#title' => $this->t("Selected flight no. - ") . $number . $this->t(" is already ") . $this->t($status),
    ];
    return $form;
  }

  /**
   * Ajax`s validation.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structures.
   */
  public function submitAjax(array &$form, FormStateInterface $form_state): array {
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email',
        $this->t(
          'Mail: Oh No! Your Email title is Invalid('
        )
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $email = $form_state->getValue('email');
    $data = [
      'email' => $email,
      'fly_number' => $entity->get('flight_number')->value,
      'time' => $entity->get('scheduled_time')->value,
    ];
    \Drupal::database()->insert('flights_notify')->fields($data)->execute();

    if (\Drupal::config('flights.settings')->get('submit_msg_be_sent')) {
      $params['entity'] = $entity;
      // Sends an Email.
      $newMail = \Drupal::service('plugin.manager.mail');
      $newMail->mail('flights', 'submit', $email, 'en', $params);
    }
  }

}

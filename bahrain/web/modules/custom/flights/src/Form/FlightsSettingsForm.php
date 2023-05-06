<?php

namespace Drupal\flights\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for a flights entity type.
 */
class FlightsSettingsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'flights_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::config('flights.settings');
    $form['settings'] = [
      '#markup' => $this->t('Settings form for a flights entity type.'),
    ];
    $form['notify'] = [
      '#type' => 'details',
      '#open' => TRUE,
      '#title' => $this->t('Notify Settings'),
    ];
    $form['notify']['submit_msg_be_sent'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Send a message after submit form'),
      '#default_value' => $config->get('submit_msg_be_sent'),
    ];
    $form['notify']['submit_msg'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Submit notification message'),
      '#default_value' => $this->t($config->get('submit_msg')),
    ];
    $form['notify']['notify_24_be_sent'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Send user notifications 24 hours before'),
      '#default_value' => $config->get('notify_24_be_sent'),
    ];
    $form['notify']['notify_24_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Message text to send'),
      '#default_value' => $this->t($config->get('notify_24_text')),
    ];
    $form['notify']['notify_cansel_be_sent'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Send user notifications when flight canceled'),
      '#default_value' => $config->get('notify_cansel_be_sent'),
    ];
    $form['notify']['notify_cansel_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Message text to send'),
      '#default_value' => $this->t($config->get('notify_cansel_text')),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')
      ->getEditable('flights.settings');
    $config->set('submit_msg_be_sent', boolval($form_state->getValue('submit_msg_be_sent')))
      ->save();
    $config->set('submit_msg', $form_state->getValue('submit_msg')['value'])
      ->save();
    $config->set('notify_24_be_sent', boolval($form_state->getValue('notify_24_be_sent')))
      ->save();
    $config->set('notify_24_text', $form_state->getValue('notify_24_text')['value'])
      ->save();
    $config->set('notify_cansel_be_sent', boolval($form_state->getValue('notify_cansel_be_sent')))
      ->save();
    $config->set('notify_cansel_text', $form_state->getValue('notify_cansel_text')['value'])
      ->save();
    $this->messenger()
      ->addStatus($this->t('The configuration has been updated.'));
  }

}

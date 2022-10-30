<?php

namespace Drupal\guestbook\Form;

use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a confirmation form to confirm deletion cat by id.
 */
class DeleteReview extends ConfirmFormBase {

  /**
   * ID of the item to delete.
   *
   * @var int
   */
  protected int $id;

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return "guestbook_delete";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, int $id = NULL): array {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion(): string {
    return $this->t('You really want to delete this response?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl(): Url {
    return new Url('guestbook.content');
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $database = \Drupal::database();
    $result = $database->select('guestbook', 'g')
      ->fields('g', ['ava', 'img'])
      ->condition('id', $this->id)
      ->execute()->fetch();
    if ($result->ava) {
      File::load($result->ava)->delete();
    }
    if ($result->img) {
      File::load($result->img)->delete();
    }
    $database->delete('guestbook')
      ->condition('id', $this->id)
      ->execute();
    \Drupal::messenger()->addStatus('Successfully deleted.');
    $form_state->setRedirect('guestbook.content');
  }

}

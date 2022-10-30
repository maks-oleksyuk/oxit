<?php

namespace Drupal\guestbook\Controller;

use Drupal\file\Entity\File;
use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the guestbook module.
 */
class PageController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build(): array {
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\guestbook\Form\GuestBookForm');
    $reviews = self::getReviews();
    $build['content'] = [
      '#text' => $this->t('Hello! You can leave feedback, comments, impressions and wishes.'),
      '#form' => $form,
      '#review' => $reviews,
      '#theme' => 'guestbook_theme',
      '#attached' => [
        'library' => [
          'guestbook/guestbook-css',
        ],
      ],
    ];
    return $build;
  }

  /**
   * Function for get data from the table guestbook.
   */
  public static function getReviews(): array {

    // Connect to the database and send a request.
    $database = \Drupal::database();
    $result = $database->select('guestbook', 'g')
      ->fields('g', [
        'id',
        'name',
        'email',
        'phone',
        'text',
        'ava',
        'img',
        'created',
      ])
      ->orderBy('created', 'DESC')
      ->execute();

    // Forming an array of results.
    $reviews = [];
    foreach ($result as $response) {
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

      // Check for avatar ID.
      if ($response->ava != $ava) {
        $ava = File::load($response->ava)->createFileUrl();
      }

      // Check the image for feedback ID.
      if ($response->img != $img) {
        $img = File::load($response->img)->createFileUrl();
      }

      // Forming an array for return.
      $reviews[] = [
        'id' => $response->id,
        'name' => $response->name,
        'email' => $response->email,
        'phone' => $response->phone,
        'text' => $response->text,
        'ava' => $ava,
        'img' => $img,
        'created' => $response->created,
      ];
    }
    return $reviews;
  }

}

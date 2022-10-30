<?php

namespace Drupal\moliek\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityFormBuilder;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides route responses for the moliek module.
 */
class PageController extends ControllerBase {

  /**
   * Construct GuestBookController.
   *
   * @param \Drupal\Core\Entity\EntityFormBuilder $entityFormBuilder
   *   The entity form builder.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(EntityFormBuilder $entityFormBuilder, EntityTypeManagerInterface $entityTypeManager) {
    $this->entityFormBuilder = $entityFormBuilder;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container): PageController {
    return new static(
      $container->get('entity.form_builder'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Builds the response.
   *
   * @return array
   *   Array of answers for the guestbook page.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function build(): array {
    // Creates a new storage instance.
    $storage = $this->entityTypeManager->getStorage('response');
    // Gets an array of forms to reproduce the entity type.
    $form = $this->entityFormBuilder->getForm($storage->create(), 'add');
    // Gets an array of user response records.
    $responses = $this->getResponses();
    // Forming an array to return.
    return [
      '#form' => $form,
      '#theme' => 'guestbook',
      '#response_list' => $responses,
    ];
  }

  /**
   * Receiving feedback from the database.
   *
   * @return array
   *   Array of user reviews.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function getResponses(): array {
    // Creates a new storage instance.
    $storage = $this->entityTypeManager->getStorage('response');
    // Send a query to the database.
    $query = $storage->getQuery()
      ->sort('created', 'DESC')
      ->pager(5)
      ->execute();
    // Loads one or more entities.
    $responses = $storage->loadMultiple($query);
    // Creates a new view builder instance for array.
    $responses = $this->entityTypeManager
      ->getViewBuilder('response')
      ->viewMultiple($responses);
    // Forming an array to return.
    return [
      '#theme' => 'response_list',
      '#responses' => $responses,
      '#pager' => [
        '#type' => 'pager',
      ],
    ];
  }

}

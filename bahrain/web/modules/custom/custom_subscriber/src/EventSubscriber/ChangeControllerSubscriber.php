<?php

namespace Drupal\custom_subscriber\EventSubscriber;

use Drupal\Core\Routing\RouteBuildEvent;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * A simple event subscriber for controller .
 */
class ChangeControllerSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[RoutingEvents::ALTER] = 'alterRoutes';
    return $events;
  }

  /**
   * Alters existing routes.
   *
   * @param \Drupal\Core\Routing\RouteBuildEvent $event
   *   The route building event.
   */
  public function alterRoutes(RouteBuildEvent $event) {
    // Fetch the collection which can be altered.
    $collection = $event->getRouteCollection();
    // The event is fired multiple times so ensure that the user_page route
    // is available.
    if ($route = $collection->get('field_tooltips_data.tooltip')) {
      // As example add a new requirement.
      $route->setDefault('_controller', '\Drupal\custom_subscriber\Controller\ChangedTooltipsDataController::tooltip');
    }
  }

}

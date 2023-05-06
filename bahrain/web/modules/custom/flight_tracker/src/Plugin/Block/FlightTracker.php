<?php

namespace Drupal\flight_tracker\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

//
/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("Tracker"),
 *   category = @Translation("Flight Tracker"),
 * )
 */
class FlightTracker extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
return [
  '#theme' => 'flight_tracker_block',
];
  }
}


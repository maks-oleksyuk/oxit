<?php

namespace Drupal\live_clock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Locale\CountryManager;

/**
 * Provides a block with time.
 *
 * @Block(
 *   id = "clock_block",
 *   admin_label = @Translation("Clock Block"),
 * )
 */
class ClockBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      '#theme' => 'live_clock_block',
      '#country' => $this->configuration["country"],
      '#id' => 'live-clock-time-' . $this->configuration["id"],
      '#attributes' => [
        'class' => ['live-clock-block'],
        'data-timezone' => $this->configuration["timezone"],
        'data-id' => 'live-clock-time-' . $this->configuration["id"],
      ],
      '#attached' => [
        'library' => 'live_clock/react',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    // Get arrays countries and timezone to select.
    $timezones = timezone_identifiers_list();
    $countries = CountryManager::getStandardList();
    // Get default values from site.
    $timezone = \Drupal::config('system.date')->get('timezone')['default'];
    $country_code = \Drupal::config('system.date')->get('country')['default'];
    $config = $this->getConfiguration();
    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#empty_value' => '',
      '#description' => $this->t('The name of the country that will be displayed together with the clock'),
      '#options' => $countries,
      '#default_value' => isset($config['country_code']) ? $config['country_code'] : $country_code,
      '#weight' => '1',
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Time zone'),
      '#description' => $this->t('Time zone for which the time will be displayed'),
      '#options' => $timezones,
      '#default_value' => isset($config['timezone']) ? array_search($config['timezone'], $timezones) : array_search($timezone, $timezones),
      '#weight' => '2',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $country_code = $form_state->getValue('country');
    $country_name = '';
    if ($country_code) {
      $country_name = \Drupal::service('country_manager')
        ->getList()[$country_code]->__toString();
    }
    $timezones = timezone_identifiers_list();
    $this->configuration['country'] = $country_name;
    $this->configuration['country_code'] = $country_code;
    $this->configuration['timezone'] = $timezones[$form_state->getValue('timezone')];
    $this->configuration['id'] = $form['id']['#value'];
  }

}

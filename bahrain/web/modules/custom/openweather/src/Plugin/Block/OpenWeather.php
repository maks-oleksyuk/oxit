<?php

namespace Drupal\openweather\Plugin\Block;

use Drupal\Core\Site\Settings;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a block with time.
 *
 * @Block(
 *   id = "open_weather_block",
 *   admin_label = @Translation("OpenWeater Block"),
 * )
 */
class OpenWeather extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    // Set variables for the query.
    $config = $this->getConfiguration();
    $country = $config['country'];
    $key = settings::get('open_weather_api_key', '');
    // Obtaining data with units=metric.
    $res = file_get_contents('https://api.openweathermap.org/data/2.5/weather?q=' . $country . '&units=metric&appid=' . $key);
    $res = json_decode($res, TRUE);
    $tempCel = intval(round($res['main']['temp']));
    // Obtaining data with units=imperial.
    $res = file_get_contents('https://api.openweathermap.org/data/2.5/weather?q=' . $country . '&units=imperial&appid=' . $key);
    $res = json_decode($res, TRUE);
    $tempFar = intval(round($res['main']['temp']));

    $temp = sprintf("%s °C / %s °F", $tempCel, $tempFar);

    return [
      '#name' => $country,
      '#city_id' => $res['id'],
      '#temp' => $temp,
      '#translatable' => TRUE,
      '#icon_id' => $res['weather'][0]['icon'],
      '#description' => $res['weather'][0]['description'],
      '#theme' => 'open_weather_block',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $config = $this->getConfiguration();
    $form['country'] = [
      '#required' => TRUE,
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#description' => $this->t('The name of the city for which the weather will be displayed'),
      "#default_value" => isset($config['country']) ? $config['country'] : '',
      '#weight' => '1',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['country'] = $form_state->getValue('country');
  }

}

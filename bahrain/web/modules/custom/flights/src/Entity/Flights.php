<?php

namespace Drupal\flights\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Annotation\ContentEntityType;

/**
 * Defines the flights entity class.
 *
 * @ContentEntityType(
 *   id = "flights",
 *   label = @Translation("flights"),
 *   label_collection = @Translation("flightses"),
 *   handlers = {
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\flights\FlightsListBuilder",
 *     "views_data" = "Drupal\flights\Entity\FlightsViewsData",
 *     "form" = {
 *       "add" = "Drupal\flights\Form\FlightsForm",
 *       "edit" = "Drupal\flights\Form\FlightsForm",
 *       "notify" = "Drupal\flights\Form\NotificationForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "flights",
 *   data_table = "flights_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer flights",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "add-form" = "/admin/content/flights/add",
 *     "canonical" = "/flights/{flights}",
 *     "edit-form" = "/admin/content/flights/{flights}/edit",
 *     "notify-form" = "/flights/{flights}/notify",
 *     "delete-form" = "/admin/content/flights/{flights}/delete",
 *     "collection" = "/admin/content/flights"
 *   },
 *   field_ui_base_route = "entity.flights.settings",
 * )
 */
class Flights extends ContentEntityBase {

  public function getFlight_type() {
    return $this->get('flight_type')->value;
  }

  public function setFlight_type($flightType) {
    $this->set('flight_type', $flightType);
    return $this;
  }

  public function getTime() {
    return $this->get('scheduled_time')->value;
  }

  public function setTime($time) {
    $this->set('scheduled_time', $time);
    return $this;
  }

  public function getCity() {
    return $this->get('city')->value;
  }

  public function setCity($city) {
    $this->set('city', $city);
    return $this;
  }

  public function getNumber() {
    return $this->get('flight_number')->value;
  }

  public function setNumber($number) {
    $this->set('flight_number', $number);
    return $this;
  }

  public function getState() {
    return $this->get('state')->value;
  }

  public function setState($status) {
    $this->set('state', $status);
    return $this;
  }

  public function getAirline() {
    return $this->get('airline')->value;
  }

  public function setAirline($airline) {
    $this->set('airline', $airline);
    return $this;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['flight_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Flight type'))
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'type' => 'string',
        'label' => 'hidden',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['scheduled_time'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Scheduled time'))
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'datetime_default',
        'settings' => [
          'format_type' => 'medium',
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_default',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setTranslatable(TRUE)
      ->setSettings(['max_length' => 30,])
      ->setDisplayOptions('view', [
        'type' => 'string',
        'label' => 'hidden',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['flight_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Flight number'))
      ->setSettings([
        'max_length' => 10,
      ])
      ->setDisplayOptions('view', [
        'type' => 'string',
        'label' => 'hidden',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['airline'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Airline'))
      ->setTranslatable(TRUE)
      ->setSettings(['max_length' => 30,])
      ->setDisplayOptions('view', [
        'type' => 'string',
        'label' => 'hidden',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['airline_logo'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Airline logo'))
      ->setDisplayOptions('view', [
        'type' => 'string',
        'label' => 'hidden',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['state'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Remarks code'))
      ->setTranslatable(TRUE)
      ->setSettings(['max_length' => 30,])
      ->setDisplayOptions('view', [
        'type' => 'string',
        'label' => 'hidden',
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setName('langcode')
      ->setDefaultValue('x-default')
      ->setStorageRequired(TRUE)
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code.'))
      ->setTranslatable(TRUE);

    $fields['default_langcode'] = BaseFieldDefinition::create('boolean')
      ->setName('default_langcode')
      ->setLabel(t('Default Language code'))
      ->setDescription(t('Indicates if this is the default language.'))
      ->setDefaultValue(1)
      ->setTargetEntityTypeId('flights')
      ->setTargetBundle(NULL)
      ->setStorageRequired(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}

<?php

namespace Drupal\moliek\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines response entity for guest book.
 *
 * @ContentEntityType(
 *   id = "response",
 *   label = @Translation("Response"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\moliek\ResponseListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\moliek\Form\ResponseForm",
 *       "edit" = "Drupal\moliek\Form\ResponseForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "response",
 *   admin_permission = "administer response",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *   },
 *   links = {
 *     "add-form" = "/admin/content/response/add",
 *     "canonical" = "/response/{response}",
 *     "edit-form" = "/admin/content/response/{response}/edit",
 *     "delete-form" = "/admin/content/response/{response}/delete",
 *     "collection" = "/admin/content/response"
 *   },
 *   field_ui_base_route = "entity.response.settings"
 * )
 */
class Response extends ContentEntityBase implements ContentEntityInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setPropertyConstraints('value', [
        'Length' => [
          'min' => 2,
          'max' => 100,
          'minMessage' => t('Minimum name length 2 characters.'),
          'maxMessage' => t('Maximum name length 100 characters.'),
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'settings' => [
          'placeholder' => 'Enter name',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'string',
        'label' => 'hidden',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'email_default',
        'settings' => [
          'placeholder' => 'name@example.com',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'email_mailto',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['phone'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Phone'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'telephone_default',
        'settings' => [
          'placeholder' => '+380(__)-___-__-__',
        ],
      ])
      ->setPropertyConstraints('value', [
        'Length' => [
          'max' => 18,
          'maxMessage' => t('Phone must be in this format +380(__)-___-__-__'),
        ],
        'Regex' => [
          'pattern' => '/[+](380)\(\d{2}\)-\d{3}-\d{2}-\d{2}$/',
          'message' => t('Mobile number format is +38(___)-___-__-__'),
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'telephone_link',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['text'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Massage'))
      ->setRequired(TRUE)
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'string_textarea',
        'settings' => [
          'placeholder' => 'It was a wonderful night spent with you Drupal',
        ],
      ])
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'hidden',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['ava'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Avatar'))
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'image',
      ])
      ->setSettings([
        'alt_field' => FALSE,
        'max_filesize' => 2097152,
        'file_extensions' => 'jpeg jpg png',
        'file_directory' => 'response/avatars',
      ])
      ->setDisplayOptions('view', [
        'type' => 'image',
        'label' => 'hidden',
        'settings' => [
          'image_link' => 'file',
          'image_style' => 'thumbnail',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['attachment'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Attachment'))
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'image',
      ])
      ->setSettings([
        'alt_field' => FALSE,
        'max_filesize' => 5242880,
        'file_extensions' => 'jpeg jpg png',
        'file_directory' => 'response/attachment',
      ])
      ->setDisplayOptions('view', [
        'type' => 'image',
        'label' => 'hidden',
        'settings' => [
          'image_link' => 'file',
          'image_style' => 'large',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Posted on'))
      ->setDescription(t('The time that the response was created.'))
      ->setDisplayOptions('view', [
        'type' => 'timestamp',
        'settings' => [
          'date_format' => 'custom',
          'custom_date_format' => 'd/m/Y H:i:s',
        ],
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}

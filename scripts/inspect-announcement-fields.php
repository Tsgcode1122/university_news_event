<?php
foreach (\Drupal::service('entity_field.manager')->getFieldDefinitions('node', 'announcement') as $name => $field) {
 if (str_starts_with($name, 'field_') || $name === 'body') { echo "$name | " . $field->getType() . PHP_EOL; }
}

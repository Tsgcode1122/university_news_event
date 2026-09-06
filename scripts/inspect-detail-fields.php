<?php
foreach (['news_article', 'event', 'spotlight'] as $type) {
  echo "$type\n";
  foreach (\Drupal::service('entity_field.manager')->getFieldDefinitions('node', $type) as $name => $field) {
    if (str_starts_with($name, 'field_') || $name === 'body') {
      echo $name . ' | ' . $field->getLabel() . ' | ' . $field->getType() . PHP_EOL;
    }
  }
}

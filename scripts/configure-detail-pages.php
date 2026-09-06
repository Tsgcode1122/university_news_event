<?php
$fields = [
 'news_article' => ['field_body', 'field_department', 'field_featured_image', 'field_news_category', 'field_publication_date'],
 'event' => ['field_department', 'field_end_date_time', 'field_event_category', 'field_event_description', 'field_featured_image', 'field_location', 'field_registration_link', 'field_start_date_time'],
 'spotlight' => ['field_department', 'field_featured_image', 'field_person_name', 'field_person_type', 'field_publication_date', 'field_spotlight_story'],
];
foreach ($fields as $bundle => $names) {
 $storage = \Drupal::entityTypeManager()->getStorage('entity_view_display');
 $display = $storage->load("node.$bundle.full");
 if (!$display) {
  $default = $storage->load("node.$bundle.default");
  $values = $default ? $default->toArray() : ['targetEntityType' => 'node', 'bundle' => $bundle];
  unset($values['uuid']);
  $values['id'] = "node.$bundle.full";
  $values['mode'] = 'full';
  $values['status'] = TRUE;
  $display = $storage->create($values);
 }
 foreach ($names as $name) {
  $component = $display->getComponent($name) ?? [];
  $component['label'] = 'hidden';
  $display->setComponent($name, $component);
 }
 $display->save();
 echo "Configured $bundle full display.\n";
}

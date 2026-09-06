<?php
$storage = \Drupal::entityTypeManager()->getStorage('entity_view_display');
$display = $storage->load('node.announcement.full');
if (!$display) {
 $default = $storage->load('node.announcement.default');
 $values = $default ? $default->toArray() : ['targetEntityType' => 'node', 'bundle' => 'announcement'];
 unset($values['uuid']);
 $values['id'] = 'node.announcement.full';
 $values['mode'] = 'full';
 $values['status'] = TRUE;
 $display = $storage->create($values);
}
foreach (['field_announcement_body', 'field_department', 'field_expiration_date', 'field_priority', 'field_publication_date'] as $name) {
 $component = $display->getComponent($name) ?? [];
 $component['label'] = 'hidden';
 $display->setComponent($name, $component);
}
$display->save();
echo "Announcement detail display configured.\n";

<?php

// Configure public content search and index existing published content.
$settings = \Drupal::configFactory()->getEditable('search.settings');
$settings->set('default_page', 'node_search')->set('index.minimum_word_size', 1)->save();
foreach (['anonymous', 'authenticated'] as $role_id) {
  $role = \Drupal\user\Entity\Role::load($role_id);
  $role->grantPermission('search content')->save();
}
$page = \Drupal\search\Entity\SearchPage::load('node_search');
$plugin = $page->getPlugin();
$plugin->markForReindex();
for ($batch = 0; $batch < 100; $batch++) {
  $plugin->updateIndex();
  $status = $plugin->indexStatus();
  if (!$status['remaining']) {
    break;
  }
}
echo json_encode($status) . PHP_EOL;

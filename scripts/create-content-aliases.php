<?php
$storage = \Drupal::entityTypeManager()->getStorage('node');
$ids = $storage->getQuery()->accessCheck(FALSE)->condition('type', ['news_article', 'event', 'spotlight', 'announcement', 'page'], 'IN')->execute();
foreach (array_chunk($ids, 50) as $batch) {
 foreach ($storage->loadMultiple($batch) as $node) {
  hub_urls_ensure_alias($node);
  $path = '/node/' . $node->id();
  echo $path . ' -> ' . \Drupal::service('path_alias.manager')->getAliasByPath($path, $node->language()->getId()) . PHP_EOL;
 }
}

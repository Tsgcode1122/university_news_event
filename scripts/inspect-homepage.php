<?php
 echo 'Front: ' . \Drupal::config('system.site')->get('page.front') . PHP_EOL;
 foreach (\Drupal::entityTypeManager()->getStorage('block')->loadMultiple() as $block) {
  if ($block->getTheme() === 'university_hub') { echo json_encode(['id'=>$block->id(),'plugin'=>$block->getPluginId(),'region'=>$block->getRegion(),'visibility'=>$block->getVisibility(),'weight'=>$block->getWeight()]) . PHP_EOL; }
 }

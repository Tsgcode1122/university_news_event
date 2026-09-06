<?php
foreach (['events', 'spotlights'] as $id) {
$v = \Drupal\views\Entity\View::load($id);
echo "$id\n";
foreach ($v->get('display') as $key => $display) {
$o = $display['display_options'];
echo json_encode([$key, array_intersect_key($o, array_flip(['path','filters','sorts','row','defaults']))], JSON_PRETTY_PRINT) . PHP_EOL;
}
}

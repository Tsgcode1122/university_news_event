<?php
use Drupal\views\Views;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
foreach (['news'] as $id) {
  $view = Views::getView($id);
  $view->setDisplay('page_1');
  $view->execute();
  echo "$id: " . count($view->result) . ' results; page size ' . $view->getItemsPerPage() . PHP_EOL;
  if ($node = ($view->result[0]->_entity ?? NULL)) {
    $input = ['keywords' => $node->label()];
    if ($node->hasField('field_department') && !$node->get('field_department')->isEmpty()) {
      $input['department'] = $node->get('field_department')->target_id;
    }
    $extra = $id === 'events' ? 'field_event_category' : ($id === 'spotlights' ? 'field_person_type' : 'field_news_category');
    if ($extra && !$node->get($extra)->isEmpty()) {
      $input[$id !== 'spotlights' ? 'category' : 'role'] = $id !== 'spotlights' ? $node->get($extra)->target_id : $node->get($extra)->value;
    }
    $filtered = Views::getView($id);
    $filtered->setDisplay('page_1');
    $filtered->setExposedInput($input);
    $filtered->execute();
    if (!in_array($node->id(), array_map(fn($r) => $r->_entity->id(), $filtered->result))) { throw new RuntimeException("$id combined filter failure"); }
    echo "Combined filters passed.\n";
  }
  $response = \Drupal::service('http_kernel')->handle(Request::create('/' . $id), HttpKernelInterface::SUB_REQUEST);
  $html = $response->getContent();
  if ($response->getStatusCode() !== 200 || !str_contains($html, 'name="keywords"') || !str_contains($html, 'name="department"')) { throw new RuntimeException("$id render failed"); }
  if (strpos($html, '<header class="news-listing-header">') > strpos($html, '<nav class="news-breadcrumb"')) { throw new RuntimeException('Header order incorrect'); }
  if (!str_contains($html, 'name="category"') || !str_contains($html, 'Showing 1')) { throw new RuntimeException('Category or count missing'); }
echo "Category, live count, and combined filters verified.\n";
  $empty = Views::getView($id);
  $empty->setDisplay('page_1');
  $empty->setExposedInput(['keywords' => 'zzznomatch123456789']);
  $empty->execute();
  if (count($empty->result)) { throw new RuntimeException('No-match filter failed'); }
}

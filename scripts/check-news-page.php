<?php
use Drupal\views\Views;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
$view = Views::getView('news');
$view->setDisplay('page_1');
$view->execute();
echo 'Initial results: ' . count($view->result) . '; total: ' . $view->total_rows . PHP_EOL;
$ids = array_map(fn($r) => $r->_entity->id(), $view->result);
$node = $view->result[0]->_entity ?? NULL;
if ($node) {
  $filtered = Views::getView('news');
  $filtered->setDisplay('page_1');
  $input = ['keywords' => $node->label()];
  if (!$node->get('field_department')->isEmpty()) {
    $input['department'] = $node->get('field_department')->target_id;
  }
  $filtered->setExposedInput($input);
  $filtered->execute();
  if (!in_array($node->id(), array_map(fn($r) => $r->_entity->id(), $filtered->result))) {
    throw new RuntimeException('Combined filters failed');
  }
  echo "Combined headline and department filters passed.\n";
}
$empty = Views::getView('news');
$empty->setDisplay('page_1');
$empty->setExposedInput(['keywords' => 'zzzznomatchingheadline123456']);
$empty->execute();
if (count($empty->result)) { throw new RuntimeException('Empty filter failed'); }
echo "No-match filter passed.\n";
$request = Request::create('/news');
$response = \Drupal::service('http_kernel')->handle($request, HttpKernelInterface::SUB_REQUEST);
$html = $response->getContent();
foreach (['news-listing-header', 'news-listing-grid', 'name="keywords"', 'name="department"'] as $marker) {
  if (!str_contains($html, $marker)) { throw new RuntimeException('Missing markup: ' . $marker); }
}
echo 'Page render HTTP ' . $response->getStatusCode() . "; header, filters and grid present.\n";
file_put_contents('/tmp/news-page-check.html', $html);
$page = \Drupal\search\Entity\SearchPage::load('node_search');
echo 'Hero search index: ' . json_encode($page->getPlugin()->indexStatus()) . PHP_EOL;

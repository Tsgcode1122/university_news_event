<?php

use Drupal\views\Entity\View;

$view = View::load('news');
$displays = $view->get('display');
$options = &$displays['page_1']['display_options'];
$base = $displays['default']['display_options'];
foreach (['filters', 'filter_groups', 'pager', 'exposed_form', 'css_class', 'empty'] as $key) {
  $options['defaults'][$key] = FALSE;
}
$options['filters'] = array_intersect_key($base['filters'], array_flip(['status', 'type', 'field_department_target_id']));
$options['filters']['field_department_target_id']['expose']['label'] = 'Department';
$options['filters']['field_department_target_id']['expose']['identifier'] = 'department';
$options['filters']['title'] = [
  'id' => 'title', 'table' => 'node_field_data', 'field' => 'title',
  'entity_type' => 'node', 'entity_field' => 'title', 'plugin_id' => 'string',
  'operator' => 'contains', 'value' => '', 'group' => 1, 'exposed' => TRUE,
  'expose' => ['label' => 'Search headlines', 'identifier' => 'keywords', 'required' => FALSE, 'remember' => FALSE, 'use_operator' => FALSE],
];
// Place the keyword input before the department selector.
$filters = $options['filters'];
$options['filters'] = ['status' => $filters['status'], 'type' => $filters['type'], 'title' => $filters['title'], 'field_department_target_id' => $filters['field_department_target_id']];
$options['filter_groups'] = ['operator' => 'AND', 'groups' => [1 => 'AND']];
$options['pager'] = ['type' => 'full', 'options' => ['items_per_page' => 9, 'offset' => 0, 'id' => 0, 'quantity' => 5, 'tags' => ['first' => 'First', 'previous' => 'Previous', 'next' => 'Next', 'last' => 'Last']]];
$options['exposed_form'] = ['type' => 'basic', 'options' => ['submit_button' => 'Search news', 'reset_button' => FALSE]];
$options['css_class'] = 'news-listing';
$options['empty'] = ['area' => ['id' => 'area', 'table' => 'views', 'field' => 'area', 'plugin_id' => 'text', 'empty' => TRUE, 'content' => ['value' => '<h2>No matching news</h2><p>Try another keyword or select a different department.</p>', 'format' => 'basic_html']]];
$view->set('display', $displays)->save();
echo "News page configured with headline search, department filter and 9 cards per page.\n";

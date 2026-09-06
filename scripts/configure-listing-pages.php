<?php
use Drupal\views\Entity\View;
$news = View::load('news')->get('display')['page_1']['display_options'];
foreach (['events', 'spotlights'] as $id) {
  $view = View::load($id);
  $displays = $view->get('display');
  $base = $displays['default']['display_options'];
  $options = &$displays['page_1']['display_options'];
  foreach (['filters', 'filter_groups', 'pager', 'exposed_form', 'css_class', 'empty', 'style', 'row'] as $key) {
    $options['defaults'][$key] = FALSE;
  }
  $options['filters'] = array_intersect_key($base['filters'], array_flip(['status', 'type', 'field_start_date_time_value']));
  $options['filters']['title'] = $news['filters']['title'];
  $options['filters']['title']['expose']['label'] = $id === 'events' ? 'Search event titles' : 'Search spotlight titles';
  $department = $news['filters']['field_department_target_id'];
  $options['filters']['field_department_target_id'] = $department;
  $field = $id === 'events' ? 'field_event_category_target_id' : 'field_person_type_value';
  $filter = $base['filters'][$field];
  $filter['exposed'] = TRUE;
  $filter['expose']['label'] = $id === 'events' ? 'Event category' : 'Community role';
  $filter['expose']['identifier'] = $id === 'events' ? 'category' : 'role';
  $options['filters'][$field] = $filter;
  $options['filter_groups'] = $news['filter_groups'];
  $options['pager'] = $news['pager'];
  $options['pager']['options']['items_per_page'] = $id === 'events' ? 6 : 9;
  $options['exposed_form'] = $news['exposed_form'];
  $options['exposed_form']['options']['submit_button'] = 'Find ' . $id;
  $options['style'] = ['type' => 'default', 'options' => ['default_row_class' => TRUE]];
  $options['row'] = ['type' => 'entity:node', 'options' => ['view_mode' => 'teaser']];
  $options['css_class'] = 'news-listing ' . $id . '-listing';
  $options['empty'] = $news['empty'];
  $options['empty']['area']['content']['value'] = '<h2>No matching ' . $id . '</h2><p>Try another title, choose a different filter, or clear your filters to browse available ' . $id . '.</p>';
  $view->set('display', $displays)->save();
  echo "Configured $id page.\n";
  unset($options);
}

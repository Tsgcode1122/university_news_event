<?php
$view = \Drupal\views\Entity\View::load('news');
$displays = $view->get('display');
$options = &$displays['page_1']['display_options'];
$options['filters']['title']['expose']['label'] = 'Search news';
$options['filters']['title']['expose']['placeholder'] = 'Search headlines or keywords…';
$category = $options['filters']['field_department_target_id'];
$category['id'] = 'field_news_category_target_id';
$category['table'] = 'node__field_news_category';
$category['field'] = 'field_news_category_target_id';
$category['vid'] = 'news_categories';
$category['hierarchy'] = FALSE;
$category['expose']['label'] = 'News category';
$category['expose']['identifier'] = 'category';
$options['filters']['field_news_category_target_id'] = $category;
$view->set('display', $displays)->save();
echo "News category filter configured.\n";

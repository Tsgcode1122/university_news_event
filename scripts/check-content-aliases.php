<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
foreach (['news_article', 'event', 'spotlight', 'announcement'] as $type) {
 $ids = \Drupal::entityQuery('node')->accessCheck(TRUE)->condition('type', $type)->condition('status', 1)->range(0, 1)->execute();
 $node = \Drupal\node\Entity\Node::load(reset($ids));
 $url = $node->toUrl()->toString();
 if (str_starts_with($url, '/node/')) { throw new RuntimeException('Alias missing'); }
 $response = \Drupal::service('http_kernel')->handle(Request::create($url), HttpKernelInterface::SUB_REQUEST);
 if ($response->getStatusCode() !== 200) { throw new RuntimeException('Alias failed: ' . $url); }
 echo "$type: $url HTTP 200\n";
}

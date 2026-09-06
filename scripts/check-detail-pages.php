<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
foreach (['news_article' => 'news-article', 'event' => 'event', 'spotlight' => 'spotlight'] as $type => $class) {
 $ids = \Drupal::entityQuery('node')->accessCheck(TRUE)->condition('type', $type)->condition('status', 1)->range(0, 1)->execute();
 if (!$ids) { throw new RuntimeException("No published $type available to verify"); }
 $id = reset($ids);
 $response = \Drupal::service('http_kernel')->handle(Request::create('/node/' . $id), HttpKernelInterface::SUB_REQUEST);
 $html = $response->getContent();
 if ($response->getStatusCode() !== 200) { throw new RuntimeException("$type HTTP failure"); }
 foreach (['hub-detail--' . $class, 'detail-story', 'detail-breadcrumb', 'detail-footer'] as $marker) {
  if (!str_contains($html, $marker)) { throw new RuntimeException("$type missing $marker"); }
 }
 if ($type === 'event' && !str_contains($html, 'Event details')) { throw new RuntimeException('Missing event details'); }
 if (in_array($type, ['news_article', 'spotlight'], TRUE) && !preg_match('/class="detail-related-image"[\s\S]*?<img\s/', $html)) { throw new RuntimeException("$type missing related thumbnail"); }
 echo "$type /node/$id: HTTP 200; detail template and navigation verified.\n";
}

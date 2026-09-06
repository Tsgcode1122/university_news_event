<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
foreach (['/', '/home'] as $path) {
 $response = \Drupal::service('http_kernel')->handle(Request::create($path), HttpKernelInterface::SUB_REQUEST);
 $html = $response->getContent();
 if ($response->getStatusCode() !== 200) { throw new RuntimeException("$path failed"); }
 foreach (['hero-title', 'announcements-section', 'featured-news-section', 'upcoming-events-section', 'spotlights-section'] as $marker) {
  if (!str_contains($html, $marker)) { throw new RuntimeException("$path missing $marker"); }
 }
 foreach (['No front page content', 'view-frontpage', 'Subscribe to'] as $marker) {
  if (str_contains($html, $marker)) { throw new RuntimeException("$path includes default listing: $marker"); }
 }
 echo "$path HTTP 200; hero and all four sections present; default listing absent.\n";
}

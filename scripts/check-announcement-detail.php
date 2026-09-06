<?php
$ids = \Drupal::entityQuery('node')->accessCheck(TRUE)->condition('type', 'announcement')->condition('status', 1)->range(0, 1)->execute();
if (!$ids) { throw new RuntimeException('No published announcement to check'); }
$id = reset($ids);
$response = \Drupal::service('http_kernel')->handle(\Symfony\Component\HttpFoundation\Request::create('/node/' . $id), \Symfony\Component\HttpKernel\HttpKernelInterface::SUB_REQUEST);
if ($response->getStatusCode() !== 200) { throw new RuntimeException('Page failed'); }
foreach (['announcement-detail-header', 'detail-story', 'Announcement details', 'Back to the hub'] as $marker) {
 if (!str_contains($response->getContent(), $marker)) { throw new RuntimeException('Missing: ' . $marker); }
}
echo "Announcement /node/$id: HTTP 200; header, content, details and back link verified.\n";

<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
$response = \Drupal::service('http_kernel')->handle(Request::create('/user/login'), HttpKernelInterface::SUB_REQUEST);
$html = $response->getContent();
foreach (['login-card', 'name="name"', 'name="pass"', 'name="form_id"', '/user/password', 'footer-admin-login'] as $marker) {
  if (!str_contains($html, $marker)) { throw new RuntimeException('Missing login markup: ' . $marker); }
}
if ($response->getStatusCode() !== 200) { throw new RuntimeException('Login page did not return 200'); }
echo "Login HTTP 200; styled template, Drupal login fields, password reset and footer link verified.\n";

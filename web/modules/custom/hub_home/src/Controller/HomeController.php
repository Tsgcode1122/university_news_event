<?php

namespace Drupal\hub_home\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Serves the homepage, with content supplied by the theme and homepage blocks.
 */
final class HomeController extends ControllerBase {

  public function page(): array {
    return ['#markup' => '', '#cache' => ['tags' => ['config:system.site']]];
  }

}

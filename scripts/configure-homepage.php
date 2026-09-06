<?php
\Drupal::configFactory()->getEditable('system.site')->set('page.front', '/home')->save();
echo "Dedicated homepage configured at /home, served at /.\n";

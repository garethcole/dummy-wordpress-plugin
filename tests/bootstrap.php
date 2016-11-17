<?php

/**
 * Autoload the composer items.
 */
require_once __DIR__ . '/../../../../../vendor/autoload.php';

WP_Mock::bootstrap();

/**
 * Bootstrap plugin
 */
require_once __DIR__ . '/../plugin.php';
<?php
// router.php

// If the requested file exists, return it
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $fullPath = __DIR__ . $path;

    if (is_file($fullPath)) {
        return false; // Serve the requested resource as-is.
    }
}

// Otherwise, load index.php
require_once __DIR__ . '/index.php';

<?php
/**
 * Configuration file for the Dark Sky Reserves starter application.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'dark_sky');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Escape a value before displaying it in HTML.
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php://stderr');

// Log environment variables for debugging
error_log("WordPress Database Settings:");
error_log("DB_HOST: " . getenv('WORDPRESS_DB_HOST'));
error_log("DB_USER: " . getenv('WORDPRESS_DB_USER'));
error_log("DB_NAME: " . getenv('WORDPRESS_DB_NAME'));

// ** Database settings - You can get this info from your web host ** //
define('DB_NAME', getenv('WORDPRESS_DB_NAME'));
define('DB_USER', getenv('WORDPRESS_DB_USER'));
define('DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD'));
define('DB_HOST', getenv('WORDPRESS_DB_HOST'));
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// Try to connect to the database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    error_log("Failed to connect to MySQL: " . $mysqli->connect_error);
    error_log("Connection info - Host: " . DB_HOST . ", User: " . DB_USER . ", Database: " . DB_NAME);
}

$mysqli->close();

// Authentication Unique Keys and Salts
define('AUTH_KEY',         '4K]D-0~+K-OL|9+6G+K9Hs4jH~1x%WV-kEu7Xt7|LQ6Y5Uc9N');
define('SECURE_AUTH_KEY',  'XmK8|4R+K-s5H~1x%WV-kEu7Xt7|LQ6Y5Uc9NjH~1x%WV-kE');
define('LOGGED_IN_KEY',    'K9Hs4jH~1x%WV-kEu7Xt7|LQ6Y5Uc9NjH~1x%WV-kEu7Xt7|');
define('NONCE_KEY',        'H~1x%WV-kEu7Xt7|LQ6Y5Uc9NjH~1x%WV-kEu7Xt7|LQ6Y5U');
define('AUTH_SALT',        'kEu7Xt7|LQ6Y5Uc9NjH~1x%WV-kEu7Xt7|LQ6Y5Uc9NjH~1x');
define('SECURE_AUTH_SALT', 'Xt7|LQ6Y5Uc9NjH~1x%WV-kEu7Xt7|LQ6Y5Uc9NjH~1x%WV');
define('LOGGED_IN_SALT',   'LQ6Y5Uc9NjH~1x%WV-kEu7Xt7|LQ6Y5Uc9NjH~1x%WV-kEu');
define('NONCE_SALT',       'Uc9NjH~1x%WV-kEu7Xt7|LQ6Y5Uc9NjH~1x%WV-kEu7Xt7|');

$table_prefix = 'wp_';

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

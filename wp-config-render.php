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
$db_host = getenv('WORDPRESS_DB_HOST');
$db_user = getenv('WORDPRESS_DB_USER');
$db_password = getenv('WORDPRESS_DB_PASSWORD');
$db_name = getenv('WORDPRESS_DB_NAME');

// Fallback values if environment variables are not set
if (empty($db_host)) {
    // Try to get the private domain from Render's environment
    $private_domain = getenv('RENDER_SERVICE_DOMAIN');
    $db_host = $private_domain ? $private_domain . ':3306' : 'mysql-db:3306';
}
if (empty($db_user)) $db_user = 'wordpress';
if (empty($db_password)) $db_password = 'MySecurePass123!@#';
if (empty($db_name)) $db_name = 'wordpress';

// Parse host and port from DB_HOST
$host_parts = explode(':', $db_host);
$host = $host_parts[0];
$port = isset($host_parts[1]) ? intval($host_parts[1]) : 3306;

error_log("Parsed connection details:");
error_log("Host: " . $host);
error_log("Port: " . $port);
error_log("User: " . $db_user);
error_log("Database: " . $db_name);

define('DB_NAME', $db_name);
define('DB_USER', $db_user);
define('DB_PASSWORD', $db_password);
define('DB_HOST', $host);
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// Try to connect to the database with retry logic
$max_retries = 30;  // Increased retries for initial setup
$retry_interval = 5; // Increased interval between retries
$connected = false;

for ($i = 0; $i < $max_retries; $i++) {
    try {
        error_log("Attempt " . ($i + 1) . " to connect to MySQL");
        error_log("Connection params - Host: " . $host . ", Port: " . $port . ", User: " . $db_user . ", Database: " . $db_name);
        
        // Try different hostnames if the connection fails
        $hostnames = [
            $host,                    // Original hostname
            $host . '.svc.internal',  // Render internal service domain
            $host . '.internal',      // Alternative internal domain
            'localhost'               // Local fallback
        ];
        
        $connected = false;
        foreach ($hostnames as $try_host) {
            error_log("Trying hostname: " . $try_host);
            try {
                $mysqli = new mysqli($try_host, $db_user, $db_password, $db_name, $port);
                if (!$mysqli->connect_error) {
                    error_log("Successfully connected to MySQL using host: " . $try_host);
                    $connected = true;
                    $mysqli->close();
                    break;
                }
                error_log("Failed to connect using host " . $try_host . ": " . $mysqli->connect_error);
            } catch (Exception $e) {
                error_log("Exception trying host " . $try_host . ": " . $e->getMessage());
            }
        }
        
        if ($connected) {
            break;
        }
        
        if ($i < $max_retries - 1) {
            error_log("All connection attempts failed. Waiting " . $retry_interval . " seconds before retry...");
            sleep($retry_interval);
        }
    } catch (Exception $e) {
        error_log("Exception in connection loop: " . $e->getMessage());
        if ($i < $max_retries - 1) {
            sleep($retry_interval);
        }
    }
}

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

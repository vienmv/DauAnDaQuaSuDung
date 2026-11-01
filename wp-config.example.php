<?php
/** Copy to wp-config.php and set environment variables on the hosting server. */
function anhtrang_required_env($name) {
    $value = getenv($name);
    if ($value === false || $value === '') {
        throw new RuntimeException('Missing WordPress environment variable: ' . $name);
    }
    return $value;
}
define('DB_NAME', anhtrang_required_env('DB_NAME'));
define('DB_USER', anhtrang_required_env('DB_USER'));
define('DB_PASSWORD', anhtrang_required_env('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
foreach (array('AUTH_KEY','SECURE_AUTH_KEY','LOGGED_IN_KEY','NONCE_KEY','AUTH_SALT','SECURE_AUTH_SALT','LOGGED_IN_SALT','NONCE_SALT') as $name) {
    define($name, anhtrang_required_env($name));
}
$table_prefix = 'bz_';
define('WP_HOME', 'https://dauandaquasudung.com');
define('WP_SITEURL', 'https://dauandaquasudung.com');
define('WP_ENVIRONMENT_TYPE', 'production');
define('WP_DEBUG', false);
define('DISALLOW_FILE_EDIT', true);
if (!defined('ABSPATH')) define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-settings.php';

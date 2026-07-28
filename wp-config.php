<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'yiigrysxa_dauandaquasudung' );

/** Database username */
define( 'DB_USER', 'yiigrysxa_dauandaquasudung' );

/** Database password */
define( 'DB_PASSWORD', 'ZZrY7Dq7LP' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ')BV(]B.&*bp`0fbYGdU1B,dF$phn~Uj3;PRQ#-$.o8~)znyA*YO|&_!@i8v6:bm$' );
define( 'SECURE_AUTH_KEY',  'RwXIl C;*5,ez%vCrF*ni ?24mANl+${ER  pScVu0h_4h;e/4>9]yUoi-_S%|5v' );
define( 'LOGGED_IN_KEY',    'jz8JUD%[mfe0oZc:k/i!bA)*H)P.9Bj^ytH3ia0&8l9#~Q+-a8fFywz0hUPE?@) ' );
define( 'NONCE_KEY',        '/xopOH_1-$b@|bzL.OXoX8?*4PuEsm5;r9j8iO:jh9ID25l$s1Y{wsltNk(8H;&K' );
define( 'AUTH_SALT',        ')=%BX2#lUH5Bw:k@cl(/Mo1@e1Hf_o`!).ia4[2=jA$BY8j/3}QSA`-s#tkXsWF,' );
define( 'SECURE_AUTH_SALT', '`YdgBN9HQ>w:A)HWRR;x;iTOS`~QrsC?@SGg0$5eQns`#Z5;4.TWl%rdUB}qh`tv' );
define( 'LOGGED_IN_SALT',   'KT_xC&v~wO*9sR/HKEINctskUqhp4U: }=eNeYs,Exx8Q):N-zFpo+Q|Y5n_,A+{' );
define( 'NONCE_SALT',       ']O>jg~FBR$W~MC314JGhZx84V~vTSx_[z=]X|QIpbBj@3)jmTecO[l*>f35Q}|y1' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bz_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

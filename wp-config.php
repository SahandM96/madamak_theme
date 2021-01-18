<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', "madamadc_1" );

/** MySQL database username */
define( 'DB_USER', "madamadc_1" );

/** MySQL database password */
//define( 'DB_PASSWORD', "Madamakkhoshgele" );
define( 'DB_PASSWORD', "Madamakkhoshgele" );

/** MySQL hostname */
define( 'DB_HOST', "localhost" );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'iZGN_<osO&y:S6G=,m]UTJo}JU5mal~ED;%@=SIj!8L4sVEW09<2#SC+`kQl=CK$' );
define( 'SECURE_AUTH_KEY',  '0cs-,LIOiB5,m$-;F2EBh/4D-,pW:cfvSGLq.Z]c#,Z&hAHk|eJLJt5#7g2mY4f$' );
define( 'LOGGED_IN_KEY',    'vF=_w4b_/DDU89I2K~5T%^>hvcVKzg5H;sG1 iN)-@1g*(J*]q0n=(~~c0@paz!n' );
define( 'NONCE_KEY',        'wNlsOtSC0_suuit=;-3jlJ<WkuJ4Y4mf 99n7b}N=)dYHs^Oi$He:vZW+YTpUMoQ' );
define( 'AUTH_SALT',        '9+AfLS,Xe%fU`wMQzcsNEMV,4i6.Iro]LVfYd#*F pC %H4jHvF 1!yAF4BIG/*J' );
define( 'SECURE_AUTH_SALT', '`?n%j5Y]LJ3,p%|Xi/2CAN4q (z2RN8fPk;h[9)4SsadW7_sf9Z1(qi,!fjm7~Cs' );
define( 'LOGGED_IN_SALT',   ' TB`[1&T$pjEfaC?*&#9je7`0>aLn;i=W_1_i{9ogwYtt&S)O=1__,`ig #POBUW' );
define( 'NONCE_SALT',       '_cKN>S GN:RW:/$`IosYgnXX{<!p%TkmT?vc;yqh H4pK/@m0rPSi,Fkt#E4FM&w' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

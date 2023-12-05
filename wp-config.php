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
define( 'DB_NAME', 'soko' );

/** Database username */
define( 'DB_USER', 'admin' );

/** Database password */
define( 'DB_PASSWORD', 'admin' );

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
define( 'AUTH_KEY',         'ATy#98V^y7(PSr6]W[puwf_6KbE]_!F=%x,*Z_~aXgs;3S,Db8TrJp24).{XQB($' );
define( 'SECURE_AUTH_KEY',  '*ejoU)ezt5;v$qO`o:I?]!r{4ZsO#bk%oL|v+%K;HZ{5*;9:e-6sXWad`T_U(IMj' );
define( 'LOGGED_IN_KEY',    'YX<Z,!Xl*;<b9;V8|<`!DpDZ!1i|-F%n:HF}0k}3FsDrCxBXjS4$AV0c4B%*&l`=' );
define( 'NONCE_KEY',        'pBcyj3zR/YRR_ AW}QB.QQrP$&Y`.h`M%uiqF6+Y0X5p S3qkAQnIN<5*/7ep33$' );
define( 'AUTH_SALT',        'S;.d.fq+U@nbHWkCuxhR.V3Tm2fTps6;/ZNYlD&eLje!{7j|Youx|Z!@CN2mM[,0' );
define( 'SECURE_AUTH_SALT', '+ftf(H5aBM#x::&`Q9yJ|-t;S]WTM)V.%cO:b~adXP.3P<}2Q]1+w[TEs/bWFlB ' );
define( 'LOGGED_IN_SALT',   '1[1Xp[[J)O_n+}<)rn bh}iBd7pKPq$kB^:a,8URK;.NDB&o+i|@i&uA%|{>OF|$' );
define( 'NONCE_SALT',       ':+ M(_V{7wr,(gKd+^$@TBUPufYg2If%UruMP}}t/ho}5vQRTSA?R5YhvF&yAa@L' );

/**#@-*/

/**
 * WordPress database table prefix.
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

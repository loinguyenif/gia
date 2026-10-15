<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'teg_gia' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'enZ<<H459gzRoQ7~6Vj2=Hm%E9Tb0`.D(+j5^^@smHyyC6{3sO/rX3/e:.|.=,kT' );
define( 'SECURE_AUTH_KEY',  '-).1Z;*6 gA!Nu!x(:k^ X9c{A4n?eU}{S:gFS|s^;3GjOK;@.TIJP*|Zk4qGFwX' );
define( 'LOGGED_IN_KEY',    ' W}>wv.[U=$n%UrxY1`h!/36vI$AJLt[g(05^=)u]TOiPL-~Xw@9Y6Vo]g}w^v|o' );
define( 'NONCE_KEY',        '!u)O*RhQXeo[ZL{YP/vfAvl3l$j,1Eo.A0d/-?Gi4Or?H+Aqw>h8T~Sa-q#,T)YS' );
define( 'AUTH_SALT',        'V}u)LJ)z,+$teQYNSr0.EGVA`53z{|Fo5c7h[s1EzEGw[=)Z{,NnCCu] {bCr]7%' );
define( 'SECURE_AUTH_SALT', 'OKn^nyuE :M zETs+0Fg,bguKR?;uFw=(WbcszOt{@qx.KU)//s2O$?D_4!,r-4h' );
define( 'LOGGED_IN_SALT',   'a<w~zJZb+b2Y!X/<@6S+u-kdd|t$^B%)K<:PY;>TYr](]J>p!!/~}F9/Uykji &1' );
define( 'NONCE_SALT',       'jS?45r(cyh9dJJU8A]/$rWU2e~#H^^&>38YbO~={]P:_Ci!<r$t8cmRSZ vT7KyT' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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

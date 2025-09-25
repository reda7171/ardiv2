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
define('DB_NAME', 'webin2605922_9gedqe');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', '');

/** Database hostname */
define('DB_HOST', 'localhost');

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
define( 'AUTH_KEY',         '49!$RR#z4gBJGGyQgkqE!&<J2x,-XcuAy9t$bLM>@vCqpKF0Q#ViNnJG0@,rk_HA' );
define( 'SECURE_AUTH_KEY',  '*k7jWfrC}[n`2C1)(cz35+oR_l{$i=>g#jr7K44Gu@-hM0j;9bn~kzEJ0^3OWcJ(' );
define( 'LOGGED_IN_KEY',    '`EpNGC`MX,zVsm@5DYuJ{mc~(Ga+BHZOzP;m4A)v*p,5p.XGo)Q60NE|&QJ]Koc}' );
define( 'NONCE_KEY',        '*R*DNK9?:Rr~iSGJS.Y62Exi97/|z4G+sGk&5ah epHoiD$]7Te=/)wmbD%nx907' );
define( 'AUTH_SALT',        '|,vwO7e]|xi>N-;Qg#C~n)H?L!:oJ[~v3}j?L&M70pJOrb8t#1RtSK+&J/VHU3)s' );
define( 'SECURE_AUTH_SALT', 'o,Qm*m&M5`x6:~5|nxum)yL$]w(7aK!|z>o{i$5o9}SFF~vlOXy81AEBMYcp&/wS' );
define( 'LOGGED_IN_SALT',   'uQB./kD|uf[kqU:r)a?`CDHRvZ?f-24G5d@J`{XHTaMj/]d~hq!UNlplyAFZ@D=Q' );
define( 'NONCE_SALT',       '6}-kmHWE9JXOt%qc|bo4ON?X[#p=J<cc-tAH$=I;VoKAqo>qCMu{1hob{,4kqjSt' );

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



define( 'CONCATENATE_SCRIPTS', 0 );
define( 'DISALLOW_FILE_EDIT', 0 );
/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

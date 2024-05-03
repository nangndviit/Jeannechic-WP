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
define( 'DB_NAME', 'nang_jene' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'uB<cdB.)DY#*%>ct0G-|b#Z&h*h}*@Pzj*1Qr!:?&nH5yL)|q]vPj]E7`p5`S:{s' );
define( 'SECURE_AUTH_KEY',  'qxFysq{&dv9!_53[h,OmBM$q#rtMwP2Q]`pd`66=L-O_-u@|E`$nWl>nz]Jm|%]P' );
define( 'LOGGED_IN_KEY',    'OAk4z-WGNO=HKwzHBB=;FhXNSe>}XMHLR|HS[C*)g:L,!gN`(CXEILcU+Jd.``t/' );
define( 'NONCE_KEY',        '.I9Z~>Cj/+bU9!RwurZTch*U_(?x*B!}pcNv6?)9|jJ%3r+&gP^sw2p|&+medPDh' );
define( 'AUTH_SALT',        '%Kc%%0<zh=R9?`0N-zhp{UCep{Yx|Y@thLPOF^M}JNyW:(hop2%DM&GN8FH<)Ai+' );
define( 'SECURE_AUTH_SALT', '9],:sHw=KB $D/9mh?|,^@!R3p*y_Uq_VNC,G*Q:Mj)`1;pZ^h&;Bh`b$.XGvSk-' );
define( 'LOGGED_IN_SALT',   '?uXjinL=>XH~]oSIJjxyj6<Nmy)WO@>QZB>g}UHXC-?3fs!8T:)>_V!hLGiUgBxY' );
define( 'NONCE_SALT',       '3mu2i5aMOTF=&7` coruvR}w==k5RAFwM*]No;A&&(hEJ<tvMO~4,HEmw;-S=rzb' );

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

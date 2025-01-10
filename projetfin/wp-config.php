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
define( 'DB_NAME', 'projetfin' );

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
define( 'AUTH_KEY',         '5W;z}@F6;^l`>^I44|6:*TSl|j8H^.q5Ryj0.lhcic pO#>C,gD+zthuR2*xl=}%' );
define( 'SECURE_AUTH_KEY',  '|8aD3.trKeuTY h:aBF$!!iskW58RL2)^k9.X0:Stv,noKqk2pQUrB+Zx,&ZRiL<' );
define( 'LOGGED_IN_KEY',    'UVapX?i&a`X3s|8.M??vgzt/qFlelH5[t^VWEBp,_EPAAMv=#z1WIA;OIj.(TT}r' );
define( 'NONCE_KEY',        'uS8jUY#,2OD9T/EF(3r!Bb(rn.F3eRj_BK53cL&@dJI~tiL-Ve:-+Jur{k><]OP_' );
define( 'AUTH_SALT',        'b41LZrwU<bS]_Yf9VX<ua;lM[wutQzr?2|uLzp(v-oD8YKgoU*EOzW27F58RKe[[' );
define( 'SECURE_AUTH_SALT', 'Uf_@uo{a(oTGl83Ru$|0v& 6h81 W!b%)xV|]Pl2yFBwFs6-[d{OrgmJ#  8Bi]o' );
define( 'LOGGED_IN_SALT',   '}<a*;/dUGKO#b[,3<]&GUPs}j.2ZECsb>`kzHZ2lf{>DN;GHaSEF1?PCu )6H#T<' );
define( 'NONCE_SALT',       'Bt$PuXpD+?v-/yv}1=zy+^A;QlV=g57@65q|+7E 0ZvyL4Vd|J|X8Tyxxh;iW?@*' );

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
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false); // Évitez d'afficher les erreurs sur le site




/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

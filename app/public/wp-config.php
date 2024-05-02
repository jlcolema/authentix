<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL cookie settings
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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '|~=rrA8AC`fq,v^^9.dcSu :eg!|zs*Q2nAz1MbR/hwU*1=_)3g`QatRo!ZMh,U?' );
define( 'SECURE_AUTH_KEY',   '7Df1Ts;jkP,m2re(r>K3kPnZFb.j.qLjl*%]eD^WY,GXB~_)mtUV!aK]Y]SxK.Sv' );
define( 'LOGGED_IN_KEY',     'XF9RO}$FNW)Iyn|6Xzkn#i{)3O}?WZ=nLxD+XoU}Xfd8*gR)72-o, 2%b0t?Zawv' );
define( 'NONCE_KEY',         'LU<][1$aH,/#f<4fVRX(QS>$v@V<r} m$}FZIr/ZBhDyY|b7><Nmihv}V:/PI,#e' );
define( 'AUTH_SALT',         ')!8#cE0^_j~0.cU]z*x Ay.[=$vyR.9~$%c/]p( oO2~CE*Z5)Fkd`px@u1naU7.' );
define( 'SECURE_AUTH_SALT',  'kh]F1#2V[^|mF7w%.?my~RJADpy7K%t>prM[U>,aeRcQQg*ot$Oh~aw6NA-GY|jx' );
define( 'LOGGED_IN_SALT',    '5c)X&NPA-}UmRmY/:<rljj_?^m,0k<YW3D]v;^-Pjhxs!rBx6Qr(=m!a4GkM$X5_' );
define( 'NONCE_SALT',        '@s2tpY&Lh!IxGu8y,m32B..sW.IOa>*?6v@~ eD<{$eb!.hgtp5{9t(FTK4.E=2x' );
define( 'WP_CACHE_KEY_SALT', '77>6,tpjZ8#J(2$=X0k$i_g.Ym9E~vL]Sfa3wpM>bHns3l=;28+*I4-0zOCT8{2Y' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

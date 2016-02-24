<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'iiblms_com');

/** MySQL database username */
define('DB_USER', 'iiblmscom');

/** MySQL database password */
define('DB_PASSWORD', '^Yji62Pr');

/** MySQL hostname */
define('DB_HOST', 'mysql.iiblms.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Fb__9)*SPG@!Q620cM&!BHkg0S+:KZI+Sr0Zkki"H2i0D8V;IfFsb&D$Mo%k65t"');
define('SECURE_AUTH_KEY',  'Iv@)5p!@HYj3U*4vOEm#A#CdqLX4E)y1/zJ8P3up2P::^I)h)2(eCh|v3vziXXK"');
define('LOGGED_IN_KEY',    ':VWIs(yrn|+Dgha|VTe7fx8KQC7h|tkVtf@r@m%?0DsI^S66b5:M5l#iwIL_s&u|');
define('NONCE_KEY',        'nDkfaphbq5:i2JgWDPN^@(dnzp3A%39BOUu$#rwP!;JAcU&72oCdU1NsYydQ4o$h');
define('AUTH_SALT',        'iq6H5PNHD1XA1R(@/KFdX^*myx8;Eeg^Dk!LI)QgQ^N9mSkmEOx$ut~W)Rc8;w)v');
define('SECURE_AUTH_SALT', 'NLUEbskbQwyx:noPYZ7PPVn~+|`^+4%2)y~TIWZ"3jxOhrspRSE91oQaqK"QEbJa');
define('LOGGED_IN_SALT',   'Gy5`n+P47ua;:Az$K::et;UM?*07ot36Fnb0t*xOKG!Dt~rn9JBl^VPEF&*J*_gp');
define('NONCE_SALT',       'Pu*yqj0x+;AZC%h"/zl1A5rj~g+/V%TxCLXy3p@F"nFdI1BU7B|^CQXh6Kfi/u%"');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_hkaqty_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


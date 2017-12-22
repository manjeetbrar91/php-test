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
define('DB_NAME', 'admin_test');

/** MySQL database username */
define('DB_USER', 'admin_testuser');

/** MySQL database password */
define('DB_PASSWORD', 'QB8lhXwAMk');

/** MySQL hostname */
define('DB_HOST', 'wine-oh.io');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '?26G8Zr*C8B-P0n@E/T9pPB}WK13t)$60z518mruLIfgf=61jFhmQ5:8aqh~pw[T');
define('SECURE_AUTH_KEY',  'gKdhjk>+?Xu$HEqgF~*1aw]sNTpSqpn](NxL`kwhKJ!OudWR&4-t[:Vi9JS3A58i');
define('LOGGED_IN_KEY',    '@`FHhC`<%C9knx+o~u`?fUsDuUm/3aXbYC(tx^3`oK]0?i$lP+Qd)Tt9-(M$=:iB');
define('NONCE_KEY',        'Sib(WBS7}`kv;.O_%->q/v7>BqbDgv5gY`<[%VLT;>(m.Wrj{tY+zed!oX->#dtS');
define('AUTH_SALT',        'H;.|BMkMrN#iC<|~&Hy[$<>$^aJK1l=vi[8wdyR|+fkY]?F(MK^$*Lb?gt2tuO57');
define('SECURE_AUTH_SALT', 'nc(yR9UEcuFu|]%9Wa:ai*L:Qo!b#lOa+v9fpsMxi-HYjqF66)!@(eZIJn4Oyt1 ');
define('LOGGED_IN_SALT',   'GO#ZgM*@BSWN]YXT@ V_Px~1[BKY:}Lrx*2+O.lkD$Ob1i=u1Q(L&xotew<(L?_=');
define('NONCE_SALT',       'Ks(44)SFYlc<!TTYat_!L1cfC~h&?0(6DM%-FiHCceS6uaT&Y^;w?w3Wf kC5!A#');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpm_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

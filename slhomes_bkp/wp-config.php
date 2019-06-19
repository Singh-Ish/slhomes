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
define('DB_NAME', 'slhomesi_new');

/** MySQL database username */
define('DB_USER', 'slhomesi_new');

/** MySQL database password */
define('DB_PASSWORD', '@hmAZ)K[!u*8');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         ',Wdkt,{UNt A%=//j#W2bGl1;:f$DnP+m{<9$5I)Zq!/yCX+l-+b9T$o5MckPH%D');
define('SECURE_AUTH_KEY',  'Jth0 qE$]@?dlnat6>V.Ic+98Al3H[tIQmLvgzRNNyX/?#/lOA^T;1,V!;YX3#ZA');
define('LOGGED_IN_KEY',    '8D.ytC?u r+b[3p@u~kB!YcHx2-Gf`g`-`r?o58{*X6oFqc)--ehkH:XdY$(#*4v');
define('NONCE_KEY',        'iB*C/9|2:])_~mt-D}RHxi U|jQbY/TyC;Z9f=4_L)9`uhtYooawhb|.t0Ab+XGb');
define('AUTH_SALT',        'dh&hNq^7qi)2y`-Z6JD+>7cR~e=2zJKn=<@R*:whQ@_${!(,^Bja>Z])^=]%50in');
define('SECURE_AUTH_SALT', 'eMCgJDfqhXo><nQen))#Gm9bIf}W@nl%}2{o@^vhTg(<R <qXj7FFxc5L&BL061N');
define('LOGGED_IN_SALT',   'cY6(aSfdZ#F0=#4~1!NCbjZ/w$@C&HUjsqT[&:T]5NO}n/B6f=MAP<BPg7?>&ViZ');
define('NONCE_SALT',       '7~o<]@pQ[Q%aZtR6!6?4ZWPvkNlOf>:A1a>fXHl:130pU$4d5[C U37={)0+06$1');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

<?php

/* Path to the WordPress codebase you'd like to test. Add a forward slash in the end. */
define( 'ABSPATH', dirname( __FILE__, 2 ) . '/wordpress/' );

/*
 * Path to the theme to test with.
 *
 * The 'default' theme is symlinked from test/phpunit/data/themedir1/default into
 * the themes directory of the WordPress installation defined above.
 */
define( 'WP_DEFAULT_THEME', 'default' );

// Test with multisite enabled.
// Alternatively, use the tests/phpunit/multisite.xml configuration file.
// define( 'WP_TESTS_MULTISITE', true );

// Force known bugs to be run.
// Tests with an associated Trac ticket that is still open are normally skipped.
// define( 'WP_TESTS_FORCE_KNOWN_BUGS', true );

// Test with WordPress debug mode (default).
define( 'WP_DEBUG', true );

// ** MySQL settings ** //

// This configuration file will be used by the copy of WordPress being tested.
// wordpress/wp-config.php will be ignored.

// WARNING WARNING WARNING!
// These tests will DROP ALL TABLES in the database with the prefix named below.
// DO NOT use a production database or one that is shared with something else.

define( 'DB_NAME'       , getenv( 'WP_DB_NAME' ) ?: 'wp_phpunit_tests' );
define( 'DB_USER'       , getenv( 'WP_DB_USER' ) ?: 'wordpress' );
define( 'DB_PASSWORD'   , getenv( 'WP_DB_PASS' ) ?: 'root' );
define( 'DB_HOST'       , 'localhost' );
define( 'DB_CHARSET'    , 'utf8mb4' );
define( 'DB_COLLATE'    , '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 */
define( 'AUTH_KEY',         '<iLvt|+{d Wwn#dHl4}kME@|hz9g):<@P[-r/79shoi;|F2E8gR/L_FWoEmI~Z}?' );
define( 'SECURE_AUTH_KEY',  '`Li)y6g$[CbDqQ^F{8=`meq$5/.[(>y;G_j.yu;j/Mh^#4ZHj/K%~dKnze+`v_$v' );
define( 'LOGGED_IN_KEY',    'GmV<drb ^oP<oWavd7f^FQq%neBCw7R*03ff3&}t=Pc07L[3(Ewo.; J/gCbcq),' );
define( 'NONCE_KEY',        '^LB{Ct@J!c4k+lY1(|*^yWwmYIW#(^]q(|(vu$&2JXP>I~ixM6+?R&K<Ulh!Bs$<' );
define( 'AUTH_SALT',        '`^r.Je[4BtbpLuvF8^E+T+u_s3qi h2wfr:B:~[WoEoi*vl;-R~U3uG4EY{#uFx1' );
define( 'SECURE_AUTH_SALT', 'X9JGTTAO-}!J]JL?~Nt}E41OAuw+a{-{1p1pgv`cui2&Yxr!v:oYd7Ye?E_A2T.0' );
define( 'LOGGED_IN_SALT',   'w$O~Ay%o<gY4>j6h/Sx4K50RWJb;nmV/P)TvAGR[c1golF]tgF3n,be60lcuHNc+' );
define( 'NONCE_SALT',       'F)a>Yn<WDM_?css=MEA:yNOKOTk5$;p2y,2:)b;!$eM,q2;^<|S?hhj?hi:d;)~w' );

$table_prefix = 'wp_';   // Only numbers, letters, and underscores please!

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );

define( 'WP_PHP_BINARY', 'php' );

define( 'WPLANG', '' );

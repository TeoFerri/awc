<?php
// DATABASE SETTINGS
define("DB_HOST", "localhost");
define("DB_DB", "awc");
define("DB_CHAR", "utf8");
define("DB_USER", "root");
define("DB_PASS", "");

/* [MUTE NOTIFICATIONS] */
error_reporting(E_ALL & ~E_NOTICE);

// FILE PATH
// Manually define the absolute path if you get path problems
define('PATH_LIB', __DIR__ . DIRECTORY_SEPARATOR);

session_start();
if(!isset($_SESSION['cart']) && empty($_SESSION['cart'])) {
   $_SESSION['cart']=0;
}
?>

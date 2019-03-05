<?php
/**
 * MySQL server connection information
 * 
 * This file has configuration information to establish connection to the MySQL server
 *	- hostName = mysql server to connect
 *  - userName = database username to login
 *  - passWord = database password to login
 *  - dataBase = database name
 */
if ($_SERVER['HTTP_HOST'] == 'localhost') { // Local
	define('HOST_NAME','localhost');
	define('USER_NAME','root');
	define('PASSWORD','');
	define('DATABASE_NAME','mbtm');
}
else {  // Main 
	define('HOST_NAME','');
	define('USER_NAME','');
	define('PASSWORD','');
	define('DATABASE_NAME','');
}
$dbConfig['hostName'] = HOST_NAME;
$dbConfig['userName'] = USER_NAME;
$dbConfig['passWord'] = PASSWORD;
$dbConfig['dataBase'] = DATABASE_NAME;

?>

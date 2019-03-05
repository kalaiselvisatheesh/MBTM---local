<?php
/**
 * configuration variables
 *
 * This file has constants and global variable used throughout the application.
 *
 */
 //$_SERVER['HTTP_X_FORWARDED_FOR'] == 103.238.229.114
define("TITLE","MBTM");
if (isset($_SERVER['HTTPS']) && ($_SERVER["HTTPS"] == 'on' ) )
	$site = 'https://';
elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
	$site = 'https://';
else
	$site = 'http://';
//if($_SERVER['SERVER_ADDR']=='localhost')
if($_SERVER['HTTP_HOST']=='localhost')
{
	define('BASE_URL',$site.$_SERVER['HTTP_HOST']);
	define('SITE_PATH',  $site.$_SERVER['HTTP_HOST'].'/MBTM');
	define('ABS_PATH',  'C:/wamp64/www/MBTM');
	define('SERVER',  0);
	define('SITE_PATH_UPLOAD',SITE_PATH.'/webresources/uploads/');
	define('ABS_PATH_UPLOAD',ABS_PATH.'/webresources/uploads/');
	//define('IMAGE_PATH',SITE_PATH.'/webresources/uploads/');
}
else
{
	define('BASE_URL',$site.$_SERVER['HTTP_HOST']);
	define('SITE_PATH',  $site.$_SERVER['HTTP_HOST']);
	define('ABS_PATH',  '/var/www/html');
	define('SERVER',  1);
	define('SITE_PATH_UPLOAD',SITE_PATH.'/webresources/uploads/');
	define('ABS_PATH_UPLOAD',ABS_PATH.'/webresources/uploads/');
	//define('IMAGE_PATH','');
}
define('SITE_TITLE', 'MBTM');
//define('ADMIN_SCRIPT_PATH', SITE_PATH.'/webresources/js/');
define('SCRIPT_PATH', SITE_PATH.'/webresources/js/');
define('STYLE_PATH', SITE_PATH.'/webresources/css/');
define('IMAGE_PATH', SITE_PATH.'/webresources/images/');
define('MAGAZINE_IMAGE_PATH', SITE_PATH.'/webresources/uploads/magazine/');
define('TEMP_IMAGE_PATH', SITE_PATH.'/webresources/uploads/temp/');	
define('TEMP_IMAGE_PATH_REL', ABS_PATH.'/webresources/uploads/temp/');
define('LIMIT',100);
define('PERPAGE',25);

define('ADMIN_PER_PAGE_LIMIT', 10);

global $admin_per_page_array;
$admin_per_page_array = array(10,50,100,200,250);
define('ADMIN_PER_PAGE_ARRAY', 'return ' . var_export($admin_per_page_array, 1) . ';');//define constant array

?>

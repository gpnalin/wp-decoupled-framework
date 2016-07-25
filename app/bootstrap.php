<?php

// Make sure this is PHP 5.3 or later
// -----------------------------------------------------------------------------

if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300)
{
	exit('Framework requires PHP 5.3.0 or later, but you&rsquo;re running '.PHP_VERSION.'. Please talk to your host/IT department about upgrading PHP or your server.');
}

if (!defined('E25_API_HOST'))
{
	exit('Framework requires define E25_API_HOST defined. Please specify the API Host!');
}


// Path constants and validation
// -----------------------------------------------------------------------------

// We're already in the app/ folder, so let's use that as the starting point. Make sure it doesn't look like we're on a
// network share that starts with \\
$appPath = realpath(dirname(__FILE__));

if (isset($appPath[0]) && isset($appPath[1]))
{
	if ($appPath[0] !== '\\' && $appPath[1] !== '\\')
	{
		$appPath = str_replace('\\', '/', $appPath);
	}
}

defined('E25_APP_PATH') || define('E25_APP_PATH', $appPath.'/');

defined('E25_API_ASSETS_URL') || define('E25_API_ASSETS_URL', E25_API_HOST.'wp-content/uploads/');

defined('E25_APP_HOST') || define('E25_APP_HOST', 'http://'.$_SERVER["HTTP_HOST"]. '/');
defined('E25_APP_ASSETS_URL') || define('E25_APP_ASSETS_URL', E25_APP_HOST.'media/');


// The app/ folder goes inside craft/ by default, so work backwards from app/
defined('E25_BASE_PATH') || define('E25_BASE_PATH', realpath(E25_APP_PATH.'..').'/');

// Everything else should be relative from by default
defined('E25_CONFIG_PATH')       || define('E25_CONFIG_PATH',       E25_BASE_PATH.'config/');
defined('E25_ASSETS_PATH')       || define('E25_ASSETS_PATH',      	E25_BASE_PATH.'assets/');
defined('E25_CACHE_PATH')        || define('E25_CACHE_PATH',      	E25_BASE_PATH.'.cache/');
defined('E25_TEMPLATES_PATH')    || define('E25_TEMPLATES_PATH',    E25_BASE_PATH.'templates/');

defined('DEBUG_MODE')    || define('DEBUG_MODE',  false );

# Get the Composer autoloader
require_once E25_BASE_PATH . '/vendor/autoload.php';

require_once E25_APP_PATH . 'includes/stash.php';
require_once E25_APP_PATH . 'includes/twig.php';
require_once E25_APP_PATH . 'includes/guzzle.php';

require_once E25_APP_PATH . 'routers.php';

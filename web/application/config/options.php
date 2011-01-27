<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| OPTIONS API
| -------------------------------------------------------------------
| Configure options API here.
*/

/*
| -------------------------------------------------------------------
| Autoloading - enabled or disabled?
| -------------------------------------------------------------------
*/
if (!defined('OPTIONS_ENABLE_AUTOLOADING')) define('OPTIONS_ENABLE_AUTOLOADING', true);
if (!defined('OPTIONS_DEFAULT_DBGROUP')) define('OPTIONS_DEFAULT_DBGROUP', 'default');

$config['options'] = array();
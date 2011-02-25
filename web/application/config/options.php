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
// enable option autoloading - speeds access to commonly needed options, but use only if acceptable in all cases
@define('OPTIONS_ENABLE_AUTOLOADING', true);
// override default db_group (ignore environment); set to null for standard behavior
@define('OPTIONS_DEFAULT_DBGROUP', null);

$config['options'] = array();
<?php 
/**
 * Idea - a prototyping Framework for PHP developers
 * Copyright (C) 2011  Fat Panda, LLC
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
 
/**
 * Command line interface for executing controllers outside of a Web context, and for launching test cases.
 * @author Aaron Collegeman
 */

error_reporting(E_ALL);

// don't timeout
set_time_limit(0);
 
// tell the stack we're on the command line
define('ON_CLI', true);
 
$opts = getopt(
  implode('', array(
    'h',
    'd::',
    'x::',
    't::',
    'T'
  ))
);

#
# Print the help screen?
# 
if (isset($opts['h']) || ( count($argv) == 1 )):

echo <<<EOF
Idea - a prototyping Framework for PHP developers
Copyright (C) 2011  Fat Panda, LLC
http://github.com/collegeman/idea

Usage: php cli.php [-x:controller[/action] | -t:test_case | -T] [-d:db_group] 
  or   php cli.php controller[/action]
  
Options
 -x:<controller>[/<action>]       Run controller and action (or index in controller)
 -t:<test_case>                   Run a unit test case
 -T                               Run all unit tests
 -d:<db_group>                    Load the named database group instead of default


EOF;
exit;

#
# Execute a controller/action?
#
elseif (!empty($opts['x']) || ( !$opts && count($argv) == 2 )):

  $cli_uri = !empty($opts['x']) ? trim($opts['x'], ' :') : $argv[1];

/*
|---------------------------------------------------------------
| SYSTEM FOLDER NAME
|---------------------------------------------------------------
|
| This variable must contain the name of your "system" folder.
| Include the path if the folder is not in the same  directory
| as this file.
|
| NO TRAILING SLASH!
|
*/
	$system_folder = "web/system";

/*
|---------------------------------------------------------------
| APPLICATION FOLDER NAME
|---------------------------------------------------------------
|
| If you want this front controller to use a different "application"
| folder then the default one you can set its name here. The folder 
| can also be renamed or relocated anywhere on your server.
| For more info please see the user guide:
| http://codeigniter.com/user_guide/general/managing_apps.html
|
|
| NO TRAILING SLASH!
|
*/
	$application_folder = "web/application";

/*
|===============================================================
| END OF USER CONFIGURABLE SETTINGS
|===============================================================
*/


/*
|---------------------------------------------------------------
| SET THE SERVER PATH
|---------------------------------------------------------------
|
| Let's attempt to determine the full-server path to the "system"
| folder in order to reduce the possibility of path problems.
| Note: We only attempt this if the user hasn't specified a 
| full server path.
|
*/
if (strpos($system_folder, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
	{
		$system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
	}
}
else
{
	// Swap directory separators to Unix style for consistency
	$system_folder = str_replace("\\", "/", $system_folder); 
}

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| SELF		- The name of THIS file (typically "index.php")
| FCPATH	- The full server path to THIS file
| BASEPATH	- The full server path to the "system" folder
| APPPATH	- The full server path to the "application" folder
|
*/
define('EXT', '.php');
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', str_replace(SELF, '', __FILE__));
define('BASEPATH', $system_folder.'/');

if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ($application_folder == '')
	{
		$application_folder = 'application';
	}

	define('APPPATH', BASEPATH.$application_folder.'/');
}

/*
|---------------------------------------------------------------
| LOAD THE FRONT CONTROLLER
|---------------------------------------------------------------
|
| And away we go...
|
*/
require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;

/* End of file index.php */
/* Location: ./index.php */

endif;
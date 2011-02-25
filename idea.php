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
    'T',
    'i',
    'e::',
    'f::'
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

Usage: php cli.php [-x:controller[/action] | -f:</facebook/api> | -t:test_case | -T | -i] [options] 
  or   php cli.php controller[/action]
  
Functions:
 -x:<controller>[/<action>]         Run controller and action (or index in controller)
 -f:</facebook/api>                 Invoke a specific endpoint in the Facebook Graph API
 -t:<test_case>                     Run a unit test case
 -T                                 Run all unit tests
 -i                                 Display the output of phpinfo()
 
Options:
 -d:<db_group>                      Load the named database group instead of default
 -e:<environment>                   Set \$_ENV['CI_ENV'] equal to <environment>
 -p:param1=value1&param2=value2...  Parse and load the query string into \$_REQUEST and \$_POST  
 -g:param1=value1&param2=value2...  Parse and load the query string into \$_REQUEST and \$_GET

EOF;
exit; 

endif;

#
# httpdocs/index.php here:
#

/*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" folder.
 * Include the path if the folder is not in the same  directory
 * as this file.
 *
 */
	$system_path = "web/system";

/*
 *---------------------------------------------------------------
 * APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * folder then the default one you can set its name here. The folder
 * can also be renamed or relocated anywhere on your server.  If
 * you do, use a full server path. For more info please see the user guide:
 * http://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 *
 */
	$application_folder = "web/application";

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here.  For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT:  If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller.  Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 *
 */
	// The directory name, relative to the "controllers" folder.  Leave blank
	// if your controller is not in a sub-folder within the "controllers" folder
	// $routing['directory'] = '';

	// The controller class file name.  Example:  Mycontroller.php
	// $routing['controller'] = '';

	// The controller function you wish to be called.
	// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 *
 */
	// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------




/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */
	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}

	// ensure there's a trailing slash
	$system_path = rtrim($system_path, '/').'/';

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	define('EXT', '.php');

	// Path to the system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// Path to the front controller (this file)
	define('FCPATH', str_replace(SELF, '', __FILE__));

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


	// The path to the "application" folder
	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}



#
# Set environment
#
if ($env = @$opts['e']) {
  $_ENV['CI_ENV'] = trim($env, ' :');
}

#
# Set $_POST
#
if ($query = @$opts['p']) {
  $_POST = array_merge($_POST, parse_str($query));
  $_REQUEST = array_merge($_REQUEST, $_POST);
}

#
# Set $_GET
#
if ($query = @$opts['g']) {
  $_GET = array_merge($_GET, parse_str($query));
  $_REQUEST = array_merge($_REQUEST, $_GET);
}

#
# Print phpinfo()?
#
if (isset($opts['i'])):

  phpinfo();
  exit(0);

#
# Run a test case or execute a Facebook request?
#
elseif (!empty($opts['f']) || !empty($opts['t']) || isset($opts['T'])):

  #
  # system/core/CodeIgniter.php here:
  #

  /*
   * ------------------------------------------------------
   *  Define the CodeIgniter Version
   * ------------------------------------------------------
   */
  	define('CI_VERSION', '2.0');

  /*
   * ------------------------------------------------------
   *  Load the global functions
   * ------------------------------------------------------
   */
  	require(BASEPATH.'core/Common'.EXT);

  /*
   * ------------------------------------------------------
   *  Load the framework constants
   * ------------------------------------------------------
   */
  	require(APPPATH.'config/constants'.EXT);

  /*
   * ------------------------------------------------------
   *  Define a custom error handler so we can log PHP errors
   * ------------------------------------------------------
   */
  	set_error_handler('_exception_handler');

  	if ( ! is_php('5.3'))
  	{
  		@set_magic_quotes_runtime(0); // Kill magic quotes
  	}

  /*
   * ------------------------------------------------------
   *  Set the subclass_prefix
   * ------------------------------------------------------
   *
   * Normally the "subclass_prefix" is set in the config file.
   * The subclass prefix allows CI to know if a core class is
   * being extended via a library in the local application
   * "libraries" folder. Since CI allows config items to be
   * overriden via data set in the main index. php file,
   * before proceeding we need to know if a subclass_prefix
   * override exists.  If so, we will set this value now,
   * before any classes are loaded
   * Note: Since the config file data is cached it doesn't
   * hurt to load it here.
   */
  	if (isset($assign_to_config['subclass_prefix']) AND $assign_to_config['subclass_prefix'] != '')
  	{
  		get_config(array('subclass_prefix' => $assign_to_config['subclass_prefix']));
  	}

  /*
   * ------------------------------------------------------
   *  Set a liberal script execution time limit
   * ------------------------------------------------------
   */
  	if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
  	{
  		@set_time_limit(300);
  	}

  /*
   * ------------------------------------------------------
   *  Start the timer... tick tock tick tock...
   * ------------------------------------------------------
   */
  	$BM =& load_class('Benchmark', 'core');
  	$BM->mark('total_execution_time_start');
  	$BM->mark('loading_time:_base_classes_start');

  /*
   * ------------------------------------------------------
   *  Instantiate the hooks class
   * ------------------------------------------------------
   */
  	$EXT =& load_class('Hooks', 'core');

  /*
   * ------------------------------------------------------
   *  Is there a "pre_system" hook?
   * ------------------------------------------------------
   */
  	$EXT->_call_hook('pre_system');

  /*
   * ------------------------------------------------------
   *  Instantiate the config class
   * ------------------------------------------------------
   */
  	$CFG =& load_class('Config', 'core');

  	// Do we have any manually set config items in the index.php file?
  	if (isset($assign_to_config))
  	{
  		$CFG->_assign_to_config($assign_to_config);
  	}

  /*
   * ------------------------------------------------------
   *  Instantiate the UTF-8 class
   * ------------------------------------------------------
   *
   * Note: Order here is rather important as the UTF-8
   * class needs to be used very early on, but it cannot
   * properly determine if UTf-8 can be supported until
   * after the Config class is instantiated.
   *
   */

  	$UNI =& load_class('Utf8', 'core');

  /*
   * ------------------------------------------------------
   *  Instantiate the URI class
   * ------------------------------------------------------
   */
  	$URI =& load_class('URI', 'core');

  /*
   * ------------------------------------------------------
   *  Instantiate the routing class and set the routing
   * ------------------------------------------------------
   */
  	$RTR =& load_class('Router', 'core');
  	//$RTR->_set_routing();
    
  	// Set any routing overrides that may exist in the main index file
  	if (isset($routing))
  	{
  		$RTR->_set_overrides($routing);
  	}

  /*
   * ------------------------------------------------------
   *  Instantiate the output class
   * ------------------------------------------------------
   */
  	$OUT =& load_class('Output', 'core');

  /*
   * ------------------------------------------------------
   *	Is there a valid cache file?  If so, we're done...
   * ------------------------------------------------------
   */
  	if ($EXT->_call_hook('cache_override') === FALSE)
  	{
  		if ($OUT->_display_cache($CFG, $URI) == TRUE)
  		{
  			exit;
  		}
  	}
  	

  /*
   * ------------------------------------------------------
   *  Load the Input class and sanitize globals
   * ------------------------------------------------------
   */
  	$IN	=& load_class('Input', 'core');

  /*
   * ------------------------------------------------------
   *  Load the Language class
   * ------------------------------------------------------
   */
  	$LANG =& load_class('Lang', 'core');

  /*
   * ------------------------------------------------------
   *  Load the app controller and local controller
   * ------------------------------------------------------
   *
   */
  	// Load the base controller class
  	require BASEPATH.'core/Controller'.EXT;

  	function &get_instance()
  	{
  		return CI_Controller::get_instance();
  	}


  	if (file_exists(APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller'.EXT))
  	{
  		require APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller'.EXT;
  	}
  	

  $CI = new CI_Controller();
  
  if (!empty($opts['f'])) {
  
    if (!($session = get_option('_core_facebook_session'))) {
      echo "Core Facebook session not configured. Browse to options/facebook and login.\n";
      exit(1);
    }
    
    $path = trim($opts['f'], ' :');
    
    $CI->load->library('facebook');
    
    $CI->facebook->setSession($session, false);
    
    print_r($CI->facebook->api($path));
    
  } else {
  
    require_once(APPPATH.'/simpletest/autorun.php');
  
    class AllTests extends TestSuite {
      function AllTests() {
        global $opts;
      
        // run an individual test:
        if ($test = trim(@$opts['t'], ' :')) {
          if (file_exists(($file = APPPATH.'tests/'.$test.'.php'))) {
            $this->addFile($file);
          } else if (file_exists(($file = APPPATH.'tests/'.$test.'_test.php'))) {
            $this->addFile($file);
          } else if (file_exists(($file = APPPATH.'tests/'.$test.'_tests.php'))) {
            $this->addFile($file);
          } else if (file_exists(($file = APPPATH.'tests/'.$test.'Test.php'))) {
            $this->addFile($file);
          } else if (file_exists(($file = APPPATH.'tests/'.$test.'Tests.php'))) {
            $this->addFile($file);
          } else {
            echo "Test case file for '$test' does not exist.\n";
            exit(1);
          }
        
        // run all tests:
        } else {
          $dir = opendir(APPPATH.'/tests/');
          while(($file = readdir($dir)) !== false) {
            if (substr(strrev($file), 0, 3) == 'php') {
              $this->addFile(sprintf('%s/%s', APPPATH.'tests', $file));
            }
          }
        }
      
      }
    }
  
  }
  
  exit(0);
  
  
#
# Execute a controller[/action]?
#
elseif (!empty($opts['x']) || ( !$opts && count($argv) == 2 )):

  $cli_uri = !empty($opts['x']) ? trim($opts['x'], ' :') : $argv[1];

  /*
  |---------------------------------------------------------------
  | LOAD THE FRONT CONTROLLER
  |---------------------------------------------------------------
  |
  | And away we go...
  |
  */
  require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;

endif;
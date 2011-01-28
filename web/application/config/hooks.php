<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_controller'][] = array(
  'function' => '_do_detect_environment',
  'filename' => 'environment.php',
  'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
  'function' => '_do_options_autoload',
  'filename' => 'options_autoloader.php',
  'filepath' => 'hooks'
);

/* End of file hooks.php */
/* Location: ./system/application/config/hooks.php */
<?php 
class MY_Config extends CI_Config {
  
  function __construct()
	{
		$this->config =& get_config();
		log_message('debug', "Config Class Initialized");
	}
  	
	// --------------------------------------------------------------------

	/**
	 * Load Config File
	 *
	 * @access	public
	 * @param	string	the config file name
	 * @return	boolean	if the file was loaded correctly
	 */	
	function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
	  if (isset($_ENV['CI_ENV']) && ($env = $_ENV['CI_ENV'])) {
	    
	    $env_file = $file . '-' . $env;
	  
	    if (!parent::load($env_file, $use_sections, true)) {
	      log_message('debug', "No special config file for [$file] exists for environment [$env]");
	      return parent::load($file, $use_sections, $fail_gracefully);
	    }
	    
	  } else {
	    
	    return parent::load($file, $use_sections, $fail_gracefully);
	    
	  }
	}
  
}
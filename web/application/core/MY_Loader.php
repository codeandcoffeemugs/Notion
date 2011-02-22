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
 * Slightly different paradigm with respect to loading libraries. 
 * @author Aaron Collegeman 
 * @see My_Loader->library
 */
class MY_Loader extends CI_Loader {
  
  /**
	 * If $_ENV['CI_ENV'] is set and there exists a database group in configuration
	 * by the same name, and $params is empty, then the value $_ENV['CI_ENV'] is
	 * used as the active DB group
	 *
	 * @access	public
	 * @param	string	the DB credentials
	 * @param	bool	whether to return the DB object
	 * @param	bool	whether to enable active record (this allows us to override the config setting)
	 * @return	object
	 */	
	function database($params = '', $return = FALSE, $active_record = TRUE)
	{
	  if ($params == '' && !empty($_ENV['CI_ENV'])) {
	    include(APPPATH.'config/database'.EXT);
      if (isset($db) && isset($db[$_ENV['CI_ENV']])) {
        $params = $_ENV['CI_ENV'];
      }
    }
    
    return parent::database($params, $return, $active_record);
	}
  
  /**
	 * Class Loader
	 *
	 * This function lets users load and instantiate classes.
	 * It is designed to be called from a user's app controllers.
	 *
	 * @access	public
	 * @param	string	the name of the class
	 * @param	mixed	the optional parameters
	 * @param	string	an optional object name
	 * @return	void
	 */
	function library($library = '', $params = NULL, $object_name = NULL)
	{
		if (is_array($library))
		{
			foreach($library as $read)
			{
				$this->library($read);
			}

			return;
		}

		if ($library == '' OR isset($this->_base_classes[$library]))
		{
			return FALSE;
		}

		if (is_null($params)) {
		  $params = $this->_get_config($library);
		}
    
		if (is_array($library))
		{
			foreach ($library as $class)
			{
				$this->_ci_load_class($class, $params, $object_name);
			}
		}
		else
		{
			$this->_ci_load_class($library, $params, $object_name);
		}
	}
	
	function _get_config($library) {

	  if (isset($_ENV['CI_ENV']) && ($env = $_ENV['CI_ENV'])) {
	    $file = strtolower($library . '-' . $env);
	    
	    if (file_exists(APPPATH.'config/'.$file.EXT))
  		{
  			include(APPPATH.'config/'.$file.EXT);
  		}			
  		else {
  		  $file = ucfirst(strtolower($library));
  		  
  		  if (file_exists(APPPATH.'config/'.$file.EXT))
  		  {
    			include(APPPATH.'config/'.$file.EXT);
    		}
    	}
	  } 
	  
	  if (!isset($config)) {
  	  if (file_exists(APPPATH.'config/'.strtolower($library).EXT))
  		{
  			include(APPPATH.'config/'.strtolower($library).EXT);
  		}			
  		elseif (file_exists(APPPATH.'config/'.ucfirst(strtolower($library)).EXT))
  		{
  			include(APPPATH.'config/'.ucfirst(strtolower($library)).EXT);
  		}
  	}
		
		return isset($config) ? $config : null;
	}
  
}
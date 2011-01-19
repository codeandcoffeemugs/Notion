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
 * All Controller class names are suffixed "Controller", all controller
 * filenames are suffixed "controller.php".
 * @author Aaron Collegeman aaron@collegeman.net
 */
class MY_Router extends CI_Router {
  
  function MY_Router() {
    parent::CI_Router();
  }
  
  function set_class($value) {
    parent::set_class($value.'Controller');
  }
  
  function _validate_request($segments)
	{
		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$segments[0].'controller'.EXT))
		{
			return $segments;
		}

		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{		
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);
			
			if (count($segments) > 0)
			{
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].'controller'.EXT))
				{
					show_404($this->fetch_directory().$segments[0]);
				}
			}
			else
			{
				$this->set_class($this->default_controller);
				$this->set_method('index');
			
				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
				{
					$this->directory = '';
					return array();
				}
			
			}

			return $segments;
		}

		// Can't find the requested controller...
		show_404($segments[0]);
	}
	 
}
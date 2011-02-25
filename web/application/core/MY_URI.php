<?php

class MY_URI extends CI_URI {
  
  /**
	 * Get the URI String
	 *
	 * @access	private
	 * @return	string
	 */
	function _fetch_uri_string()
	{
	  global $cli_uri;
	  
	  if ($cli_uri) {
	    $this->uri_string = $cli_uri;
	    return;
	  } else {
	    return parent::_fetch_uri_string();
	  }
	}
  
}
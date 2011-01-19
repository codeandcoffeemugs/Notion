<?php

class Default extends Controller {

	function Default() {
		parent::Controller();	
	}
	
	function index() {
		$this->load->view('default/index');
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
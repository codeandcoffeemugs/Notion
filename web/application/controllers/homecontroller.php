<?php
class HomeController extends Controller {

	function HomeController() {
		parent::Controller();	
	}
	
	function index() {
		$this->load->view('home/index');
	}
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
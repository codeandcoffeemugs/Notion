<?php
class HomeController extends Controller {

	function HomeController() {
		parent::Controller();	
	}
	
	function index() {
	  $this->config->load('facebook');
	  $data['appId'] = $this->config->item('appId');
		$data['secret'] = $this->config->item('secret');
		$data['apiKey'] = $this->config->item('apiKey');
		
		$this->load->helper('facebook');
		
		// prepare and create the data for the view
	
		
		//$data['fb'] = new facebook();
		$this->load->view('home/index',$data);
		
	}
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
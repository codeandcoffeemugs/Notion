<?php
class HomeController extends Controller {

	function HomeController() {
		parent::Controller();	
	}
	
	function index() {
	  /*
	  $this->config->load('facebook');
	  $data['appId'] = $this->config->item('appId');
		$data['secret'] = $this->config->item('secret');
		$data['apiKey'] = $this->config->item('apiKey');
		
		$this->load->helper('facebook');
		
		// prepare and create the data for the view
	
		
		//$data['fb'] = new facebook();
		$this->load->view('home/index',$data);
		*/
		$this->load->library('facebook',$this->config->item('facebook'));
    Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
    $this->load->view('home/index');
	}
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
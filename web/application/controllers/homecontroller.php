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
    $appinfo = $this->config->item("facebook");
    $data['appId'] = $appinfo['appId'];
    $data['base'] = $this->config->item('base_url');
    $this->load->view('home/index',$data);
	}
	
	// function logout() {
	//     //setcookie('','',time() - 3600);
	//     // $appinfo = $this->config->item("facebook");
	//     //    $appId = $appinfo['appId'];
	//     echo "<pre>";
	//     print_r($_REQUEST);
	//     echo "</pre>";
	//     $chunks = $_GET;
	//     $chuncks = explode('/', $chunks);
	//     echo $chunks[2];
	//     $cookie = "fbs_" .$chunks[2];
	//     setcookie($cookie,'',time() - 3600);
	  //header('Location: http://slimui.localhost');
	//}
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
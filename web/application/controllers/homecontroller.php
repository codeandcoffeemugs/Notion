<?php
class HomeController extends Controller {

	function HomeController() {
		parent::Controller();	
	}
	
	function index() {
		
		$this->load->library('facebook',$this->config->item('facebook'));
    Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
    $appinfo = $this->config->item("facebook");
    $data['appId'] = $appinfo['appId'];
    $data['base'] = $this->config->item('base_url');
    $session = $this->facebook->getSession();
    $data['login'] = NULL;
    $data['logout'] = NULL;
      if(!$session) {
        $login = $this->facebook->getLoginUrl(array('req_perms' => 'email,friends_photos'));
        $data['login'] = "anchor($login,'Login')";
     } else {
       $logout = $this->facebook->getLogoutUrl();
       $data['me'] = $this->facebook->api('/me');
       $data['accessToken'] = $session['access_token'];
       $data['uid'] = $this->facebook->getUser();
       $data['logout'] = "<a href='" .$logout. "'>Logout</a>"; 
       }
    
    $this->load->view('home/index',$data);
	}
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */
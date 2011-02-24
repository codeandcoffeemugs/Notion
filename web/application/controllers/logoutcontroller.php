<?php 
class LogoutController extends Controller {
  function LogoutController() {
		parent::Controller();	
	}
	function logout() {
	  $this->load->view('logout');
	  
	}
}
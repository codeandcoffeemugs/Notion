<?php
  class JsworkController extends CI_Controller {
    function index() {
      $this->load->library('facebook');
      Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
      $data['appId'] = $this->facebook->getAppId();
      $data['base'] = $this->config->item('base_url');
      
      $this->load->view('home/jswork', $data);
    }
  }
?>
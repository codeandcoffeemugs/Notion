<?php

  class FacebookController extends Controller {
    function index() {
      $this->load->library('facebook',$this->config->item('facebook'));
      $this->facebook->getSession();
      redirect('/');
    }
  }
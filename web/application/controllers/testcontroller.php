<?php 
class TestController extends Controller {
  
  function index() {
    echo get_option('foo');
  
  }
  
}
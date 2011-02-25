<?php
class MY_Log extends CI_Log {
  
  function setThreshold($threshold) {
    $this->_threshold = $threshold;
  }
  
}
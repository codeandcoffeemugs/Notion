<?php /**
* 
*/
class CacheController extends CI_Controller
{
  function index() {
    echo cache('test','foobar','5 seconds');
  }
  
  function read() {
    echo cache_get('test', 'default');
  }
  
  function doSomething() {
    if (!($value = cache_get('my-unique-key'))) {
      $value = /* compose it */ true;
      cache('my-unique-key', $value, '1 hour');
    }
    
    // use $value to do something
  }
}

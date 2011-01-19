<?php 
/**
 * Test the options helper.
 */
class OptionsHelperTests extends UnitTestCase {
  
  function setUp() {
    global $CI;
    $CI->load->helpers(array('stash', 'options'));
    trunc_options();
  }
  
  function testAddOptionThatDoesntExist() {
    $this->assertNull(get_option('foo', null));
    $this->assertTrue(add_option('foo', true));
    $this->assertTrue(get_option('foo'));
  }
  
  function testAddOptionThatDoesExist() {
    $this->assertNull(get_option('foo', null));
    $this->assertTrue(add_option('foo', 'bar'));
    $this->assertFalse(add_option('foo', 'doo'));
    $this->assertEqual('bar', get_option('foo'));
  }
  
  function testUpdateOptionThatDoesntExist() {
    $this->assertNull(get_option('foo', null));
    update_option('foo', 'bar');
    $this->assertEqual('bar', get_option('foo'));
  }

  function testUpdateOptionThatDoesExist() {
    $this->assertNull(get_option('foo', null));
    update_option('foo', 'bar');
    update_option('foo', 'doo');
    $this->assertEqual('doo', get_option('foo'));
  }
  
  function testDeleteOption() {
    $this->assertNull(get_option('foo', null));
    $this->assertTrue(add_option('foo', 'bar'));
    delete_option('foo');
    $this->assertNull(get_option('foo'));
  }
  
  function testComplexTypes() {
    add_option('boolean_true', true);
    $this->assertTrue(get_option('boolean_true'));
    
    add_option('boolean_false', false);
    $this->assertFalse(get_option('boolean_false'));
   
    add_option('array', array(true, false, 1, 'one', null));
    $this->assertEqual(array(true, false, 1, 'one', null), get_option('array'));
    
    add_option('object', (object) array('name' => 'Aaron Collegeman', 'favorite_color' => 'blue'));
    $obj = new stdClass();
    $obj->name = 'Aaron Collegeman';
    $obj->favorite_color = 'blue';
    $this->assertEqual($obj, get_option('object'));
    
    add_option('numeric', 3.1415);
    $this->assertEqual(3.1415, get_option('numeric'));
  }
  
  // TODO: write tests for db_group parameters
}
<?php 
/**
 * Test the stash helper.
 */
class StashHelperTests extends UnitTestCase {
 
  function testStashHelper() {
    Stash::delete('tests');
    
    Stash::add('tests', 'fruit', 'apples');
    Stash::add('tests', 'fruit', 'bananas');
    Stash::add('tests', 'fruit', 'pears');
    
    $this->assertEqual(array('apples', 'bananas', 'pears'), Stash::get('tests', 'fruit'));
    $this->assertEqual('apples', Stash::get('tests', 'fruit', null, true));
    
    $this->assertEqual('apples', Stash::shift('tests', 'fruit', null, true));
    $this->assertEqual(array('bananas', 'pears'), Stash::get('tests', 'fruit'));

    $this->assertEqual('pears', Stash::pop('tests', 'fruit', null, true));
    $this->assertEqual(array('bananas'), Stash::get('tests', 'fruit'));
    
    Stash::delete('tests', 'fruit');
    
    $this->assertEqual('foo', Stash::get('tests', 'fruit', 'foo'));
    $this->assertEqual(array('foo', 'bar'), Stash::get('tests', 'fruit', array('foo', 'bar')));
  }
  
}
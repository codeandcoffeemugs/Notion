<?php 
/**
 * Test the cache helper.
 * TODO: test memcache
 */
class CacheHelperTests extends UnitTestCase {
  
  function setUp() {
    global $CI;
    $CI->load->helpers(array('stash', 'options', 'cache'));
    flush_cache();
  }
  
  function testDefaultConfig() {
    $this->assertEqual(array('prefix' => 'cache-'), get_cache_config('options'));
  }
  
  function testCacheAndExpiration() {
    $key = 'testCacheAndExpiration';
    $this->assertTrue(cache($key, 'foo', '1 second'));
    $this->assertEqual('foo', get_cache($key));
    usleep(1.01 * 1000000);
    $this->assertEqual('default', get_cache($key, 'default'));
  }  
  
  function testAddCache() {
    $key = 'testAddCache';
    $this->assertTrue(cache_add($key, 'foo'));
    $this->assertEqual('foo', get_cache($key));
    $this->assertFalse(cache_add($key, 'foo'));
  }
  
  function testReplaceCache() {
    $key = 'testReplaceCache';
    $this->assertFalse(cache_replace($key, 'foo'));
    $this->assertNull(get_cache($key));
    $this->assertTrue(cache($key, 'foo'));
    $this->assertEqual('foo', get_cache($key));
    $this->assertTrue(cache_replace($key, 'bar'));
    $this->assertEqual('bar', get_cache($key));
  }
  
  function testDeleteCache() {
    $key = 'testDeleteCache';
    $this->assertTrue(cache($key, 'foo'));
    $this->assertEqual('foo', get_cache($key));
    $this->assertTrue(cache_delete($key));
    $this->assertNull(get_cache($key));
    $this->assertFalse(cache_delete('non-existent-cache-key'));
  }
  
  function testIncrement() {
    $key = 'testIncrement';
    $this->assertEqual(1, cache_increment($key));
    $this->assertEqual(2, cache_increment($key));
    $this->assertEqual(3, cache_increment($key));
    $this->assertEqual(13, cache_increment($key, 10));
    $this->assertTrue(cache_delete($key));
    $this->assertEqual(42, cache_increment($key, 42));
  }
  
  function testDecrement() {
    $key = 'testDecrement';
    $this->assertTrue(cache($key, 10));
    $this->assertEqual(10, get_cache($key));
    $this->assertEqual(9, cache_decrement($key));
    $this->assertEqual(8, cache_decrement($key));
    $this->assertEqual(4, cache_decrement($key, 4));
    $this->assertEqual(0, cache_decrement($key, 10));
    $this->assertEqual(0, cache_decrement($key, 42));
  }

}
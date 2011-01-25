<?php 
/**
 * Test the cache helper.
 * TODO: test memcache
 */
class CacheHelperTests extends UnitTestCase {
  
  function setUp() {
    global $CI;
    $CI->load->helpers(array('stash', 'options', 'cache'));
    cache_flush();
    cache_flush('memcached');
  }
  
  function testDefaultConfig() {
    $this->assertEqual(array('prefix' => 'cache-'), cache_get_config('options'));
    
    $this->assertEqual(array(array(
      'host' => 'localhost',
      'port' => 11211,
      'persistent' => true,
      'weight' => 1,
      'timeout' => 1, 
      'retry_interval' => 15,
      'status' => true,
      'failure_callback' => null
    )), cache_get_config('memcached'));
  }
  
  function testCacheAndExpiration() {
    $key = 'testCacheAndExpiration';
    
    $this->assertTrue(cache($key, 'foo', '1 second'));
    $this->assertEqual('foo', cache_get($key));
    usleep(1.01 * 1000000);
    $this->assertEqual('default', cache_get($key, 'default'));
    
    $this->assertTrue(cache($key, 'foo', '1 second', 'memcached'));
    $this->assertEqual('foo', cache_get($key, null, 'memcached'));
    usleep(1.01 * 1000000);
    $this->assertEqual('default', cache_get($key, 'default', 'memcached'));
  }  
  
  function testAddCache() {
    $key = 'testAddCache';
   
    $this->assertTrue(cache_add($key, 'foo'));
    $this->assertEqual('foo', cache_get($key));
    $this->assertFalse(cache_add($key, 'foo'));
    
    $this->assertTrue(cache_add($key, 'foo', 0, 'memcached'));
    $this->assertEqual('foo', cache_get($key, null, 'memcached'));
    $this->assertFalse(cache_add($key, 'foo', 0, 'memcached'));
  }
  
  function testReplaceCache() {
    $key = 'testReplaceCache';
    
    $this->assertFalse(cache_replace($key, 'foo'));
    $this->assertNull(cache_get($key));
    $this->assertTrue(cache($key, 'foo'));
    $this->assertEqual('foo', cache_get($key));
    $this->assertTrue(cache_replace($key, 'bar'));
    $this->assertEqual('bar', cache_get($key));
    
    $this->assertFalse(cache_replace($key, 'foo', 0, 'memcached'));
    $this->assertNull(cache_get($key, null, 'memcached'));
    $this->assertTrue(cache($key, 'foo', 0, 'memcached'));
    $this->assertEqual('foo', cache_get($key, null, 'memcached'));
    $this->assertTrue(cache_replace($key, 'bar', 0, 'memcached'));
    $this->assertEqual('bar', cache_get($key, null, 'memcached'));
  }
  
  function testDeleteCache() {
    $key = 'testDeleteCache';
    
    $this->assertTrue(cache($key, 'foo'));
    $this->assertEqual('foo', cache_get($key));
    $this->assertTrue(cache_delete($key));
    $this->assertNull(cache_get($key));
    $this->assertFalse(cache_delete('non-existent-cache-key'));
    
    $this->assertTrue(cache($key, 'foo', 0, 'memcached'));
    $this->assertEqual('foo', cache_get($key, null, 'memcached'));
    $this->assertTrue(cache_delete($key, 'memcached'));
    $this->assertNull(cache_get($key, null, 'memcached'));
    $this->assertFalse(cache_delete('non-existent-cache-key', 'memcached'));
  }
  
  function testIncrement() {
    $key = 'testIncrement';
    
    $this->assertTrue(cache($key, 0));
    $this->assertEqual(1, cache_increment($key));
    $this->assertEqual(2, cache_increment($key));
    $this->assertEqual(3, cache_increment($key));
    $this->assertEqual(13, cache_increment($key, 10));
    $this->assertTrue(cache_delete($key));
    $this->assertFalse(cache_increment('non-existent-incremental-value'));
    
    $this->assertTrue(cache($key, 0, 0, 'memcached'));
    $this->assertEqual(1, cache_increment($key, 1, 'memcached'));
    $this->assertEqual(2, cache_increment($key, 1, 'memcached'));
    $this->assertEqual(3, cache_increment($key, 1, 'memcached'));
    $this->assertEqual(13, cache_increment($key, 10, 'memcached'));
    $this->assertTrue(cache_delete($key, 'memcached'));
    $this->assertFalse(cache_increment('non-existent-incremental-value', 1, 'memcached'));
  }
  
  function testDecrement() {
    $key = 'testDecrement';
    $this->assertTrue(cache($key, 10));
    $this->assertEqual(10, cache_get($key));
    $this->assertEqual(9, cache_decrement($key));
    $this->assertEqual(8, cache_decrement($key));
    $this->assertEqual(4, cache_decrement($key, 4));
    $this->assertEqual(0, cache_decrement($key, 10));
    $this->assertEqual(0, cache_decrement($key, 42));
  }

}
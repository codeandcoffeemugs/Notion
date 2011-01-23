<?php 
/**
 * Idea - a prototyping Framework for PHP developers
 * Copyright (C) 2011  Fat Panda, LLC
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
 
/**
 * Global functions for caching anything.
 * TODO: finish implementing memcache
 * @author Aaron Collegeman
 */

/**
 * Alias for cache_set()
 * @see cache_set()
 */
function cache($cache_key, $value, $timeout = 0, $strategy = CACHE_STRATEGY_DEFAULT, $options = null) {
  return cache_set($cache_key, $value, $timeout, $strategy, $options);
}

function get_cache($cache_key, $default = null, $strategy = CACHE_STRATEGY_DEFAULT, $options = null) {
  if ($strategy == 'options') {
    return cache_option('get', $cache_key, $default, 0, $options);
  } else if ($st[0] == 'memcache') {
    return cache_memcache('get', $cache_key, $default, 0, $options);
  } else {
    throw new Exception(sprintf('Unrecognized caching strategy: %s', $strategy));
  }
}

function cache_set($cache_key, $value, $timeout = 0, $strategy = CACHE_STRATEGY_DEFAULT, $options = null) {
  if ($strategy == 'options') {
    return cache_option('set', $cache_key, $value, $timeout, $options);
  } else if ($strategy == 'memcache') {
    return cache_memcache('set', $cache_key, $value, $timeout, $options);
  } else {
    throw new Exception(sprintf('Unrecognized caching strategy: %s', $strategy));
  }
}

function cache_increment($cache_key, $value = 1, $strategy = CACHE_STRATEGY_DEFAULT, $options = null) {
  if ($strategy == 'options') {
    return cache_option('increment', $cache_key, $value, 0, $options);
  } else if ($strategy == 'memcache') {
    return cache_memcache('increment', $cache_key, $value, 0, $options);
  } else {
    throw new Exception(sprintf('Unrecognized caching strategy: %s', $strategy));
  }
}

function cache_decrement($cache_key, $value = 1, $strategy = CACHE_STRATEGY_DEFAULT, $options = null) {
  if ($strategy == 'options') {
    return cache_option('decrement', $cache_key, $value, 0, $options);
  } else if ($strategy == 'memcache') {
    return cache_memcache('decrement', $cache_key, $value, 0, $options);
  } else {
    throw new Exception(sprintf('Unrecognized caching strategy: %s', $strategy));
  }
}

function cache_add($cache_key, $value, $timeout = 0, $strategy = CACHE_STRATEGY_DEFAULT, $options = null) {
  if ($strategy == 'options') {
    return cache_option('add', $cache_key, $value, $timeout, $options);
  } else if ($strategy == 'memcache') {
    return cache_memcache('add', $cache_key, $value, $timeout, $options);
  } else {
    throw new Exception(sprintf('Unrecognized caching strategy: %s', $strategy));
  }
}

function cache_replace($cache_key, $value, $timeout = 0, $strategy = CACHE_STRATEGY_DEFAULT, $options = null) {
  if ($strategy == 'options') {
    return cache_option('replace', $cache_key, $value, $timeout, $options);
  } else if ($strategy == 'memcache') {
    return cache_memcache('replace', $cache_key, $value, $timeout, $options);
  } else {
    throw new Exception(sprintf('Unrecognized caching strategy: %s', $strategy));
  }
}

function cache_delete($cache_key, $strategy = CACHE_STRATEGY_DEFAULT, $options = null) {
  if ($strategy == 'options') {
    return cache_option('delete', $cache_key, null, null, $options);
  } else if ($strategy == 'memcache') {
    return cache_memcache('delete', $cache_key, null, null, $options);
  } else {
    throw new Exception(sprintf('Unrecognized caching strategy: %s', $strategy));
  }
}

/**
 * Manipulate or read the cache, stored via the Options model.
 * @param string $action - get, set, add, increment, decrement, replace, or delete
 * @param string $cache_key - a unique identifier for this cache
 * @param mixed $value - a value to store, can be any time
 * @param mixed $timeout - how long to store the value in the cache, expressed in human terms, or in total number of seconds; 0 = no expiration
 * @param string $db_group - the database group (configured in config/database.php) to use for storing the data
 * @see http://php.net/manual/en/function.strtotime.php
 * @return mixed, dependent upon action:
 *    get         If a value exists for $cache_key, and has not expired, the stored value is returned; otherwise, $value (a default) is returned
 *    set         Store $value at $cache_key for $timeout; return true on success, false on failure
 *    add         Store $value at $cache_key for $timeout, but only if no value currently exists for $cache_key; return true on success, false on failure
 *    replace     Replace stored value at $cache_key with $value, unless no value existed to be replaced; returns true when value is replaced, false when it did not
 *    increment   Increment the value stored at $cache_key by $value; if no value exists at $cache_key, or if the value is non-numeric, $value is stored; returns the new value on success, false on failure
 *    decrement   Decrement the value stored at $cache_key by $value; if no value exists at $cache_key, or if the value is non-numeric, $value is stored; resulting value will not be less than 0; returns the new value on success, false on failure
 *    delete      Deletes the value stored at $cache_key; returns true on success, false when no value existed to delete
 */
function cache_option($action, $cache_key, $value = null, $timeout = 0, $db_group = 'default') {
  global $CFG;

  if (!$db_group) {
    $db_group = 'default';
  }

  if ($CFG->item('log_threshold') >= 3) {
    log_message('info', sprintf("cache_option::%s(cache_key:%s, value:%s, timeout:%s, db_group:%s)", $action, $cache_key, maybe_serialize($value), $timeout, $db_group));
  }
  
  $cfg = get_cache_config('options', $db_group);
  $key = @$cfg['prefix'].$cache_key;
  $expires = cache_parse_timeout($timeout);
  
  $packet = array(
    'value' => $value,
    'expires' => $expires
  );
  
  if ($action == 'set') {
    return update_option($key, $packet);
    
  } else if ($action == 'add') {
    if (cache_option_packet_expired(get_option($key, null, $db_group))) {
      return update_option($key, $packet);
    } else {
      return false;
    }
    
  } else if ($action == 'replace') {
    if (!cache_option_packet_expired(get_option($key, null, $db_group))) {
      return update_option($key, $packet);
    } else {
      return false;
    }
    
  } else if ($action == 'get') {
    if (!cache_option_packet_expired($stored = get_option($key, null, $db_group))) {
      return $stored['value'];
    } else {
      return $value; // in this case, treated as a default
    }
    
  } else if ($action == 'delete') {
    return delete_option($key);
    
  } else if ($action == 'increment') {
    // lock the options table
    $db = option_db($db_group);
    // TODO: need to add lock_table to the active record API
    $db->query(sprintf('LOCK TABLES %s WRITE', $db->dbprefix.'options'));
    
    if (!cache_option_packet_expired($stored = get_option($key, null, $db_group))) {
      $stored_value = $stored['value'];
      if (!is_numeric($stored_value)) {
        $new_value = $value;
      } else {
        $new_value = $stored_value + $value;
      }
      if (cache_option('set', $cache_key, $new_value)) {
        $db->query('UNLOCK TABLES');
        return $new_value;
      } else {
        $db->query('UNLOCK_TABLES');
        return false;
      }
    } else {
      if ($value < 0) {
        $value = 0;
      }
      
      if (cache_option('set', $cache_key, $value)) {
        $db->query('UNLOCK TABLES');
        return $value;
      } else {
        $db->query('UNLOCK TABLES');
        return false;
      }
    } 
  
  } else if ($action == 'decrement') {
    // lock the options table
    $db = option_db($db_group);
    // TODO: need to add lock_table to the active record API
    $db->query(sprintf('LOCK TABLES %s WRITE', $db->dbprefix.'options'));
    
    if (!cache_option_packet_expired($stored = get_option($key, null, $db_group))) {
      $stored_value = $stored['value'];
      if (!is_numeric($stored_value)) {
        $new_value = $value;
      } else {
        $new_value = $stored_value - $value;
      }
      
      if ($new_value < 0) {
        $new_value = 0;
      }
      
      if (cache_option('set', $cache_key, $new_value)) {
        $db->query('UNLOCK TABLES');
        return $new_value;
      } else {
        $db->query('UNLOCK_TABLES');
        return false;
      }
    } else {
      if ($value < 0) {
        $value = 0;
      }
      
      if (cache_option('set', $cache_key, $value)) {
        $db->query('UNLOCK TABLES');
        return $value;
      } else {
        $db->query('UNLOCK TABLES');
        return false;
      }
    } 
  }
}

/**
 * Determines if cache data stored in the option model has expired.
 * @param array $packet 
 * @return true if $packet is null or empty or expired; false when expiration is not set or when packet has not expired
 */
function cache_option_packet_expired($packet = null) {
  global $CFG;
  
  $now = time();
  
  if (is_null($packet)) {
    $expired = true;
  } else if ($packet['expires'] === 0) {
    $expired = false;
  } else {
    $expired = $packet['expires'] <= $now;
  }
  
  if ($CFG->item('log_threshold') >= 3) {
    log_message('info', sprintf('cache_option_packet_expired(%s) @ %s == %s', @$packet['expires'], $now, $expired ? 'true' : 'false'));
  }
  
  return $expired;
}

function cache_memcache($action, $cache_key, $value = null, $timeout, $options) {
  
}

function cache_parse_timeout($timeout = 0) {
  $now = time();
  
  if (is_numeric($timeout) && $timeout > 0) {
    $expires = $now + $timeout;
  } else if (is_string($timeout)) {
    $expires = strtotime($timeout, $now);
  } else if ($timeout === 0 || is_null($timeout) || $timeout < 0) {
    $expires = 0;
  } else {
    throw new Exception("Unrecognized value for cache timeout: $timeout");
  }
  
  global $CFG;
  if ($CFG->item('log_threshold') >= 3) {
    log_message('info', sprintf('cache_parse_timeout(%s) @ %s == %s', $timeout, $now, $expires));
  }
  
  return $expires;
}

function get_cache_config($strategy = 'options', $id = 'default') {
  global $CFG;
  
  $config = array_merge(array(
    'options' => array()
  ), $CFG->item('cache'));
  
  $cfg = array_merge(array(
    $id => array(
      'prefix' => 'cache-'
    )
  ), $config['options']);
  
  return $cfg[$id];
}

function flush_cache($strategy = CACHE_STRATEGY_DEFAULT, $options = null) {
  if ($strategy == 'options') {
    if (!$options) {
      $options = 'default';
    }
    
    $cfg = get_cache_config($strategy, $options);
    $db = option_db($options);
    $db->like('option_name', $cfg['prefix'], 'after')->delete('options');
    // TODO: this is a little too thorough: dial back to just cached options?
    Stash::delete('options');
  } else if ($strategy == 'memcache') {
    
  } else {
    throw new Exception(sprintf('Unrecognized caching strategy: %s', $strategy));
  }
}

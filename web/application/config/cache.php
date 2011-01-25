<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| CACHING STRATEGIES
| -------------------------------------------------------------------
| Configure caching strategies here.
|
| The default caching strategy is to cache data using the Options model.
| You can configure that default below. 
*/

/*
| -------------------------------------------------------------------
| DEFAULT CACHING STRATEGY?
| -------------------------------------------------------------------
*/
#
# What is the default caching strategy - "options" or "memcached"
#
define('CACHE_STRATEGY_DEFAULT', 'options');

/*
| -------------------------------------------------------------------
| OPTIONS CACHING STRATEGIES
| -------------------------------------------------------------------
*/

#
# "default" DB group
#
$config['cache']['options']['default']['prefix'] = 'cache-'; // how should each cache entry be prefixed?

#
# the memcached servers
#
$config['cache']['memcached'][] = array(
  'host' => 'localhost',
  'port' => 11211,
  'persistent' => true,
  'weight' => 1,
  'timeout' => 1, 
  'retry_interval' => 15,
  'status' => true,
  'failure_callback' => null
);

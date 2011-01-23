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
define('CACHE_STRATEGY_DEFAULT', 'options');

/*
| -------------------------------------------------------------------
| OPTIONS CACHING STRATEGIES
| -------------------------------------------------------------------
*/
// options cache settings are grouped by DB group (the third element in the array)
// how should we prefix each value?
$config['cache']['options']['default']['prefix'] = 'cache-';

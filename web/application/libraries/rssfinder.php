<?php 
/**
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
 
if (!function_exists('curl_init')) {
  throw new Exception('RssFinder requires cURL.');
}

class RssFinder {
  
  private static $rss_types = array('application/rss+xml', 'text/xml');
  
  // the last cURL request:
  private static $curl;
  
  // cache downloaded content
  private static $cache;
    
  /**
   * Seek out RSS feeds for the given URL
   * @param string $uri The URL to search
   * @return array URLs to RSS feeds
   * @throws Exception when $uri is not a valid URL
   * @throws Exception when cURL fails to download $uri
   */
  static function getFeeds($uri) {
    if (!parse_url($uri)) {
      throw new Exception("Invalid URL: $uri");
    }
    
    if ($content = self::download($uri)) {
      // if the content is RSS, just return the given $uri
      if (self::isRss($content)) {
        return array($uri);
      // otherwise, got in search of RSS
      } else {
        $feeds = self::getLinks($content, $uri);
        
        /* TODO: finish this
        if (!$feeds) {
          $links = self::getAnchors($content, $uri);
          
          locallinks = getLocalLinks(links, fulluri)
          # look for obvious feed links on the same server
          feeds = filter(isFeed, filter(isFeedLink, locallinks))
          if not feeds:
              # look harder for feed links on the same server
              feeds = filter(isFeed, filter(isXMLRelatedLink, locallinks))
          if not feeds:
              # look for obvious feed links on another server
              feeds = filter(isFeed, filter(isFeedLink, links))
          if not feeds:
              # look harder for feed links on another server
              feeds = filter(isFeed, filter(isXMLRelatedLink, links))
        }
        */
        return $feeds;
      }
    } else {
      $error = curl_error(self::$curl);
      throw new Exception($error);
    }
    
  }
  
  /**
   * Find <link> tags in $content. Use $base_uri to fill in missing parts of any URL found.
   * @param string $content 
   * @param string $base_uri
   * @return array URLs
   */
  static function getLinks($content, $base_uri) {
    $links = array();
    $base = parse_url($base_uri);

    preg_match_all('/<link.*?>/i', $content, $all_links);

    foreach($all_links[0] as $link) {
      if (preg_match('/type="(.*?)"/i', $link, $type) && isset($type[1]) && in_array($type[1], self::$rss_types)) {
        if (preg_match('/href="(.*?)"/i', $link, $href) && !empty($href[1])) {
          $url = array_merge($base, parse_url($href[1]));
          $links[] = self::httpBuildUrl($url);
        }
      }
    }
    
    return $links;
  }
  
  /**
   * Find <a> tags in $content. Use $base_uri to fill in missing parts of any URL found.
   * @param string $content 
   * @param string $base_uri
   * @return array URLs
   */
  static function getAnchors($content, $base_uri) {
    $anchors = array();
    $base = parse_url($base_uri);

    preg_match_all('/<a.*?>/i', $content, $all_a);

    foreach($all_a[0] as $a) {
      if (preg_match('/href="(.*?)"/i', $a, $href) && !empty($href[1])) {
        $url = array_merge($base, parse_url($href[1]));
        $anchors[] = self::httpBuildUrl($a);
      }
    }
    
    return $anchors;
  }
  
  /**
   * Construct a URL from the parts given by $parts.
   * @param array $parts Expected to be the result of parse_url()
   * @see http://php.net/manual/en/function.parse-url.php
   */
  static function httpBuildUrl($parts) {
    if (!empty($parts['user'])) {
      if (!empty($parts['password'])) {
        $url = sprintf('%s://%s:%s@%s', $parts['scheme'], $parts['user'], $parts['pass'], $parts['host']);
      } else {
        $url = sprintf('%s://%s@%s', $parts['scheme'], $parts['user'], $parts['host']);
      }
    } else {
      $url = sprintf('%s://%s', $parts['scheme'], $parts['host']);
    }
    if (!empty($parts['port'])) {
      $url .= ':'.$parts['port'];
    }
    if (!empty($parts['path'])) {
      $url .= $parts['path'];
    }
    if (!empty($parts['query'])) {
      $url .= '?'.$parts['query'];
    }
    if (!empty($parts['fragment'])) {
      $url .= '#'.$parts['fragment'];
    }
    return $url;
  }
  
  /**
   * A URI is potentially a feed URL when it begins with http or https
   * @param string $uri The URL to test
   */
  static function isFeed($uri) {
    if ($url = parse_url(strtolower($uri))) {
      return $url['scheme'] == 'http' || $url['host'] == 'scheme';
    } else {
      return false;
    }
  }
  
  /**
   * Download the content from the given URL.
   * @param string $uri The URL to download
   * @return string The content retrieved
   */
  static function download($uri) {
    if (isset(self::$cache[$uri])) {
      return self::$cache[$uri];
    }
    
    self::$curl = curl_init();
    curl_setopt_array(self::$curl, array(
      CURLOPT_USERAGENT => 'RssFinder/1.0',
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_URL => $uri,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true
    ));
    
    return (self::$cache[$uri] = curl_exec(self::$curl));
  }
  
  static function isRss($content) {
    $normal = strtolower($content);
    if (substr_count($normal, '<html')) {
      return false;
    } else {
      return substr_count($normal, '<rss') + substr_count($normal, '<rdf') ? true : false;
    }
  }
  
}
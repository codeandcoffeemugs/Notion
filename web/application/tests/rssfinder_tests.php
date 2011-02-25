<?php
class RssFinderTests extends UnitTestCase {
  
  const KNOWN_RSS = 'http://feeds.feedburner.com/bassistscom';
  const KNOWN_NOT_RSS = 'http://www.facebook.com';
  const KNOWN_HAS_LINKS = 'http://www.notreble.com';
  
  private $rssfinder;
  
  function setUp() {
    if (!$this->rssfinder) {
      $CI = get_instance();
      $CI->load->library('rssfinder');
      $this->rssfinder = $CI->rssfinder;
    }
  }
  
  function testRssDetection() {
    $content = $this->rssfinder->download(self::KNOWN_RSS);
    $this->assertTrue($this->rssfinder->isRss($content));
    $content = $this->rssfinder->download(self::KNOWN_NOT_RSS);
    $this->assertFalse($this->rssfinder->isRss($content));
    $this->assertEqual(array(self::KNOWN_RSS), $this->rssfinder->getFeeds(self::KNOWN_RSS));
  }
  
  function testHttpBuildUrl() {
    $base_url = 'http://www.myurl.com/';
    $partial_url = '/foo/bar/ding/rss.xml?foo#bar';
    $parts = array_merge(parse_url($base_url), parse_url($partial_url));
    $this->assertEqual('http://www.myurl.com/foo/bar/ding/rss.xml?foo#bar', $this->rssfinder->httpBuildUrl($parts));
  }
  
  function testGetLinks() {
    $content = $this->rssfinder->download(self::KNOWN_HAS_LINKS);
    $links = $this->rssfinder->getLinks($content, self::KNOWN_HAS_LINKS);
    $this->assertEqual(array(
      'http://feeds.feedburner.com/bassistscom',
      'http://www.notreble.com/home/feed/'
    ), $links);
  }
  
  function testGetFeeds() {
    $this->assertEqual(array(
      'http://feeds.feedburner.com/bassistscom',
      'http://www.notreble.com/home/feed/'
    ), $this->rssfinder->getFeeds(self::KNOWN_HAS_LINKS));
  }
  
}
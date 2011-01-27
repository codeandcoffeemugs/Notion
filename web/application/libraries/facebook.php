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
require('facebook-sdk-2.1.2.php');
 
// don't verify SSL cert
FacebookClient::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = false;
 
class Facebook extends FacebookClient {
  
  /**
   * Creates a new instance of the Facebook API client, configuring it 
   * according to the settings group named $config (see config/facebook.php).
   * This constructor also calls FacebookClient->getSession, which will
   * automatically consult the request for a cookie or a session parameter
   * from which to derive authentication.
   */
  function __construct($config = FACEBOOK_DEFAULT_APP) {
    global $CFG;
    $CFG->load('facebook');
    $facebook = $CFG->item('facebook');
    if ($cfg = @$facebook[$config]) {
      if (!@$cfg['appId'] || !@$cfg['secret']) {
        show_error('Facebook library has not been configured.');
      }
      parent::__construct($cfg);
    } else {
      show_error('Facebook library has not been configured.');
    }
    
    $this->getSession();
  }
  
}
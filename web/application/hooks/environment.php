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
 * This function is called as a pre_system hook, and establishes the
 * execution environment. To establish the execution environment,
 * set $_ENV['CI_ENV'] equal to some value. That value will be used
 * as a suffix on configuration files, loading the environment-specific
 * one if available, and loading the default when not.
 *
 * Note that $_ENV['CI_ENV'] can be set by processes external to PHP,
 * i.e., Apache. If you are using Apache to influence the CI execution
 * environment, then do nothing in this function.
 *
 * The default behavior is to look for the words "local" and "dev" in
 * $_SERVER['HTTP_HOST'] and $_SERVER['SERVER_NAME']. Find either of these
 * will result in setting $_ENV['CI_ENV'] to 'dev'.
 */

function _do_detect_environment() {
  global $CFG;
  
  // if $_ENV['CI_ENV'] is already set, don't do anything
  if (empty($_ENV['CI_ENV'])) {
  
    $server_name = @$_SERVER['HTTP_HOST'] OR @$_SERVER['SERVER_NAME'];
    if (preg_match('/(dev)|(local)/i', $server_name)) {
      // dev environment, please:
      $_ENV['CI_ENV'] = 'dev';
      // set logging threashold to info-level
      $CFG->set_item('log_threshold', 3);
      $logger = load_class('Log');
      $logger->setThreshold(3);
    }
  
  }
  
  // look for an environment-specific cofig file
  if ($env = @$_ENV['CI_ENV']) {
    $CFG->load('config-'.$env, false, true);
  }
}
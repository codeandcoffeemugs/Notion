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
 
class MY_Input extends CI_Input {
  
  /**
   * @return true when the request method is POST
   */
  function isPost() {
    return $this->isRequestMethod('post');
  }
  
  /**
   * @return true when the request method is GET
   */
  function isGet() {
    return $this->isRequestMethod('get');
  }
  
  /**
   * @return true when the request method is DELETE
   */
  function isDelete() {
    return $this->isRequestMethod('delete');
  }
  
  /**
   * @return true when the request method is CREATE
   */
  function isCreate() {
    return $this->isRequestMethod('create');
  }
  
  
  function isRequestMethod($method) {
    return strtolower($method) == strtolower(@$_SERVER['REQUEST_METHOD']);
  }
  
}
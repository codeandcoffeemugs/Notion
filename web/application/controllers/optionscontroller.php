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
 * A utility controller.
 */
class OptionsController extends Controller {
  
  const CORE_FACEBOOK_SESSION = '_core_facebook_session';
  
  /**
   * Authorize and cache a Facebook session to enable CLI access to the Facebook API.
   */
  function facebook() {
    if ($this->input->isPost()) {
      update_option(self::CORE_FACEBOOK_SESSION, $_POST['session']);
    } else {
      $this->load->library('facebook');
      $this->load->view('options/facebook', array(
        'saved_session_exists' => get_option(self::CORE_FACEBOOK_SESSION) ? true : false
      ));
    }
  }
  
}
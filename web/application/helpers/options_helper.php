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
 * Global functions for persisting globally-namespaced settings.
 * Based on the option model in WordPress.
 * @author Aaron Collegeman
 */
 
/**
 * Get an option $name
 * @param string $name The name of the option to load
 * @param mixed $default (optional) A default value to return when $name option does not exist
 * @param string $db_group (optional) The database group to load; defaults to 'default'
 */
function get_option($name, $default = null, $db_group = 'default') {
  if (Stash::has('options', $name)) {
    return Stash::get('options', $name, $default, true);
    
  } else {
    $db = DB($db_group);
    $query = $db->get_where('options', array('option_name' => $name));
    
    if (!$query->num_rows()) {
      return $default;
    } else {
      $option = $query->result('object');
      $value = maybe_unserialize($option->option_value);
      Stash::update('options', $name, $value);
      return $value;
    }
  }
}

/**
 * Save a new option, but only if it does not already exist
 * @param string $name The option's name
 * @param mixed $value The option's value - can be any value type (scalar, object, or array)
 * @param bool $autoload (optional) defaults to true
 * @param string $db_group (optional) The database group to load; defaults to 'default'
 * @return true when the option did not exist and was written; otherwise, false
 */
function add_option($name, $value, $autoload = true, $db_group = 'default') {
  if (Stash::has('options', $name)) {
    return false;
    
  } else {
    $db = DB($db_group);
    $query = $db->get_where('options', array('option_name' => $name));
    
    // if it exists, we're done here
    if ($query->num_rows()) {
      return false;
      
    } else {
      $db->insert('options', array(
        'option_name' => $name,
        'option_value' => maybe_serialize($value),
        'autoload' => $autoload
      ));
      
      Stash::update('options', $name, $value);
      
      return true;
    }  
  }
}

/**
 * Update the value stored for an option
 * @param string $name The option's name
 * @param mixed $value The option's value - can be any value type (scalar, object, or array)
 * @param bool $autoload (optional) defaults to true
 * @param string $db_group (optional) The database group to load; defaults to 'default'
 */
function update_option($name, $value, $autoload = true, $db_group = 'default') {
  $db = DB($db_group);
  $query = $db->get_where('options', array('option_name' => $name));
  
  if ($query->num_rows()) {
    $db->where('option_name', $name)->update('options', array(
      'option_name' => $name,
      'option_value' => maybe_serialize($value),
      'autoload' => $autoload
    ));
    
  } else {
    $db->insert('options', array(
      'option_name' => $name,
      'option_value' => maybe_serialize($value),
      'autoload' => $autoload
    ));
  }
  
  Stash::update('options', $name, $value);
}

/**
 * Delets the option, if it exists.
 * @param string $name The option's name
 * @param string $db_group (optional) The database group to load; defaults to 'default'
 */
function delete_option($name, $db_group = 'default') {
  Stash::delete('options', $name);
  $db = DB($db_group);
  $db->delete('options', array('option_name' => $name));
}

/**
 * Remove all options from the system.
 * @param string $db_group (optional) defaults to 'default'
 */ 
function trunc_options($db_group = 'default') {
  $db = DB($db_group);
  Stash::delete('options');
  $db->empty_table('options');
}

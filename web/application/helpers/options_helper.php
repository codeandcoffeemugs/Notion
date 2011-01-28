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
function get_option($name, $default = null, $db_group = OPTIONS_DEFAULT_DBGROUP) {
  global $CFG;
  if ($CFG->item('log_threshold') >= 3 ) {
    log_message('info', sprintf('Loading option [%s], with default [%s]', $name, maybe_serialize($default)));
  }
  
  if (Stash::has('__options__', $name)) {
    return Stash::get('__options__', $name, $default, true);
    
  } else {
    $db = option_db($db_group);
    $query = $db->get_where('options', array('option_name' => $name));
    
    if (!$query->num_rows()) {
      return $default;
      
    } else {
      $option = $query->row('object');
      $value = maybe_unserialize($option->option_value);
      Stash::update('__options__', $name, $value);
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
function add_option($name, $value, $autoload = true, $db_group = OPTIONS_DEFAULT_DBGROUP) {
  if (Stash::has('__options__', $name)) {
    return false;
    
  } else {
    $db = option_db($db_group);
    $query = $db->get_where('options', array('option_name' => $name));
    
    // if it exists, we're done here
    if ($query->num_rows()) {
      log_message('info', sprintf('Could not add option [%s]: it already exists', $name));
      
      return false;
      
    } else {
      $serialized = maybe_serialize($value);
      
      log_message('info', sprintf('Adding%s option [%s] with value [%s]', $autoload ? ' autoloading' : '', $name, $serialized));
      
      $db->insert('options', array(
        'option_name' => $name,
        'option_value' => maybe_serialize($value),
        'autoload' => $autoload
      ));
      
      Stash::update('__options__', $name, $value);
      
      return true;
    }  
  }
}

/**
 * Does an option $name exist? This function bypasses the Stash and looks at the datastore.
 * @param $name
 * @param string $db_group (optional) The database group to load; defaults to 'default'
 * @return true when exists; false on failure
 */
function has_option($name, $db_group = OPTIONS_DEFAULT_DBGROUP) {
  $db = option_db($db_group);
  $query = $db->get_where('options', array('option_name' => $name));
  $exists = $query->num_rows() > 0 ? true : false;
  
  log_message('info', sprintf('Option [%s] %s', $name, $exists ? 'exists' : 'does not exist'));

  return $exists;
}

/**
 * Update the value stored for an option
 * @param string $name The option's name
 * @param mixed $value The option's value - can be any value type (scalar, object, or array)
 * @param bool $autoload (optional) defaults to true
 * @param string $db_group (optional) The database group to load; defaults to 'default'
 * @return true on success; false on failure
 */
function update_option($name, $value, $autoload = true, $db_group = OPTIONS_DEFAULT_DBGROUP) {
  $serialized = maybe_serialize($value);
  
  log_message('info', sprintf('Updating%s option [%s] with value [%s]', $autoload ? ' autoloading' : '', $name, $serialized));
  
  $db = option_db($db_group);
  $query = $db->get_where('options', array('option_name' => $name));
  
  if ($query->num_rows()) {
    $result = $db->where('option_name', $name)->update('options', array(
      'option_name' => $name,
      'option_value' => $serialized,
      'autoload' => $autoload
    ));
    
  } else {
    $result = $db->insert('options', array(
      'option_name' => $name,
      'option_value' => $serialized,
      'autoload' => $autoload
    ));
  }
  
  if ($result) {
    Stash::update('__options__', $name, $value);
  }
  
  return $result;
}

/**
 * Delets the option, if it exists.
 * @param string $name The option's name
 * @param string $db_group (optional) The database group to load; defaults to 'default'
 * @return true when there was something to delete; false when not, or on failure
 */
function delete_option($name, $db_group = OPTIONS_DEFAULT_DBGROUP) {
  Stash::delete('__options__', $name);
  $db = option_db($db_group);
  $db->delete('options', array('option_name' => $name));

  $deleted = $db->affected_rows() > 0 ? true : false;
  
  log_message('info', sprintf('Option [%s] %s', $name, $deleted ? 'was deleted' : 'did not exist to delete'));
  
  return $deleted;
}

/**
 * Don't create multiple instances of the DB driver classes.
 */
function option_db($db_group = OPTIONS_DEFAULT_DBGROUP) {
  static $instances;
  
  if (!$instances) {
    $instances = array();
  }
  
  if (!isset($instances[$db_group])) {
    log_message('info', "Creating Database Driver Class for options in [$db_group]");
    $instances[$db_group] = DB($db_group);
  }
  
  return $instances[$db_group];
}

/**
 * Remove all options from the system.
 * @param string $db_group (optional) defaults to 'default'
 */ 
function delete_all_options($db_group = OPTIONS_DEFAULT_DBGROUP) {
  $db = DB($db_group);
  Stash::delete('__options__');
  $db->empty_table('options');
}

function options_autoload($db_group = OPTIONS_DEFAULT_DBGROUP) {
  $db = DB($db_group);
  $all = $db->get_where('options', array('autoload' => true));
  foreach($all->result() as $opt) {
    log_message('info', "Auto-loaded option [$opt->option_name]");
    $value = maybe_unserialize($opt->option_value);
    Stash::update('__options__', $opt->option_name, $value);
  }
}

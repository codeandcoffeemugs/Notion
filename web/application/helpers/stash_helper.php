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
 * A place to stash data for the lifetime of a single request.
 * @author Aaron Collegeman
 */
class Stash {
  
  private $stash = array();
  
  static function add($ns, $name, $value) {
    if (!self::has($ns, $name)) {
      self::update($ns, $name, $value);
    } else {
      self::$stash[$ns][$name][] = $value;
    }
  }
  
  static function update($ns, $name, $value) {
    self::$stash[$ns][$name] = array($value);
  }
  
  static function get($ns, $name, $default = null, $single = false) {
    if (self::has($ns, $name)) {
      return $default;
    } else {
      $value = $stash[$ns][$name];
      return $single ? $value[0] : $value;
    }
  }
  
  static function delete($ns, $name) {
    unset(self::$stash[$ns][$name]);
  }
  
  static function has($ns, $name) {
    return isset(self::$stash[$ns][$name]);
  }
  
}

/**
 * Serialize data, if needed.
 * @param mixed $data Data that might be serialized.
 * @return mixed A scalar data
 */
function maybe_serialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) )
		return serialize( $data );

	if ( is_serialized( $data ) )
		return serialize( $data );

	return $data;
}

/**
 * Unserialize value only if it was serialized.
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
function maybe_unserialize( $original ) {
	if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	return $original;
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @param mixed $data Value to check to see if was serialized.
 * @return bool False if not serialized and true if it was.
 */
function is_serialized( $data ) {
	// if it isn't a string, it isn't serialized
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	if ( 'N;' == $data )
		return true;
	if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
		return false;
	switch ( $badions[1] ) {
		case 'a' :
		case 'O' :
		case 's' :
			if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
				return true;
			break;
		case 'b' :
		case 'i' :
		case 'd' :
			if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
				return true;
			break;
	}
	return false;
}
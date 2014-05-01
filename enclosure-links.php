<?php 

/*
Plugin Name: Enclosure Links
Plugin URI: http://github.com/simonwheatley/enclosure-links/
Description: Adds links to the post body from any enclosure custom fields.
Version: 0.1
Author: Simon Wheatley
Author URI: http://simonwheatley.co.uk/
*/
 
/*  Copyright 2014 Simon Wheatley

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/


/**
 * 
 * 
 * @package 
 **/
class SW_Enclosure_Links {

	/**
	 * Singleton stuff.
	 * 
	 * @access @static
	 * 
	 * @return SW_Enclosure_Links object
	 */
	static public function init() {
		static $instance = false;

		if ( ! $instance )
			$instance = new SW_Enclosure_Links;

		return $instance;

	}

	/**
	 * Class constructor
	 *
	 * @return null
	 */
	public function __construct() {
	}

	// HOOKS
	// =====

	// CALLBACKS
	// =========

	// UTILITIES
	// =========

}

// Initiate the singleton
SW_Enclosure_Links::init();

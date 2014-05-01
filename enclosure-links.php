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
	 * A flag to determine when we are in a save_post action
	 *
	 * @var bool
	 **/
	protected $in_save_post;

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
		add_action( 'save_post_post', array( $this, 'action_save_post_post_early' ), 1, 3 );

		$this->in_save_post = false;
	}

	// HOOKS
	// =====

	/**
	 * Hooks the WP action save_post_{post}
	 *
	 * @action save_post_{post}
	 * 
	 * @param int $post_ID
	 * @param object $post A Post object
	 * @param bool $update Whether this is an update to an existing post
	 * @return void
	 * 
	 * @author Simon Wheatley
	 **/
	public function action_save_post_post_early( $post_ID, WP_Post $post, $update ) {
		// Prevent recursion
		if ( $this->in_save_post ) {
			return;
		}
		$this->in_save_post = true;

		$post_urls = wp_extract_urls( $post->post_content );
		// var_dump( $post_urls );
		$meta = get_post_meta( $post_ID );
		// var_dump( $meta );
		foreach ( $meta as $meta_key => $meta_values ) {
			foreach ( $meta_values as $meta_value ) {
				if ( 'enclosure' != $meta_key ) {
					continue;
				}
				// What URLs are in the enclosure?
				$enclosure_urls = wp_extract_urls( $meta_value );
				// var_dump( $enclosure_urls );
				
				// Ensure the enclosure URLs in the post_urls
				foreach ( $enclosure_urls as $enclosure_url ) {
					if ( ! in_array( $enclosure_url, $post_urls ) ) {
						// var_dump( $enclosure_url );
						$this->add_url_as_link_in_post( $post_ID, $enclosure_url );
						// Update our knowledge of post URLs
						$post_urls = wp_extract_urls( get_post( $post_ID )->post_content );
					}
				}
			}
		}

		// exit;
		$this->in_save_post = false;
	}

	// UTILITIES
	// =========

	/**
	 * 
	 *
	 * @param int $post_ID The ID of the post to add the link to
	 * @param string $enclosure_url The URL to add as a link
	 * @return void
	 * 
	 * @author Simon Wheatley
	 **/
	public function add_url_as_link_in_post( $post_ID, $enclosure_url ) {
		$post_data = array(
			'ID' => $post_ID,
			'post_content' => get_post( $post_ID )->post_content,
		);
		$url_parts = @parse_url( $enclosure_url );
		$type = false;
		if ( false !== $url_parts ) {
			$extension = pathinfo( $url_parts['path'], PATHINFO_EXTENSION );
			if ( !empty( $extension ) ) {
				foreach ( wp_get_mime_types() as $exts => $mime ) {
					if ( preg_match( '!^(' . $exts . ')$!i', $extension ) ) {
						$type = $mime;
						break;
					}
				}
			}
		}
		if ( $type ) {
			$link_text = sprintf( __( 'Download (%s)', 'enclosure-links' ), $type );
		} else {
			$link_text = __( 'Download', 'enclosure-links' );
		}
		$post_data[ 'post_content' ] .= "<p class='sw-enclosure-link'><a href='$enclosure_url'>$link_text</a></p>";
		// var_dump( $post_data[ 'post_content' ] );
		wp_update_post( $post_data );
	}

}

// Initiate the singleton
SW_Enclosure_Links::init();

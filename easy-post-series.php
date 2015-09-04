<?php
/**
 * Plugin Name: Easy Post Series
 * Plugin URI: https://wordpress.org/plugins/easy-post-series/
 * Description: Create series of posts easily.
 * Version: 1.1.2
 * Author: Yudhistira Mauris
 * Author URI: http://www.yudhistiramauris.com/
 * Text Domain: easy-post-series
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: languages
 *
 * Copyright © 2015 Yudhistira Mauris (email: mauris@yudhistiramauris.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/gpl-2.0.txt>.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// Check if class doesn't exist
if ( ! class_exists( 'Easy_Post_Series' ) ) {
	
	/**
	* Main Easy_Post_Series class
	*
	* The class is responsible for setting up instance,
	* constants, included files, and localization.
	*/
	class Easy_Post_Series {
		
		/**
		 * The one true Easy_Post_Series
		 * 
		 * @var Easy_Post_Series
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Taxonomy name and slug
		 *
		 * @since 1.2.0
		 * @var string
		 */
		public $taxonomy;

		/**
		 * Main Easy_Post_Series instance
		 *
		 * @since 1.0
		 * @static		 
		 * @return object The one true Easy_Post_Series
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Easy_Post_Series ) ) {
				self::$instance = new Easy_Post_Series();
				self::$instance->setup_constants();				
				self::$instance->load_textdomain();
				self::$instance->setup_hooks();

				self::$instance->taxonomy = apply_filters( 'wpeps_taxonomy', 'series' );
			}
			return self::$instance;
		}

		/**
		 * Setup constants used throughout the plugin
		 *
		 * @since 1.0
		 */
		public function setup_constants() {			
			// Plugin version
			if ( ! defined( 'WPEPS_VERSION' ) ) {
				define( 'WPEPS_VERSION', '1.1.2' );
			}

			// Plugin folder path
			if ( ! defined( 'WPEPS_PLUGIN_PATH' ) ) {
				define( 'WPEPS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin folder url
			if ( ! defined( 'WPEPS_PLUGIN_URL' ) ) {
				define( 'WPEPS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin main file
			if ( ! defined( 'WPEPS_PLUGIN_FILE' ) ) {
				define( 'WPEPS_PLUGIN_FILE', __FILE__ );
			}
		}
		
		/**
		 * Load localization file
		 *
		 * @since 1.0
		 */
		public function load_textdomain() {
			$wpeps_lang_dir = WPEPS_PLUGIN_PATH . 'languages/';
			$wpeps_lang_dir = apply_filters( 'wpeps_languages_directory', $wpeps_lang_dir );
			load_plugin_textdomain( 'easy-post-series', false, $wpeps_lang_dir );
		}

		/**
		 * Setup action hooks used throughout the plugin
		 *
		 * @since 1.0
		 */
		private function setup_hooks() {
			register_activation_hook( WPEPS_PLUGIN_FILE, array( $this, 'on_activation' ) );
			add_action( 'init', array( $this, 'register_taxonomy_series' ) );			
			add_filter( 'the_content', array( $this, 'series_post_navigation' ) );
			add_filter( 'pre_get_posts', array( $this, 'series_archive_page_query' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts_and_styles') );
			register_deactivation_hook( WPEPS_PLUGIN_FILE, array( $this, 'on_deactivation' ) );
		}

		/**
		 * Run this function during plugin activation
		 *
		 * @since 1.0
		 */
		public function on_activation() {
			// Call register taxonomy function so that the flush function will work
			$this->register_taxonomy_series();
			flush_rewrite_rules();
		}

		/**
		 * Run this function during plugin deactivation
		 *
		 * @since 1.0
		 */
		public function on_deactivation() {
			flush_rewrite_rules();
		}

		/**
		 * Create a taxonomy called series
		 *
		 * Callback function of an action hook, used to register a new taxonomy.
		 * 
		 * @since 1.0
		 */
		public function register_taxonomy_series() {
			$labels = array(
				'name'					     => _x( 'Series', 'Plural name', 'easy-post-series' ),
				'singular_name'			     => _x( 'Series', 'Singular name', 'easy-post-series' ),
				'search_items'			     => __( 'Search Series', 'easy-post-series' ),
				'popular_items'			     => __( 'Popular Series', 'easy-post-series' ),
				'all_items'				     => __( 'All Series', 'easy-post-series' ),
				'parent_item'			     => __( 'Parent Series', 'easy-post-series' ),
				'parent_item_colon'		     => __( 'Parent Series', 'easy-post-series' ),
				'edit_item'				     => __( 'Edit Series', 'easy-post-series' ),
				'update_item'			     => __( 'Update Series', 'easy-post-series' ),
				'add_new_item'			     => __( 'Add New Series', 'easy-post-series' ),
				'new_item_name'			     => __( 'New Series', 'easy-post-series' ),
				'separate_items_with_commas' => __( '' , 'easy-post-series' ),
				'add_or_remove_items'	     => __( 'Add or remove series', 'easy-post-series' ),
				'choose_from_most_used'	     => __( '', 'easy-post-series' ),
				'menu_name'				     => __( 'Series', 'easy-post-series' ),
			);
			$labels = apply_filters( 'wpeps_taxonomy_labels', $labels );
		
			$args = array(
				'labels'            => $labels,
				'public'            => true,
				'show_in_nav_menus' => true,
				'show_admin_column' => true,
				'hierarchical'      => false,
				'show_tagcloud'     => false,
				'show_ui'           => true,
				'query_var'         => true,
				'rewrite'           => true,
				'query_var'         => true,				
				'capabilities'      => array(),
			);
			$args = apply_filters( 'wpeps_taxonomy_args', $args );

			// $taxonomy = apply_filters( 'wpeps_taxonomy', 'series' );
			register_taxonomy( $this->taxonomy, array( 'post' ), $args );
		}

		/**
		 * Create navigation among posts in a series on single post page
		 *
		 * @since 1.0
		 * @return Display navigation panel on a single post of a series
		 */
		public function series_post_navigation( $content ) {

			if ( is_singular( 'post' ) && has_term( '', 'series' ) && is_main_query() ) {

				$terms = wp_get_post_terms( get_the_ID(), 'series' );
				$nav   = '';

				foreach ( $terms as $term_key => $term ) {

					$this->series_navigation( $term->term_id );
				}

				return $nav . $content;				
			}			
			return $content;
		}

		/**
		 * Output series navigation
		 *
		 * @since  1.0
		 * @param  integer $term_id ID of a term
		 * @return string           Series navigation HTML
		 */
		public function series_navigation( $term_id ) {
			
			$term = get_term_by( 'id', $term_id, $this->taxonomy, OBJECT );
			$nav = '';

			$term_url   = get_term_link( $term->slug, $this->taxonomy );
			$term_name  = $term->name;
			$nav_number = (int) $term_id;

			$nav .= '<nav class="wpeps-series-nav wpeps-series-' . $nav_number . '">';
			$nav .= apply_filters( 'wpeps_html_before_nav_paragraph', '' );
			$nav .= '<p>' . sprintf( __( 'This post is part of the series <a href=%1$s>%2$s</a>.', 'easy-post-series' ), $term_url, $term_name  ) . '<a href="#" onclick="return false;" class="wpeps-show-posts">' . __( 'Show All Posts', 'easy-post-series' ) . '</a><a href="#" onclick="return false;" class="wpeps-hide-posts">' . __( 'Hide All Posts', 'easy-post-series' ) . '</a></p>';
			$nav .= apply_filters( 'wpeps_html_after_nav_paragraph', '' );
			$nav .= '<ul>';

			// Get posts of the term
			$order    = apply_filters( 'wpeps_nav_post_order', 'ASC' );
			$orderby  = apply_filters( 'wpeps_nav_post_orderby', 'post_date' );

			$args = array(
				'order'          => $order,
				'orderby'        => $orderby,
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy'       => $this->taxonomy,
						'field'          => 'slug',
						'terms'          => $term->slug,
					),
				),
			);
			$posts = get_posts( $args );

			// Loop through each post
			foreach ( $posts as $key => $post ) {
				$nav .= '<li>' . sprintf( __( 'Part %1$d: <a href="%2$s">%3$s</a>', 'easy-post-series' ), (int) $key + 1, get_permalink( $post->ID ), $post->post_title ) . '</li>';
			}

			$nav .= '</ul>';
			$nav .= apply_filters( 'wpeps_html_after_nav_list', '' );
			$nav .= '</nav>';
			
			echo $nav;
		}

		/**
		 * Provides a way for user to customize taxonomy archive page post order
		 * 
		 * @param  object $query Query object from 'pre_get_posts' filter
		 * @return object        Modified query object
		 *
		 * @since  1.1
		 */
		public function series_archive_page_query( $query ) {
			if ( is_tax( $this->taxonomy ) ) {
				$order   = apply_filters( 'wpeps_archive_page_post_order', 'DESC' );
				$orderby = apply_filters( 'wpeps_archive_page_post_orderby', 'date' );
				$query->set( 'order', $order );
				$query->set( 'orderby', $orderby );
			}
			return $query;
		}

		/**
		 * Load scripts and styles used by the plugin
		 * 
		 * @since  1.0
		 */
		public function load_scripts_and_styles() {
			wp_enqueue_script( 'wpeps-scripts', WPEPS_PLUGIN_URL . 'assets/js/scripts.js', array( 'jquery' ), WPEPS_VERSION, false );
			wp_enqueue_style( 'wpeps-styles', WPEPS_PLUGIN_URL . 'assets/css/styles.css', array(), WPEPS_VERSION );
		}
	} // End class Easy_Post_Series
} // End if class_exist check

/**
 * Main function that run Easy_Post_Series class
 *
 * Create ESP instance and fire the plugin.
 *
 * @since 1.0
 * @return object The one true Easy_Post_Series instance
 */
function easy_post_series() {
	return Easy_Post_Series::instance();
}

// Run Easy Post Series
easy_post_series();
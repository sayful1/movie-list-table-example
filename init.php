<?php
/**
 * Plugin Name: Movie: WP_List_Table class implementation example
 * Description: A highly documented plugin that demonstrates how to create custom List Tables using official WordPress APIs.
 * Version: 1.0.0
 * Author: Sayful Islam
 * Author URI: http://www.sayfulislam.com
 * License: GPLv3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Movie_List_Table_DB_Example' ) ):

	class Movie_List_Table_DB_Example {

		private static $instance;
		private $plugin_name = 'movie-list-table';
		private $version = '1.0.0';
		private $plugin_path;
		private $plugin_url;

		/**
		 * @return Movie_List_Table_DB_Example
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {

			$this->define_constants();
			$this->includes();

			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_filter( 'set-screen-option', array( $this, 'set_screen' ), 10, 3 );

			register_activation_hook( __FILE__, array( $this, 'activation' ) );
		}

		public function define_constants() {
			define( 'MOVIE_LIST_TABLE_VERSION', $this->version );
			define( 'MOVIE_LIST_TABLE_FILE', __FILE__ );
			define( 'MOVIE_LIST_TABLE_PATH', dirname( MOVIE_LIST_TABLE_FILE ) );
			define( 'MOVIE_LIST_TABLE_INCLUDES', MOVIE_LIST_TABLE_PATH . '/includes' );
			define( 'MOVIE_LIST_TABLE_TEMPLATES', MOVIE_LIST_TABLE_PATH . '/templates' );
			define( 'MOVIE_LIST_TABLE_WIDGETS', MOVIE_LIST_TABLE_PATH . '/widgets' );
			define( 'MOVIE_LIST_TABLE_URL', plugins_url( '', MOVIE_LIST_TABLE_FILE ) );
			define( 'MOVIE_LIST_TABLE_ASSETS', MOVIE_LIST_TABLE_URL . '/assets' );
		}

		private function includes() {
			include MOVIE_LIST_TABLE_INCLUDES . '/Movie_List_Table_Activation.php';

			include MOVIE_LIST_TABLE_INCLUDES . '/models/Movie.php';

			include MOVIE_LIST_TABLE_INCLUDES . '/Movie_List_Table.php';
			include MOVIE_LIST_TABLE_INCLUDES . '/Movie_Form_Handler.php';

			new Movie_Form_Handler;
		}

		public function admin_menu() {
			$hook = add_menu_page(
				__( 'Movies', 'textdomain' ),
				__( 'Movies', 'textdomain' ),
				'activate_plugins',
				'movies',
				array( $this, 'menu_page' ),
				'dashicons-editor-video',
				99
			);

			add_submenu_page(
				'movies',
				__( 'All Movies', 'textdomain' ),
				__( 'All Movies', 'textdomain' ),
				'activate_plugins',
				'movies',
				array( $this, 'menu_page' )
			);

			add_submenu_page(
				'movies',
				__( 'Add New', 'textdomain' ),
				__( 'Add New', 'textdomain' ),
				'activate_plugins',
				'add_movie',
				array( $this, 'submenu_page' )
			);

			add_action( "load-$hook", array( $this, 'screen_option' ) );
		}

		public function menu_page() {
			$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
			$id     = isset( $_GET['movie'] ) ? intval( $_GET['movie'] ) : 0;

			switch ( $action ) {
				case 'view':
					$template = MOVIE_LIST_TABLE_PATH . '/views/single.php';
					break;

				case 'edit':
					$template = MOVIE_LIST_TABLE_PATH . '/views/edit.php';
					break;

				default:
					$template = MOVIE_LIST_TABLE_PATH . '/views/list.php';
					break;
			}

			if ( file_exists( $template ) ) {
				include $template;
			}
		}

		public function submenu_page() {
			$template = MOVIE_LIST_TABLE_PATH . '/views/new.php';

			if ( file_exists( $template ) ) {
				include $template;
			}
		}

		public function screen_option() {
			$option = 'per_page';
			$args   = [
				'label'   => 'Movies',
				'default' => 5,
				'option'  => 'movies_per_page'
			];

			add_screen_option( $option, $args );
		}


		public function set_screen( $status, $option, $value ) {
			return $value;
		}

		/**
		 * To be run when the plugin is activated
		 * @return void
		 */
		public function activation() {
			do_action( 'movie_list_table_activation' );
			flush_rewrite_rules();
		}

		/**
		 * To be run when the plugin is deactivated
		 * @return void
		 */
		public function deactivation() {
			do_action( 'movie_list_table_deactivation' );
			flush_rewrite_rules();
		}

	}

endif;

Movie_List_Table_DB_Example::instance();

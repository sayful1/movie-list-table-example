<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Movie_List_Table_Activation' ) ):

	class Movie_List_Table_Activation {

		private static $instance;

		/**
		 * @return Movie_List_Table_Activation
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_action( 'movie_list_table_activation', array( $this, 'create_database' ), 10 );
			add_action( 'movie_list_table_activation', array( $this, 'insert_movies' ), 20 );
		}

		public function create_database() {
			global $wpdb;
			$movies  = $wpdb->prefix . "movies";
			$charset = $wpdb->get_charset_collate();

			$movies_sql = "CREATE TABLE IF NOT EXISTS $movies (
            ID int(11) NOT NULL AUTO_INCREMENT,
            title varchar(255) DEFAULT NULL,
            rating varchar(255) DEFAULT NULL,
            director varchar(255) DEFAULT NULL,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            deleted_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
        ) $charset;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $movies_sql );
		}

		public function insert_movies() {
			global $wpdb;
			$table = $wpdb->prefix . "movies";

			foreach ( $this->movies_data() as $data ) {

				$wpdb->insert( $table, $data );
			}

		}

		public function movies_data() {
			$time = time();

			return array(
				array(
					'ID'         => 1,
					'title'      => '300',
					'rating'     => 'R',
					'director'   => 'Zach Snyder',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 2,
					'title'      => 'Eyes Wide Shut',
					'rating'     => 'R',
					'director'   => 'Stanley Kubrick',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 3,
					'title'      => 'Moulin Rouge!',
					'rating'     => 'PG-13',
					'director'   => 'Baz Luhrman',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 4,
					'title'      => 'Snow White',
					'rating'     => 'G',
					'director'   => 'Walt Disney',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 5,
					'title'      => 'Super 8',
					'rating'     => 'PG-13',
					'director'   => 'JJ Abrams',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 6,
					'title'      => 'The Fountain',
					'rating'     => 'PG-13',
					'director'   => 'Darren Aronofsky',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 7,
					'title'      => 'Watchmen',
					'rating'     => 'R',
					'director'   => 'Zach Snyder',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 8,
					'title'      => '2001',
					'rating'     => 'G',
					'director'   => 'Stanley Kubrick',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 9,
					'title'      => 'The Shawshank Redemption',
					'rating'     => 'A',
					'director'   => 'Frank Darabont',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 10,
					'title'      => 'The Godfather',
					'rating'     => 'A',
					'director'   => 'Francis Ford Coppola',
					'created_at' => $time,
					'updated_at' => $time,
				),
				array(
					'ID'         => 11,
					'title'      => 'The Dark Knight',
					'rating'     => 'A',
					'director'   => 'Christopher Nolan',
					'created_at' => $time,
					'updated_at' => $time,
				),
			);
		}
	}

endif;

Movie_List_Table_Activation::init();

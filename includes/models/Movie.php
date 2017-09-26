<?php

class Movie {

	private $db;
	private $table;

	protected $data = [
		'ID'         => 0,
		'title'      => '',
		'rating'     => '',
		'director'   => '',
		'created_at' => '',
		'updated_at' => '',
		'deleted_at' => '',
	];

	/**
	 * Movie constructor.
	 *
	 * @param array $data
	 */
	public function __construct( $data = [] ) {
		global $wpdb;

		$this->db    = $wpdb;
		$this->table = $this->db->prefix . "movies";

		if ( $data && is_object( $data ) ) {
			$this->data = $data;
		}
	}

	/**
	 * @param $key
	 *
	 * @return null
	 */
	public function __get( $key ) {
		if ( isset( $this->data->{$key} ) ) {
			return $this->data->{$key};
		}

		return null;
	}

	/**
	 * Fetch a single movie from database
	 *
	 * @param int $id
	 *
	 * @return Movie
	 */
	public function get_movie( $id = 0 ) {
		$movie = $this->db->get_row(
			$this->db->prepare(
				"SELECT * FROM $this->table WHERE id = %d",
				$id
			)
		);

		return new Movie( $movie );
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_movies( $args = [] ) {
		$orderby  = isset( $args['orderby'] ) ? $args['orderby'] : 'ID';
		$order    = isset( $args['order'] ) ? $args['order'] : 'desc';
		$offset   = isset( $args['offset'] ) ? intval( $args['offset'] ) : 0;
		$per_page = isset( $args['per_page'] ) ? intval( $args['per_page'] ) : 5;

		$items = $this->db->get_results( "
                SELECT * FROM $this->table
                ORDER BY $orderby $order
                LIMIT $per_page
                OFFSET $offset
            " );

		$movies = [];

		if ( is_array( $items ) ) {
			foreach ( $items as $item ) {
				$movies[] = new Movie( $item );
			}
		}

		return $movies;
	}

	/**
	 * @param array $args
	 * @param $text
	 *
	 * @return array
	 */
	public function search_movies( $args = [], $text ) {
		$orderby  = isset( $args['orderby'] ) ? $args['orderby'] : 'ID';
		$order    = isset( $args['order'] ) ? $args['order'] : 'desc';
		$offset   = isset( $args['offset'] ) ? intval( $args['offset'] ) : 0;
		$per_page = isset( $args['per_page'] ) ? intval( $args['per_page'] ) : 5;

		$items = $this->db->get_results( $this->db->prepare( "
                    SELECT * FROM $this->table
                    WHERE
                        ID LIKE '%%%s%%'
                        OR title LIKE '%%%s%%'
                        OR rating LIKE '%%%s%%'
                        OR director LIKE '%%%s%%'
                    ORDER BY $orderby $order
                    LIMIT $per_page
                    OFFSET $offset
                ", $text, $text, $text, $text ) );

		$movies = [];

		if ( is_array( $items ) ) {
			foreach ( $items as $item ) {
				$movies[] = new Movie( $item );
			}
		}

		return $movies;
	}

	/**
	 * Get number of total movie from database
	 *
	 * @return int
	 */
	public function count_movies() {
		return (int) $this->db->get_var( "SELECT COUNT(*) FROM $this->table" );
	}


	/**
	 * @return int
	 */
	public function get_id() {
		if ( isset( $this->data->ID ) ) {
			return absint( $this->data->ID );
		}

		return 0;
	}


	/**
	 * @return null|string
	 */
	public function get_title() {
		if ( isset( $this->data->title ) ) {
			return esc_attr( $this->data->title );
		}

		return null;
	}


	/**
	 * @return null|string
	 */
	public function get_rating() {
		if ( isset( $this->data->rating ) ) {
			return esc_attr( $this->data->rating );
		}

		return null;
	}


	/**
	 * @return null|string
	 */
	public function get_director() {
		if ( isset( $this->data->director ) ) {
			return esc_attr( $this->data->director );
		}

		return null;
	}
}
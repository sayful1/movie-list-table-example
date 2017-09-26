<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The WP_List_Table class isn't automatically available to plugins,
 * So we need to check if it's available and load it if necessary.
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a package class
 *
 * Create a new list table package that extends the core WP_List_Table class.
 * Our theme for this list table is going to be movies.
 */
final class Movie_List_Table extends WP_List_Table {

	/** ************************************************************************
	 * REQUIRED. Set up a constructor that references the parent constructor. We
	 * use the parent reference to set some default configs.
	 ***************************************************************************/
	function __construct() {
		global $status, $page;

		//Set parent defaults
		parent::__construct( array(
			'singular' => 'movie',     //singular name of the listed records
			'plural'   => 'movies',    //plural name of the listed records
			'ajax'     => false        //does this table support ajax?
		) );

	}

	/**
	 * Message to show if no designation found
	 *
	 * @return void
	 */
	function no_items() {
		_e( 'No movie found', 'textdomain' );
	}


	/**
	 * REQUIRED! This method dictates the table's columns and titles. This should
	 * return an array where the key is the column slug (and class) and the value
	 * is the column's title text. If you need a checkbox for bulk actions, refer
	 * to the $columns array below.
	 *
	 * @see WP_List_Table::::single_row_columns()
	 * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
	 */
	function get_columns() {
		$columns = array(
			'cb'       => '<input type="checkbox"/>',
			'title'    => __( 'Title', 'textdomain' ),
			'rating'   => __( 'Rating', 'textdomain' ),
			'director' => __( 'Director', 'textdomain' ),
		);

		return $columns;
	}


	/** ************************************************************************
	 * Optional. If you want one or more columns to be sortable (ASC/DESC toggle),
	 * you will need to register it here. This should return an array where the
	 * key is the column that needs to be sortable, and the value is db column to
	 * sort by. Often, the key and value will be the same, but this is not always
	 * the case (as the value is a column name from the database, not the list table).
	 *
	 * This method merely defines which columns should be sortable and makes them
	 * clickable - it does not handle the actual sorting. You still need to detect
	 * the ORDERBY and ORDER querystring variables within prepare_items() and sort
	 * your data accordingly (usually by modifying your query).
	 *
	 * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
	 **************************************************************************/
	function get_sortable_columns() {
		$sortable_columns = array(
			'title'    => array( 'title', false ),     //true means it's already sorted
			'rating'   => array( 'rating', false ),
			'director' => array( 'director', false )
		);

		return $sortable_columns;
	}


	/**
	 * This is method that is used to render a column when
	 * no other specific method exists for that column.
	 *
	 * Custom columns must be provided by the developer and
	 * can be used to handle each type column individually.
	 * For example: if a method named column_title() were provided, it
	 * would be used to render any column that had the slug "title".
	 * This function accepts one argument - a single $item object.
	 *
	 * @param object $item A singular item (one full row's worth of data)
	 * @param string $column_name The name/slug of the column to be processed
	 *
	 * @return string Text or HTML to be placed inside the column <td>
	 */
	function column_default( $item, $column_name ) {
		return isset( $item->{$column_name} ) ? $item->{$column_name} : '';
	}


	/**
	 * This is a custom column method and is responsible for what
	 * is rendered in any column with a name/slug of 'title'. Every time the class
	 * needs to render a column, it first looks for a method named
	 * column_{$column_title} - if it exists, that method is run. If it doesn't
	 * exist, column_default() is called instead.
	 *
	 * This example also illustrates how to implement rollover actions. Actions
	 * should be an associative array formatted as 'slug'=>'link html' - and you
	 * will need to generate the URLs yourself. You could even ensure the links
	 *
	 *
	 * @see WP_List_Table::::single_row_columns()
	 *
	 * @param Movie $item A singular item (one full row's worth of data)
	 *
	 * @return string Text to be placed inside the column <td> (movie title only)
	 */
	public function column_title( $item ) {

		//Build row actions
		$actions = array(
			'edit'   => sprintf( '<a href="?page=%1$s&action=edit&%4$s=%2$s">%3$s</a>', $_REQUEST['page'],
				$item->get_id(),
				__( 'Edit', 'textdomain' ), $this->_args['singular'] ),
			'delete' => sprintf( '<a href="?page=%1$s&action=delete&%4$s=%2$s">%3$s</a>', $_REQUEST['page'],
				$item->get_id(),
				__( 'Delete', 'textdomain' ), $this->_args['singular'] )
		);

		//Return the title contents
		return sprintf( '<strong>%1$s</strong> %3$s',
			$item->get_title(),
			$item->get_id(),
			$this->row_actions( $actions )
		);
	}


	/** ************************************************************************
	 * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
	 * is given special treatment when columns are processed. It ALWAYS needs to
	 * have it's own method.
	 *
	 * @see WP_List_Table::::single_row_columns()
	 *
	 * @param Movie $item A singular item (one full row's worth of data)
	 *
	 * @return string Text to be placed inside the column <td> (movie title only)
	 **************************************************************************/
	public function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
			$item->get_id() //The value of the checkbox should be the record's id
		);
	}

	/**
	 * @param Movie $item
	 *
	 * @return null|string
	 */
	public function column_rating( $item ) {
		return $item->get_rating();
	}

	/**
	 * @param Movie $item
	 *
	 * @return null|string
	 */
	public function column_director( $item ) {
		return $item->get_director();
	}


	/** ************************************************************************
	 * Optional. If you need to include bulk actions in your list table, this is
	 * the place to define them. Bulk actions are an associative array in the format
	 * 'slug'=>'Visible Title'
	 *
	 * If this method returns an empty value, no bulk action will be rendered. If
	 * you specify any bulk actions, the bulk actions box will be rendered with
	 * the table automatically on display().
	 *
	 * Also note that list tables are not automatically wrapped in <form> elements,
	 * so you will need to create those manually in order for bulk actions to function.
	 *
	 * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
	 **************************************************************************/
	function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Delete', 'textdomain' )
		);

		return $actions;
	}


	/** ************************************************************************
	 * REQUIRED! This is where you prepare your data for display. This method will
	 * usually be used to query the database, sort and filter the data, and generally
	 * get it ready to be displayed. At a minimum, we should set $this->items and
	 * $this->set_pagination_args(), although the following properties and methods
	 * are frequently interacted with here...
	 *
	 * @global WPDB $wpdb
	 * @uses $this->_column_headers
	 * @uses $this->items
	 * @uses $this->get_columns()
	 * @uses $this->get_sortable_columns()
	 * @uses $this->get_pagenum()
	 * @uses $this->set_pagination_args()
	 **************************************************************************/
	function prepare_items( $search = null ) {

		/**
		 * REQUIRED. Now we need to define our column headers. This includes a complete
		 * array of columns to be displayed (slugs & titles), a list of columns
		 * to keep hidden, and a list of columns that are sortable.
		 *
		 * Finally, we build an array to be used by the class for column headers.
		 */
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		/**
		 * Optional. You can handle your bulk actions however you see fit. In this
		 * case, we'll handle them within our package just to keep things clean.
		 */
		$this->process_bulk_action();

		// First, lets decide how many records per page to show
		$per_page = $this->get_items_per_page( 'movies_per_page', 5 );
		// What page the user is currently looking at
		$current_page = $this->get_pagenum();

		$args = array(
			'orderby'  => ! empty( $_REQUEST['orderby'] ) ? $_REQUEST['orderby'] : 'ID',
			'order'    => ! empty( $_REQUEST['order'] ) ? $_REQUEST['order'] : 'desc',
			'offset'   => ( $current_page - 1 ) * $per_page,
			'per_page' => $per_page,
		);

		/**
		 * REQUIRED. Now we can add our *sorted* data to the items property, where
		 * it can be used by the rest of the class.
		 */
		$this->items = $this->get_movies( $args, $search );

		// Total number of items
		$total_items = $this->count_movies();

		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page )
		) );
	}

	/**
	 * Get all movies data from movies database table
	 *
	 * @return Movie
	 */
	public function get_movies( array $args, $search = null ) {
		$cache_key   = sprintf( 'all-%s', $this->_args["plural"] );
		$cache_group = sprintf( 'all-%s-group', $this->_args["plural"] );

		$items = wp_cache_get( $cache_key, $cache_group );

		if ( false === $items ) {

			$movie = new Movie();

			if ( $search !== null ) {
				$items = $movie->search_movies( $args, $search );
			} else {
				$items = $movie->get_movies( $args );
			}

			wp_cache_set( $cache_key, $items, $cache_group );
		}

		return $items;
	}

	/**
	 * Get number of total movie from database
	 *
	 * @return int
	 */
	public function count_movies() {
		global $wpdb;
		$table = $wpdb->prefix . "movies";

		return (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
	}

	/** ************************************************************************
	 * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
	 * For this example package, we will handle it in the class to keep things
	 * clean and organized.
	 *
	 * @see $this->prepare_items()
	 **************************************************************************/
	function process_bulk_action() {

		global $wpdb;
		$table = $wpdb->prefix . "movies";

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			$movie = isset( $_REQUEST[ $this->_args['singular'] ] ) ? $_REQUEST[ $this->_args['singular'] ] : null;

			if ( $movie ) {
				if ( is_array( $movie ) ) {
					foreach ( $movie as $id ) {
						$wpdb->delete( $table, array( 'ID' => intval( $id ) ), array( '%d' ) );
					}
				} else {
					$wpdb->delete( $table, array( 'ID' => intval( $movie ) ), array( '%d' ) );
				}
			}
		}
	}
}

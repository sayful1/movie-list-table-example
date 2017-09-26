<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Movie_Form_Handler {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'handle_form' ) );
    }

    public function handle_form()
    {
        if ( ! isset( $_POST['_wpnonce'] ) ) return;
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'movie_nonce_field' ) ) return;
        if ( ! current_user_can( 'manage_options' ) ) return;

        $errors   	= array();
        $page_url 	= admin_url( 'admin.php?page=movies' );
        $field_id 	= isset( $_POST['movie_id'] ) ? intval( $_POST['movie_id'] ) : 0;

        $title 		= isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
        $rating 	= isset( $_POST['rating'] ) ? sanitize_text_field( $_POST['rating'] ) : '';
        $director 	= isset( $_POST['director'] ) ? sanitize_text_field( $_POST['director'] ) : '';

        // some basic validation
        if ( ! $title ) {
            $errors[] = __( 'Error: Name is required', 'textdomain' );
        }
        if ( ! $director ) {
            $errors[] = __( 'Error: Director is required', 'textdomain' );
        }
        if ( ! $rating ) {
            $errors[] = __( 'Error: Rating is required', 'textdomain' );
        }

        // bail out if error found
        if ( $errors ) {
            $first_error = reset( $errors );
            $redirect_to = add_query_arg( array( 'error' => urlencode($first_error) ), $page_url );
            wp_safe_redirect( $redirect_to );
            exit;
        }

        $fields = array(
            'title'     => $title,
            'rating'    => $rating,
            'director'  => $director,
        );

        if ( ! $field_id ) {

            // Add new movie
            $insert_id = $this->insert_movie( $fields );
            $success[] = __( 'New record has been created.', 'textdomain' );

        } else {

            // Update existing movie
            $insert_id = $this->insert_movie( $fields, $field_id );
            $success[] = __( 'Record has been updated.', 'textdomain' );
        }

        $redirect_to = $page_url;

        if( $success ){
            $success_success = reset( $success );
            $redirect_to = add_query_arg( array( 'success' => urlencode($success_success) ), $page_url );
        }
        wp_safe_redirect( $redirect_to );
        exit;
    }

    private function insert_movie( array $fields, $id = null )
    {
        global $wpdb;
        $table = $wpdb->prefix . "movies";

        if ( $id ) {
            // Update data
            if ( $wpdb->update( $table, $fields, array( 'ID' => $id ) ) ) {
                return $id;
            }
        } else {
            // Insert new row
            if ( $wpdb->insert( $table, $fields ) ) {
                return $wpdb->insert_id;
            }
        }
    }
}
<?php

$movie = ( new Movie() )->get_movie( $id );

?>
<div class="wrap">
    <h2><?php _e( 'Edit Movie', 'textdomain' ); ?></h2>

    <form method="POST" accept-charset="UTF-8" autocomplete="off">

        <table class="form-table">

            <tr>
                <th scope="row">
                    <label for="title"><?php _e( 'Name', 'textdomain' ); ?></label>
                </th>
                <td>
                    <input type="text" name="title" id="title" class="regular-text"
                           placeholder="<?php echo esc_attr( 'Movie Name', 'textdomain' ); ?>"
                           value="<?php echo esc_attr( $movie->get_title() ); ?>" required="required"/>
                    <p class="description"><?php _e( 'Enter movie full name here.', 'textdomain' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="rating"><?php _e( 'Rating', 'textdomain' ); ?></label>
                </th>
                <td>
                    <input type="text" name="rating" id="rating" class="regular-text"
                           placeholder="<?php echo esc_attr( 'Rating', 'textdomain' ); ?>"
                           value="<?php echo esc_attr( $movie->get_rating() ); ?>" required="required"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="director"><?php _e( 'Director', 'textdomain' ); ?></label>
                </th>
                <td>
                    <input type="text" name="director" id="director" class="regular-text"
                           placeholder="<?php echo esc_attr( 'Director', 'textdomain' ); ?>"
                           value="<?php echo esc_attr( $movie->get_director() ); ?>" required="required"/>
                </td>
            </tr>

        </table>

        <input type="hidden" name="movie_id" value="<?php echo $movie->get_id(); ?>">
		<?php wp_nonce_field( 'movie_nonce_field' ); ?>
		<?php submit_button( __( 'Update Movie', 'textdomain' ), 'primary', 'submit_distributor' ); ?>
    </form>
</div>
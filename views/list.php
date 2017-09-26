<div class="wrap">
    
    <h1 class="wp-heading-inline">
        <?php echo __( 'Movies', 'textdomain' ); ?>
    </h1>
    <a href="?page=add_movie" class="page-title-action">Add New</a>
    <hr class="wp-header-end">

    <!-- Show error message if any -->
    <?php if (array_key_exists('error', $_GET)): ?>
        <div class="notice notice-error is-dismissible"><p><?php echo $_GET['error']; ?></p></div>
    <?php endif; ?>

    <!-- Show success message if any -->
    <?php if (array_key_exists('success', $_GET)): ?>
        <div class="notice notice-success is-dismissible"><p><?php echo $_GET['success']; ?></p></div>
    <?php endif; ?>
    
    <form id="movies-filter" method="get" autocomplete="off" accept-charset="utf-8">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php
            //Create an instance of our package class...
            $movieListTable = new Movie_List_Table();
            // Check if any search result
            $search = isset($_GET['s']) ? $_GET['s'] : null;
            //Fetch, prepare, sort, and filter our data...
            $movieListTable->prepare_items( $search );
            // Show search form
            $movieListTable->search_box( __('Search Movie'), 'movie' );
            // Display table with data
            $movieListTable->display();
        ?>
    </form>
    
</div>
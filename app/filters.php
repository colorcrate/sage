<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Custom block categories
 */
add_filter( 'block_categories_all' , function( $categories ) {
    // WordPress orders the items in the listing based on their position
    // in the array, so if we want to be at the front of the list we
    // want to 'unshift' to put our categories at the start.
    array_unshift( $categories,
        array(
            'slug'  => 'cc-header',
            'title' => 'CC Headers'
        ),
        array(
            'slug'  => 'cc-body',
            'title' => 'CC Body Blocks'
        )
    );
    return $categories;
});

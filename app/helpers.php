<?php
/**
 *  Friendly PHP HELPERS
 *  @version 0.0.2
 *
 *  This file contains various useful PHP functions to help you perform common
 *  tasks. Within Blade templates you can access these functions thusly:
 *
 *    <?php \App\format_url('foo bar baz'); ?>
 *
 *  and within Composers you can access these functions similarly:
 *
 *    $url = \App\format_url('foo bar baz');
 */

namespace App;

use ReflectionClass;


/**
 * Access Protected Object Values
 * @since 0.0.1
 *
 * Accepts an object with protected properties, returns the value for the selected property.
 *
 * @param object $obj The protected object
 * @param string $prop The object property key
 */
function access_protected_object_value( $obj, $prop ) {
  $reflection = new ReflectionClass($obj);
  $property = $reflection->getProperty($prop);
  $property->setAccessible(true);
  return $property->getValue($obj);
}


/**
 *  in_array, but recursive
 *  @since 0.0.0
 *
 *  @param $needle - the thing to find in the array
 *
 *  @return $haystack - the array to search
 */
function in_array_recursive( $needle, $haystack ) {
  $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($haystack));
  foreach($it AS $element) {
    if($element == $needle) {
      return true;
    }
  }
  return false;
}

/**
 *  Make a string URL safe
 *  @since 0.0.0
 *
 *  @param $str - the string to make URL safe
 *  @param $sep - the separater to use in place of spaces in the string
 *
 *  @return string
 */
function format_url( $str, $sep='-' ) {
  $res = strtolower($str);
  $res = preg_replace('/[^[:alnum:]]/', ' ', $res);
  $res = preg_replace('/[[:space:]]+/', $sep, $res);
  return trim($res, $sep);
}

/**
 *  Excerpt string
 *  @since 0.0.0
 *
 *  Takes a string of text and converts it to an excerpt format.
 *
 *  @param $str - the string to modify and return as an excerpt
 *
 *  @return string
 */
function excerpt_str( $str ) {
  $excerpt_length = 22; // 22 words
  $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
  $excerpt = strip_shortcodes( $str );
  $excerpt = apply_filters('the_content', $excerpt);
  $excerpt = str_replace(']]>', ']]>', $excerpt);
  $excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
  $excerpt = rtrim( $excerpt, ",.;:- _!$&#" );
  return $excerpt . '&hellip;';
}

/**
 * Get Featured Image Data
 * @since 0.0.2
 *
 * Accepts a post ID and returns an array of image data similar to an ACF image field.
 *
 * @param int $post_id the ID of the post
 *
 * @return array of image data
 */
function get_featured_image_data($post_id) {
  $thumbnail_id = get_post_thumbnail_id($post_id);
  return get_image_data_by_id($thumbnail_id);
}

/**
 *  Get Highest Parent Term
 *  @since 0.0.0
 *
 *  Accepts a taxonomy term object or ID and returns the top-level term parent.
 *
 *  @param $term - WP Term object, or term ID
 *
 *  @return WP post object - highest level term parent
 */
function get_highest_parent_term( $term ) {
  $id = false;
  $term_obj = false;

  if ( isset( $term ) ) {
    if ( is_object( $term ) ) {
      $id = $term->term_id;
      $term_obj = $term;
    } else if ( is_numeric( $term ) ) {
      $id = $term;
      $term_obj = get_term( $id );
    } else {
      return false; // $term is not an object or number
    }
  } else {
    return false; // $term is not set
  }

  // Climb up the hierarchy until we reach a term with parent = '0'
  $parent = get_term( $id );
  while ( $parent->parent != '0' ) {
    $term_id = $parent->parent;
    $parent = get_term( $term_id );
  }
  return $parent;
}

/**
 * Get Image Data by ID
 * @since 0.0.2
 *
 * Accepts an image ID and returns an array of image data similar to an ACF image field.
 *
 * @param int $image_id the ID of the image
 *
 * @return array of image data
 */
function get_image_data_by_id( $image_id ) {
  if (!$image_id) { return false; }

  $image_data = wp_get_attachment_metadata($image_id);
  if (!$image_data) { return false; }

  $image_url = wp_get_attachment_url($image_id);
  $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
  $image_title = get_the_title($image_id);
  $image_caption = wp_get_attachment_caption($image_id);
  $image_description = get_post($image_id)->post_content;

  // Prepare the array similar to ACF image field
  $image_array = [
    'ID'            => $image_id,
    'url'           => $image_url,
    'alt'           => $image_alt,
    'title'         => $image_title,
    'caption'       => $image_caption,
    'description'   => $image_description,
    'width'         => $image_data['width'],
    'height'        => $image_data['height'],
    'sizes'         => [],
  ];

  // Add sizes to the array
  foreach ($image_data['sizes'] as $size => $size_data) {
    $image_array['sizes'][$size] = [
      'url'    => wp_get_attachment_image_url($image_id, $size),
      'width'  => $size_data['width'],
      'height' => $size_data['height'],
    ];
  }

  return $image_array;
}

/**
 * Responsive Image Sources
 * @since 0.0.0
 *
 * Accepts an image ID and returns a responsive img src, srcset, and sizes
 * attribute as a string.
 *
 * @param string $image_id the id of the image (from ACF or similar)
 * @param string $image_size (default 'medium') the size of the thumbnail image or custom image size
 * @param string $max_width (default '1600px') the max width this image will be shown to build the sizes attribute
 *
 * @return string eg. 'src="..." srcset="..." sizes="..."'
 */

function responsive_image_src( $image_id, $image_size = 'medium', $max_width = '1600px' ) {
	if ( $image_id != '' ) {
		// set the default src image size
		$image_src = wp_get_attachment_image_url( $image_id, $image_size );
		// set the srcset with various image sizes
		$image_srcset = wp_get_attachment_image_srcset( $image_id, $image_size );
		// generate the markup for the responsive image
		return 'src="'.$image_src.'" srcset="'.$image_srcset.'" sizes="(max-width: '.$max_width.') 100vw, '.$max_width.'"';
	} else {
    return false;
  }
}

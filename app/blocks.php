<?php

/**
 * Block registration
 */

namespace App;

add_action('acf/init', function () {
  if (function_exists('acf_register_block_type')) {

    acf_register_block_type([
      'name' => 'example-block',
      'title' => __('Example Block'),
      'description' => __('A custom example block.'),
      'render_callback' => 'sage_acf_block_render_callback',
      'category' => 'formatting',
      'icon' => 'admin-comments',
      'keywords' => ['example', 'custom'],
    ]);

  }
});

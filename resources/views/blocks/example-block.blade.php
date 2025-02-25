<?php
  /**
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during backend preview render.
   * @param   int $post_id The post ID the block is rendering content against.
   *          This is either the post ID currently being displayed inside a query loop,
   *          or the post ID of the post hosting this block.
   * @param   array $context The context provided to the block by the post or it's parent block.
   */

   // Support custom "anchor" values.
  $anchor = '';
  if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $class_name = 'cc-block cc-block--example-block cc-example-block';
  if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
  }

  // Get block options and set publish status and padding
  $options = get_field('options');
  $options = array_filter($options, function ($key) {
    return !empty($key); // Remove empty keys
  }, ARRAY_FILTER_USE_KEY);
  if ( is_array( $options ) && ! empty( $options ) ) {
    if ( isset( $options['status'] ) ) {
      $class_name .= ' cc-block--' . $options['status'];
    }
    if ( isset( $options['vertical_spacing']['top_padding'] ) ) {
      $class_name .= " cc-block--top-padding--{$options['vertical_spacing']['top_padding']}";
    }
    if ( isset( $options['vertical_spacing']['bottom_padding'] ) ) {
      $class_name .= " cc-block--bottom-padding--{$options['vertical_spacing']['bottom_padding']}";
    }
  }

  // Get block fields
  $heading = get_field('heading');
?>

<?php if ( is_admin() || $options['status'] === 'publish' ) : ?>
  <div <?php echo $anchor; ?>class="<?php echo esc_attr( $class_name ); ?>">
    <div class="cc-example-block__container">
      <h1>{{ !empty($heading) ? $heading : 'Placeholder' }}</h1>
    </div>
  </div>
<?php endif; ?>

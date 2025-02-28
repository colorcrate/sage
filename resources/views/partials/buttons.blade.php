<?php
/**
 *  Buttons
 *
 *  Displays buttons that come from the 'Clone source / Buttons' ACF. Called thusly:
 *  @include('partials.buttons', [ buttons' => array() ])
 *
 *  @param  $buttons - array - From app/View/Composers/App.php
 */
?>
@if ( ! empty( $buttons ) )
  @foreach ( $buttons as $button )
    @if ( ! empty( $button['link'] ) )
      <a
        class="cc-button cc-button--<?php echo !empty( $button['type'] ) ? $button['type'] : 'primary'; ?> cc-button--<?php echo !empty( $button['size'] ) ? $button['size'] : 'm'; ?>"
        href="<?php echo !empty( $button['link']['url'] ) ? $button['link']['url'] : ''; ?>"
        target="<?php echo !empty( $button['link']['target'] ) ? $button['link']['target'] : ''; ?>"
      >{!! $button['link']['title'] !!}</a>
    @endif
  @endforeach
@endif

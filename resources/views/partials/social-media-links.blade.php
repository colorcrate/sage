<?php
/**
 *  Social Media Links
 *
 *  Displays social media links in a list. Called thusly:
 *  @include('partials.social-media-links', [ 'class' => 'optional-class-name', 'socialMediaLinks' => array() ])
 *
 *  @param  $class - string - Optional, passed from the @include (see above)
 *  @param  $socialMediaLinks - array - From app/View/Composers/App.php
 */
?>

<ul class="social-media-links {!! isset( $class ) ? $class : '' !!}">
  @if ( $social_media_links )
    @foreach ( $social_media_links as $link )
      <li class="social-media-links__item {!! isset( $class ) ? $class . '__item' : '' !!}">
        <a class="social-media-links__link {!! isset( $class ) ? $class . '__link' : '' !!}" href="{!! $link['url'] !!}" data-service="{!! $link['service'] !!}">{!! $link['service'] !!}</a>
      </li>
    @endforeach
  @endif
</ul>

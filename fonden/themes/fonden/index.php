<?php 
get_header();

if ( have_posts() ) {
  while ( have_posts() ) {
    the_post();

    get_template_part( 'sections/introduction' );

    if ( is_front_page() ) {

    } elseif ( is_page() ) {

    } else {
      // 404...
    }
    
    echo '<script type="application/ld+json">'
      . json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
      . '</script>';
  }
}

get_footer();


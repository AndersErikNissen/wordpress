<?php 
get_header();

if ( have_posts() ) {
  while ( have_posts() ) {
    the_post();

    get_template_part( 'sections/introduction' );

    if ( is_front_page() ) {
      get_template_part( 'sections/sequence' );
      get_template_part( 'sections/cta' );

    } elseif ( is_page() ) {
      get_template_part( 'sections/text' );
      get_template_part( 'sections/cards' );
      get_template_part( 'sections/form' );
      get_template_part( 'sections/faq' );
      get_template_part( 'sections/people' );

    } elseif ( is_page() ) {

    } else {
      // 404...
    }
  }
}

get_footer();


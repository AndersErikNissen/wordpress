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
      $slug = get_post_field( 'post_name', get_queried_object_id() );

      switch( $slug ) {
        case 'kontakt':  
        case 'kontakt-os':
          get_template_part( 'sections/people' );
          get_template_part( 'sections/faq' );
          break;

        default:
      }
      
    } elseif ( is_page() ) {

    } else {
      // 404...
    }
  }
}

get_footer();


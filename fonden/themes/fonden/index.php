<?php 
get_header();

if ( have_posts() ) {
  while ( have_posts() ) {
    the_post();

    if ( is_front_page() ) {

    } elseif ( is_page() ) {

    } else {
      // 404...
    }
  }
}

get_footer();


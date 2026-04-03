<?php
use Aenother\Modules\OptionPage\OptionPage;

// Admin
add_action( 'admin_init', function() {
  remove_post_type_support( 'page', 'editor' );
  remove_post_type_support( 'post', 'editor' );
} );

add_action( 'after_setup_theme', function() {
  add_theme_support (
    'html5',
    array (
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'script',
      'style',
      'navigation-widgets',
    )
  );

  add_theme_support( 'title-tag' ); // Adds <title> to <head>

  add_image_size( 'phone',         480 );
  add_image_size( 'phone-tablet',  768 );
  add_image_size( 'tablet-laptop', 1024 );
  add_image_size( 'laptop',        1440 );
  add_image_size( 'xlarge',        1920 );

  add_image_size( 'schema_1x1',  1200, 1200, true );
  add_image_size( 'schema_4x3',  1200, 900,  true );
  add_image_size( 'schema_16x9', 1200, 675,  true );
} );

// Helpers  
function theme_schema( array $types = [] ):array {
  $schema = [];
  
  foreach ( $types as $type ) {
    switch( $type ) {
      case 'address':
        $address = OptionPage::get( 'address' );
        if ( $address ) {
          $schema[] = [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $address['street']      ?? '',
            'addressLocality' => $address['city']        ?? '',
            'addressRegion'   => $address['region']      ?? '',
            'postalCode'      => $address['postal_code'] ?? '',
            'addressCountry'  => $address['country']     ?? '',
          ];
        }
        break; 
      
      case 'organization':
        break;
    }
  }


  // $organization = [
  //   '@type' => 'Organization',
  //   'name'  => sts_option( 'company.name' ),
  //   'url'   => home_url(),
  // ];

  // $images = array_filter( [
  //   wp_get_attachment_image_url( $image_id, 'schema_1x1'  ),
  //   wp_get_attachment_image_url( $image_id, 'schema_4x3'  ),
  //   wp_get_attachment_image_url( $image_id, 'schema_16x9' ),
  // ] );

  // // bread crumbs

  // $website = [
  //   '@type' => 'WebSite',
  //   '@id'   => home_url( '/#website' ),
  //   'name'  => sts_option( 'company.name' ),
  //   'url'   => home_url(),
  // ];

  return $schema;
}

add_action( 'init', function() {
    // Now it is safe to call your schema function
    $my_schema = theme_schema( ['address'] );
    // ... do something with it, like filter it into your head
    print_r($my_schema);
});

// Injects
add_action( 'get_footer', function() {
  wp_enqueue_script( 'index', get_theme_file_uri( 'index.js' ), array(), "1.0", TRUE );
} );
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

// Schema.org
add_action( 'wp_head', function() {
  // Global schema
  $company = OptionPage::get( 'company' );
  $contact = OptionPage::get( 'contact' );

  $website = [
    '@type'       => 'WebSite',
    '@id'         => home_url( '/#website' ),
    'url'         => home_url(),
    'name'        => get_bloginfo( 'name' ),
    'description' => get_bloginfo( 'description' ),
    'inLanguage'  => 'da',
    'publisher'   => [ '@id' => home_url( '/#organization' ) ]
  ];

  $organization = [
    '@type'       => [ 'NGO', 'FundingAgency' ],
    '@id'         => home_url( '/#organization' ),
    'url'         => home_url(),
    'name'        => get_bloginfo( 'name' ),
    'description' => get_bloginfo( 'description' ),
  ];

  if ( $company ) {
    $email = $contact['email'] ?? null;
    if ( $email ) $organization['email'] = $email;
  
    $founding_date = $company['founding_date'] ?? null;
    if ( $founding_date ) $organization['foundingDate'] = $founding_date;
  }

  // Page schema
  $slug = get_post_field( 'post_name', get_queried_object_id() );

  $page_type = match( $slug ) {
    'om-fonden',  'om-os'   => 'AboutPage',
    'kontakt-os', 'kontakt' => 'ContactPage',
    default                 => 'WebPage',
  };

  $canonical = is_front_page() ? home_url( '/' ) : get_permalink( get_queried_object_id() );

  $page = [
    '@type'       => is_front_page() ? 'WebPage' : $page_type,
    '@id'         => $canonical . '#webpage',
    'url'         => $canonical,
    'name'        => get_the_title( get_queried_object_id() ) . ' — ' . get_bloginfo( 'name' ),
    'isPartOf'    => [ '@id' => home_url( '/#website' ) ],
    'about'       => [ '@id' => home_url( '/#organization' ) ],
    'publisher'   => [ '@id' => home_url( '/#organization' ) ],
    'inLanguage'  => 'da',
  ];

  // Output the schema
  $graph = [
    '@context' => 'https://schema.org',
    '@graph'   => [ $organization, $website, $page ]
  ];

  echo '<script type="application/ld+json">'
      . json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
      . '</script>';
} );

// Enqueue
add_action( 'wp_enqueue_scripts', function() {
  wp_enqueue_style( 
    'style', 
    get_theme_file_uri() . '/style.css',
    [],
    wp_get_theme()->get( 'Version' )
  );
} );

add_action( 'get_footer', function() {
  wp_enqueue_script( 'index', get_theme_file_uri( 'index.js' ), array(), "1.0", TRUE );
} );

// Helpers
function render_acf_img( $img, int $divide_by = 1, string $priority = 'low' ) {
  $id = is_array( $img ) ? $img['ID'] : $img;

   if ( ! wp_attachment_is_image( $id ) ) return;

  $widths = [
    'desktop' => 928, // px
    'handheld'  => 100 // vw
  ];

  $attrs = [
    'sizes'   => '(min-width: 928px) ' . $widths['desktop'] / $divide_by . 'px, ' . $widths['handheld'] / $divide_by . 'vw',
    'loading' => $priority === 'high' ? 'eager' : 'lazy',
  ];

  if ( $priority === 'high' ) {
    $attrs['fetchpriority'] = 'high';
  }

  echo wp_get_attachment_image( $id, 'full', attr: $attrs );
}
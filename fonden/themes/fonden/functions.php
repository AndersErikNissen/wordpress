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
function render_img( $img, array $divisions = [], string $priority = 'low', bool $use_alt = false ) {
  $id = is_array( $img ) ? $img['ID'] : $img;

   if ( ! wp_attachment_is_image( $id ) ) return;

  $divide_by = array_merge( [
    'desktop'  => 1,
    'handheld' => 1
  ], $divisions );

  $widths = [
    'desktop' => 928, // px
    'handheld'  => 100 // vw
  ];

  $attrs = [
    'sizes'   => '(min-width: 928px) ' . $widths['desktop'] / $divide_by['desktop'] . 'px, ' . $widths['handheld'] / $divide_by['handheld'] . 'vw',
    'loading' => $priority === 'high' ? 'eager' : 'lazy',
  ];

  if ( $priority === 'high' ) {
    $attrs['fetchpriority'] = 'high';
  }

  echo wp_get_attachment_image( $id, 'full', attr: $attrs );

  if ( $img['alt'] && $use_alt ) {
    printf( '<div class="alt"><p>%s</p></div>', $img['alt'] );
  }
}

function render_button( array $link, bool $outlined = false ) { 
  if ( ! $link['url'] || ! $link['title'] ) return;
  
  printf( '<a class="%s" href="%s" target="%s">'
          . '<span class="button__content">'
            . '<span class="button__labels">'
              . '<span class="button__label">%4$s</span><span class="button__label">%4$s</span>'
            . '</span>'
          . '</span>'
        . '</a>',
    'button' . ( $outlined ? ' outlined' : '' ),
    esc_url( $link['url'] ),
    esc_attr( $link['target'] ?: '_self' ),
    esc_html( $link['title'] )
  );
}

function render_icon( string $name = '' ) {
  $icons = [
    'arrow-right'     => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">' .
                           '<path d="M2 15.5008L28.1074 15.5008L17.1484 4.3515L17.5049 4.00104L17.8623 3.65059L30.001 16.0006L17.8623 28.3506L17.5049 28.0001L17.1484 27.6497L28.1074 16.5004L2 16.5004V15.5008Z" fill="currentColor"/>' .
                         '</svg>',
    'arrow-top-right' => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">' .
                           '<path d="M5.74722 25.547L24.208 7.08627L8.57506 6.95172L8.5793 6.45186L8.58422 5.95131L25.9003 6.10074L26.0498 23.4169L25.5492 23.4218L25.0494 23.426L24.9148 7.79312L6.45407 26.2539L5.74722 25.547Z" fill="currentColor"/>' .
                         '</svg>',
    'chevron'         => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">' .
                           '<path d="M28.3506 10.7129L16 22.8486L3.64941 10.7129L4.35059 10L16 21.4463L27.6494 10L28.3506 10.7129Z" fill="currentColor"/>' .
                         '</svg>'
  ];

  $icon = $icons[$name] ?? null;

  if ( $icon ) {
    printf( '<div class="icon">%s</div>', $icon );
  }
}
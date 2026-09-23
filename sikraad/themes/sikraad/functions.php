<?php
use Aenother\Modules\OptionPage\OptionPage;

/*******************************************************************************
 * 
 *  ADMIN
 * 
 ******************************************************************************/

add_filter( 'show_admin_bar', '__return_false' );

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

/*******************************************************************************
 * 
 *  SCHEMA.ORG
 * 
 ******************************************************************************/

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

  /*******************************************************************************
 * 
 *  PAGE SCHEMA
 * 
 ******************************************************************************/

  $page_schema      = get_field( 'schema' );
  $page_type        = $page_schema['page_type']   ?? 'WebPage';
  $page_name        = $page_schema['name']        ?? get_the_title( get_queried_object_id() );
  $page_description = $page_schema['description'] ?? null;

  $canonical = is_front_page() ? home_url( '/' ) : get_permalink( get_queried_object_id() );

  $page = [
    '@type'       => $page_type,
    '@id'         => $canonical . '#webpage',
    'url'         => $canonical,
    'name'        => $page_name . ' — ' . get_bloginfo( 'name' ),
    'isPartOf'    => [ '@id' => home_url( '/#website' ) ],
    'about'       => [ '@id' => home_url( '/#organization' ) ],
    'publisher'   => [ '@id' => home_url( '/#organization' ) ],
    'inLanguage'  => 'da',
  ];

  if ( $page_description ) {
    $page['description'] = $page_description;
  }

  $graph = [
    '@context' => 'https://schema.org',
    '@graph'   => [ $organization, $website, $page ]
  ];

  echo '<script type="application/ld+json">'
      . json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
      . '</script>';
} );

/*******************************************************************************
 * 
 *  ENQUEUE(S)
 * 
 ******************************************************************************/

add_action( 'wp_enqueue_scripts', function() {
  wp_enqueue_style( 
    'style', 
    get_theme_file_uri() . '/style.css',
    [],
    wp_get_theme()->get( 'Version' )
  );
} );

add_action( 'get_footer', function() {
  wp_enqueue_script( 'index', get_theme_file_uri( 'index.js' ), [], "1.0", TRUE );
} );


/*******************************************************************************
 * 
 *  HELPER(S)
 * 
 ******************************************************************************/

function get_icon( string $name ): string {
  $icon = '';
  $paths = [
    'close' => '<path d="M25.5 22.6719L37.4561 10.7158L40.2842 13.5439L28.3281 25.5L40.2842 37.4561L37.4561 40.2842L25.5 28.3281L13.4141 40.4141L10.5859 37.5859L22.6719 25.5L10.5859 13.4141L13.4141 10.5859L25.5 22.6719Z" fill="currentColor"/>',
    'burger' => '<path d="M6 18H44" stroke="currentColor" stroke-width="4"/><path d="M6 33H44" stroke="currentColor" stroke-width="4"/>',
    'arrow-right' => '<path d="M44.8281 25.5L26.9141 43.4141L24.0859 40.5859L37.1719 27.5H6V23.5H37.1719L24.0859 10.4141L26.9141 7.58594L44.8281 25.5Z" fill="currentColor"/>',
    'arrow-up-right' => '<path d="M39.1423 11.7715V37.1058H35.1427V18.5995L13.1009 40.6413L10.2725 37.8129L32.3143 15.7711H13.808V11.7715H39.1423Z" fill="currentColor"/>',
    'arrow-left' => '<path d="M6 25.5L23.9141 7.58594L26.7422 10.4141L13.6563 23.5L44.8281 23.5L44.8281 27.5L13.6563 27.5L26.7422 40.5859L23.9141 43.4141L6 25.5Z" fill="currentColor"/>',
    'chevron' => '<path d="M20 11L30 25.4996L20 40" stroke="currentColor" stroke-width="4"/>',
  ];

  if ( isset( $paths[$name] ) ) {
    $icon = '<span class="icon">' . 
              '<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">' .
                $paths[$name] . 
              '</svg>';    
            '</span>';    
  }

  return $icon;
}

function render_img( array $img, array $divisions = [], string $priority = 'low', bool $use_alt = false ) {
  $id = is_array( $img ) ? $img['ID'] : $img;

   if ( ! wp_attachment_is_image( $id ) ) return;

  $divide_by = array_merge( [
    'desktop' => 1,
    'tablet' => 1,
    'mobile' => 1
  ], $divisions );

  $widths = [
    'desktop' => 1920, // px
    'tablet' => 1024, // px
    'mobile' => 100 // vw
  ];

  $attrs = [
    'sizes' => '(min-width: 1024px) ' . $widths['desktop'] / $divide_by['desktop'] . 'px, (min-width: 480px) ' . $widths['tablet'] / $divide_by['tablet'] . 'px ,' . $widths['mobile'] / $divide_by['mobile'] . 'vw',
    'loading' => $priority === 'high' ? 'eager' : 'lazy',
  ];

  if ( $priority === 'high' ) {
    $attrs['fetchpriority'] = 'high';
  }

  echo wp_get_attachment_image( $id, 'full', attr: $attrs );

  if ( $img['alt'] && $use_alt ) { ?>
    <div class="alt">
      <p>
        <?php echo $img['alt']; ?> 
      </p>
    </div>
  <?php }
}

function render_btn( array $data = [], array $attributes = [], $icon = '' ):void {
  $data = array_merge( [
    'title' => '',
    'url' => null,
    'target' => null
  ], $data );
  
  $tag = $data['url'] ? 'a' : 'button';
  
  if ( $data['url'] ) {
    array_merge( $attributes, [ 'href' => esc_url( $data['url'] ), 'target' => $data['target'] ?? '_self'  ] );
  }
  
  $attributes = array_merge( [ 'class' => 'btn' ], $attributes );
  $attr_string = '';
  foreach ( $attributes as $key => $value ) {
    $attr_string .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
  }

  printf(
    '<%s%s>%s %s</%1$s>',
    $tag,
    $attr_string,
    $data['title'],
    get_icon( $icon ),
  );
}
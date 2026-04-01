<?php
namespace Aenother\Modules\DisableGutenberg;

use Aenother\Modules\BaseModule;

class DisableGutenberg extends BaseModule {

  public function __construct() {
    // This hook only runs on the frontend
    add_action( 'wp_enqueue_scripts', [ $this, 'cleanup_frontend' ], 100 );

    if ( is_admin() ) {
      // Disable the editor
      add_filter( 'use_block_editor_for_post', '__return_false' );
      // Disable the widget block editor
      add_filter( 'use_widgets_block_editor', '__return_false' );
    }
  }

  public function cleanup_frontend() {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' ); // Dequeue WooCommerce block styles if present
    
    // These two are the "stubborn" ones that often need high priority (100)
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
    
    // Remove the inline global styles and SVG filters
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    remove_action( 'wp_body_open',       'wp_global_styles_render_svg_filters' );
  }
}
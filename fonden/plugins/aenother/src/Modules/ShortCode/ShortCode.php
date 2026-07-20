<?php
namespace Aenother\Modules\ShortCode;

use Aenother\Modules\BaseModule;

class ShortCode extends BaseModule {
  public function __construct() {
    add_action( 'init', [ $this, 'register_shortcodes' ] );
  }

  public function register_shortcodes() {
    $shortcodes = [
      'contact-button' => [ $this, 'contact_button' ],
    ];

    foreach ( $shortcodes as $tag => $callback ) {
      add_shortcode( $tag, $callback );
    }
  }

  private function contact_button( $atts ) {
    $atts = shortcode_atts( [
      'name' => 'John Doe',
    ], $atts );

    // Look up member by name, otherwise return false
    // Create the HTML w. the member information

    // Return and NOT echo...
  }
}
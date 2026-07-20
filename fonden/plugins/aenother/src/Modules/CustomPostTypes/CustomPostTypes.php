<?php
namespace Aenother\Modules\CustomPostTypes;

use Aenother\Modules\BaseModule;

class CustomPostTypes extends BaseModule {

  public function __construct() {
    add_action( 'init', [ $this, 'register_cpts' ] );
  }

  public function register_cpts() {
    $this->register_cpt( 'person', 'personer', 'dashicons-admin-users' );
    $this->register_txnmy( 'rolle', 'roller', 'person', 'person_role' );
  }

  private function register_cpt( string $singular, string $plural, string $dashicons = 'dashicons-id' ) {
    $labels = [
      'name'               => ucfirst( $plural ),
      'singular_name'      => ucfirst( $singular ),
      'add_new'            => 'Tilføj ny',
      'add_new_item'       => "Tilføj ny {$singular}",
      'edit_item'          => "Rediger {$singular}",
      'new_item'           => "Ny {$singular}",
      'view_item'          => "Se {$singular}",
      'search_items'       => "Søg i {$plural}",
      'not_found'          => "Ingen personer fundet",
      'not_found_in_trash' => "Ingen {$plural} i papirkurven",
      'menu_name'          => ucfirst( $plural ),
    ];

    register_post_type( $singular, [
      'labels'             => $labels,
      'public'             => true,
      'has_archive'        => false,
      'publicly_queryable' => false,
      'supports'           => [ 'title' ],
      'menu_icon'          => $dashicons,
      'rewrite'            => [ 'slug' => $singular ],
    ] );
  }

  private function register_txnmy( string $singular, string $plural, string $cpt_name, string $id ) {
    $labels = [
      'name'          => ucfirst( $plural ),
      'singular_name' => ucfirst( $singular ),
      'search_items'  => "Søg i {$plural}",
      'all_items'     => "Alle {$plural}",
      'edit_item'     => "Rediger {$singular}",
      'add_new_item'  => "Tilføj ny {$singular}",
      'not_found'     => "Ingen {$plural} fundet",
      'menu_name'     => ucfirst( $plural ),
    ];

    register_taxonomy( $id, $cpt_name, [
      'labels'       => $labels,
      'hierarchical' => false,
      'public'       => true,
      'rewrite'      => [ 'slug' => "{$cpt_name}-{$singular}" ],
      'show_in_rest' => true,
    ] );
  }
}
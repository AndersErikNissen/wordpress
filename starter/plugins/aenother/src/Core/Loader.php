<?php
namespace Aenother\Core;

class Loader {
  /**
   * Array of modules to load
   * Format: 'Folder\ClassName'
   */
  protected $modules = [
    'DisableComments\DisableComments',
    'DisableGutenberg\DisableGutenberg',
    'ACFFields\ACFFields',
  ];

  public function __construct() {
    $this->init_modules();
  }

  private function init_modules() {
    foreach ( $this->modules as $module ) {
      if ( strpos( $module, 'ACFFields' ) !== false && ! class_exists( 'ACF' ) ) {
        continue;
      }

      $class = 'Aenother\\Modules\\' . $module; // e.g.
      
      if ( class_exists( $class ) ) {
        new $class();
      } else {
        error_log( "Aenother Error: Could not find class $class" );
      }
    }
  }
}
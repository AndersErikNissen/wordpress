<?php
namespace Aenother\Modules;

class ModuleLoader {

  protected $modules = [
    'ACF',
    'CustomPostTypes',
    'DisableComments',
    'DisableGutenberg',
    'OptionPage',
  ];

  public function __construct() {
    $this->init_modules();
  }

  private function init_modules() {
    foreach ( $this->modules as $module ) {
      if ( $module === 'ACFFields' && ! class_exists( 'ACF' ) ) {
        continue;
      }

      $class = 'Aenother\\Modules\\' . $module . '\\' . $module;
      
      if ( class_exists( $class ) ) {
        new $class();
      } else {
        error_log( "Aenother Plugin error: Could not find class: $class" );
      }
    }
  }
}
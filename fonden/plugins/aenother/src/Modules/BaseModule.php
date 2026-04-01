<?php
namespace Aenother\Modules;

abstract class BaseModule {

  /**
   * Helper to get the URL to this specific module's folder (for things like CSS / JS).
   */
  protected function get_url( $path = '' ) {
    // This finds the folder where the CHILD class lives
    $reflector = new \ReflectionClass(get_class($this));
    $dir = dirname($reflector->getFileName());
    
    return plugin_dir_url( $dir ) . basename( $dir ) . '/' . ltrim( $path, '/' );
  }

  /**
   * Helper to get the PATH to this specific module's folder.
   */
  protected function get_path( $path = '' ) {
    $reflector = new \ReflectionClass( get_class( $this ) );
    return dirname( $reflector->getFileName() ) . '/' . ltrim( $path, '/' );
  }
}
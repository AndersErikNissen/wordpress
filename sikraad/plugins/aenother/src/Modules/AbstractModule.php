<?php
namespace Aenother\Modules;

abstract class AbstractModule {
  
  protected function get_url( $path = '' ) {
    $reflector = new \ReflectionClass( get_class( $this ) );
    $dir = dirname( $reflector->getFileName() );
    return plugin_dir_url( $dir ) . basename( $dir ) . '/' . ltrim( $path, '/' );
  }

  protected function get_path( $path = '' ) {
    $reflector = new \ReflectionClass( get_class( $this ) );
    return dirname( $reflector->getFileName() ) . '/' . ltrim( $path, '/' );
  }
}
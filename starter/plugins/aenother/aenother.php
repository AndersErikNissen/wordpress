<?php
/**
 * Plugin Name: Aenother
 * Description: A plugin used with themes by Aenders.dk
 * Version: 1.0.0
 * Author: Aenders.dk
 * Author URI: https://aenders.dk
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Simple PSR-4 Autoloader
 */
spl_autoload_register( function ( $class ) {
  
  $prefix = 'Aenother\\';
  $base_dir = __DIR__ . '/src/';

  // Does the class use the namespace prefix?
  $len = strlen( $prefix );
  if ( strncmp( $prefix, $class, $len ) !== 0 ) {
    return;
  }

  // Get the relative class name and replace backslashes with slashes
  $relative_class = substr( $class, $len );
  $file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

  // If the file exists, require it
  if ( file_exists( $file ) ) {
    require $file;
  }
} );

new Aenother\Modules\ModuleLoader();
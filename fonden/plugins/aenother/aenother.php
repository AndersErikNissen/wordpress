<?php
/**
 * Plugin Name: Aenother
 * Description: A collection of smaller "plugins" used for themes by aenders.dk
 * Version: 0.1
 * Author: aenders.dk
 * Author URI: https://aenders.dk
 */

if ( ! defined( 'ABSPATH' ) ) exit; // if accessed directly by a user

/**
 * Simple PSR-4 Autoloader
 * * This tells PHP: "If you see a class starting with 'Aenother', look for a matching file inside the 'src' folder."
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
});

new Aenother\Core\Loader();
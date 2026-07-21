<?php
namespace Aenother\Modules\ACFFields;

use Aenother\Modules\AbstractModule;

class ACF extends AbstractModule {

  public function __construct() {
    // add_filter( 'acf/settings/show_admin', '__return_false' );
    add_filter( 'acf/settings/save_json', [ $this, 'save_json_path' ] );
    add_filter( 'acf/settings/load_json', [ $this, 'load_json_path' ] );
    add_filter( 'acf/json/save_file_name', [ $this, 'update_json_filename' ], 10, 3 );

    add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_acf_admin_styles' ] );

    add_action( 'after_setup_theme', [ $this, 'add_editor_styles' ] );
    add_filter( 'acf/fields/wysiwyg/toolbars', [ $this, 'configure_wysiwyg_toolbars' ], 1 );
    add_filter( 'tiny_mce_before_init', [ $this, 'configure_tinymce_blocks' ] );
  }

  public function save_json_path() { 
    return $this->get_path( 'local-json' ); 
  }
  
  public function load_json_path( $paths ) {
    $paths[] = $this->get_path( 'local-json' );
    return $paths;
  }

  public function update_json_filename( $filename, $post, $load_path ) {
    $filename = sanitize_title( $post['title'] ) . '.json';
    return $filename;
  }

  public function enqueue_acf_admin_styles( $hook ) {
    $screen = \get_current_screen();

    if ( $screen->post_type === 'acf-field-group' ) {
      return;
    }

    $is_editor = ( $screen->base === 'post' );

    $is_options = ( strpos( $screen->id, 'aenother-option-page' ) !== false );

    if ( $is_editor || $is_options ) {
      \wp_enqueue_style(
        'aenother-acf-custom',
        $this->get_url( 'css/acf-admin.css' ),
        [],
        filemtime( $this->get_path( 'css/acf-admin.css' ) ) 
      );
    }
  }

  public function add_editor_styles() {
    add_editor_style( $this->get_url( 'css/acf-editor.css' ) );
  }

 public function configure_wysiwyg_toolbars( $toolbars ) {
    unset( $toolbars['Full'] );
    
    $toolbars['Full'] = [];
    $toolbars['Full'][1] = [
      'formatselect',
      'bold',
      'italic',
      'strikethrough',
      'link',
      'bullist',
      'numlist',
      'blockquote',
      'undo',
      'redo'
    ];

    return $toolbars;
  }

  public function configure_tinymce_blocks( $init_array ) {
    $init_array['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4';
    return $init_array;
  }
}
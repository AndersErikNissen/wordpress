<?php
namespace Aenother\Modules\ACFFields;

use Aenother\Modules\BaseModule;

class ACFFields extends BaseModule {

  public function __construct() {
    // 1. General ACF Settings
    // add_filter( 'acf/settings/show_admin', '__return_false' );
    add_filter( 'acf/settings/save_json',   [ $this, 'save_json_path' ] );
    add_filter( 'acf/settings/load_json',   [ $this, 'load_json_path' ] );
    add_filter( 'acf/json/save_file_name',  [ $this, 'custom_json_filename' ], 10, 3 );

    // 2. Load Admin CSS (for the ACF interface)
    add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_acf_admin_styles' ] );

    // 3. WYSIWYG & TinyMCE Customization
    add_action( 'after_setup_theme',           [ $this, 'add_editor_styles' ] );
    add_filter( 'acf/fields/wysiwyg/toolbars', [ $this, 'configure_toolbars' ], 1 );
    add_filter( 'tiny_mce_before_init',        [ $this, 'configure_tinymce_blocks' ] );

    // 4. Field Logic
    add_filter( 'acf/fields/relationship/query', [ $this, 'exclude_current_post_from_relationship' ], 10, 3 );
    add_action( 'acf/save_post',                 [ $this, 'handle_featured_image_sync' ],             20 );
  }

  /**
   * JSON Path Helpers
   */
  public function save_json_path() { return $this->get_path('json'); }
  
  public function load_json_path( $paths ) {
    $paths[] = $this->get_path('json');
    return $paths;
  }

  /**
   * Enqueues CSS to style the ACF UI in the WordPress Backend
   */
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

  /**
   * Renames the JSON file based on the Group Title
   */
  public function custom_json_filename( $filename, $post, $load_path ) {
    $filename = sanitize_title( $post['title'] ) . '.json';
    return $filename;
  }

  public function add_editor_styles() {
    add_editor_style( $this->get_url( 'css/acf-editor.css' ) );
  }

  /**
   * Clean up WYSIWYG toolbars
   */
 public function configure_toolbars( $toolbars ) {
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

  /**
   * Edit TinyMCE block options (P, H2, H3, H4).
   */
  public function configure_tinymce_blocks( $init_array ) {
    $init_array['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4';
    return $init_array;
  }

  /**
   * Prevent the current post from showing up in its own relationship fields
   */
  public function exclude_current_post_from_relationship( $args, $field, $post_id ) {
    $args['post__not_in'] = [ $post_id ];
    return $args;
  }

  /**
   * Entry point for syncing image fields to the Featured Image
   */
  public function handle_featured_image_sync( $post_id ) {
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
      return;
    }

    $fields_to_check = [ 'section_hero.image' ];
    
    // OPTIONAL: Set a default Image ID from your Media Library (e.g., 125)
    $fallback_id = null; 

    $this->sync_to_featured_image( $post_id, $fields_to_check, $fallback_id );
  }

  /**
   * Internal logic for image syncing
   */
  private function sync_to_featured_image( $post_id, $field_paths, $fallback_id = null ) {
    $new_thumb_id = null;

    foreach ( $field_paths as $path ) {
      // Check if the path is nested (e.g., "group_name.image_field")
      if ( strpos( $path, '.' ) !== false ) {
        $keys = explode( '.', $path );
        $value = get_field( $keys[0], $post_id ); // Get the top-level group

        // Dig into the array for the sub-fields
        foreach ( array_slice( $keys, 1 ) as $key ) {
          if ( is_array( $value ) && isset( $value[ $key ] ) ) {
            $value = $value[ $key ];
          } else {
            $value = null;
            break;
          }
        }
      } else {
        // It's a standard top-level field
        $value = get_field( $path, $post_id );
      }

      if ( $value ) {
        $new_thumb_id = is_array( $value ) ? $value['ID'] : $value;
        break;
      }
    }

    // Use the fallback, if nothing was found
    if ( ! $new_thumb_id && $fallback_id ) {
      $new_thumb_id = $fallback_id;
    }

    $current_thumb_id = get_post_thumbnail_id( $post_id );

    if ( (int) $new_thumb_id !== (int) $current_thumb_id ) {
      if ( $new_thumb_id ) {
        set_post_thumbnail( $post_id, $new_thumb_id );
      } else {
        delete_post_thumbnail( $post_id );
      }
    }
  }
}
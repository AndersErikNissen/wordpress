<?php
namespace Aenother\Modules\DisableComments;

use Aenother\Modules\BaseModule;

class DisableComments extends BaseModule {

  public function __construct() {
    add_filter( 'comments_open',  '__return_false', 20 );
    add_filter( 'pings_open',     '__return_false', 20 );
    add_filter( 'comments_array', '__return_empty_array', 10 );

    if ( is_admin() ) {
      add_action( 'admin_init',                  [ $this, 'handle_admin_restrictions' ] );
      add_action( 'admin_menu',                  [ $this, 'clean_admin_menu' ] );
      add_action( 'wp_before_admin_bar_render',  [ $this, 'remove_admin_bar_link' ] );
      add_action( 'enqueue_block_editor_assets', [ $this, 'hide_gutenberg_panel' ] );
      
      // Post List Columns
      add_filter( 'manage_posts_columns', [ $this, 'remove_comments_columns' ] );
      add_filter( 'manage_pages_columns', [ $this, 'remove_comments_columns' ] );
    }
  }

  /**
   * Handles redirects and removing support for post types.
   */
  public function handle_admin_restrictions() {
    global $pagenow;

    // Redirect blocked pages
    if ( $pagenow === 'edit-comments.php' || $pagenow === 'options-discussion.php' ) {
      wp_safe_redirect( admin_url() );
      exit;
    }

    remove_submenu_page( 'options-general.php', 'options-discussion.php' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

    $this->strip_post_type_support();
  }

  /**
   * Internal helper to loop through post types.
   */
  private function strip_post_type_support() {
    foreach ( get_post_types() as $post_type ) {
      if ( post_type_supports( $post_type, 'comments' ) ) {
        remove_post_type_support( $post_type, 'comments' );
        remove_post_type_support( $post_type, 'trackbacks' );
      }
    }
  }

  public function clean_admin_menu() {
    remove_menu_page( 'edit-comments.php' );
    remove_submenu_page( 'options-general.php', 'options-discussion.php' );
  }

  public function remove_admin_bar_link() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'comments' );
  }

  public function remove_comments_columns( $columns ) {
    unset( $columns['comments'] );
    return $columns;
  }

  public function hide_gutenberg_panel() {
    wp_add_inline_script( 
      'wp-edit-post', 
      'wp.data.dispatch( "core/edit-post" ).removeEditorPanel( "discussion-panel" );' 
    );
  }
}
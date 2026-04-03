<?php
namespace Aenother\Modules\OptionPage;

use Aenother\Modules\BaseModule;

class OptionPage extends BaseModule {

  private $settings_page_hook;
  private $acf_groups = [ 'group_69ce7981d7168' ];

  public function __construct() {
    // 1. Register the menu
    add_action( 'admin_menu', [ $this, 'register_custom_settings_page' ] );

    // 2. Process ACF Form Data BEFORE the page loads
    add_action( 'admin_init', [ $this, 'handle_acf_form_submission' ] );

    // 3. Load Assets
    add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_acf_assets' ] );

    // 4. Hide groups, if not on the admin page
    add_filter( 'acf/prepare_field_group', [ $this, 'hide_acf_groups' ] );
  }

  public function register_custom_settings_page() {
    $this->settings_page_hook = add_menu_page(
      'Side indstillinger',              // Page Title
      'Side indstillinger',              // Menu Title
      'manage_options',                  // Capability
      'aenother-option-page',            // Slug
      [ $this, 'render_settings_page' ], // Callback
      'dashicons-admin-generic', 
      24
    );
  }

  public function handle_acf_form_submission() {
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'aenother-option-page' ) {
      acf_form_head();
    }
  }

  public function render_settings_page() {
    echo '<div class="wrap">';
      echo '<h1>Side indstillinger</h1>';
      acf_form( [
        'post_id'         => 'option',          
        'field_groups'    => $this->acf_groups, 
        'submit_value'    => 'Gem indstillinger',
        'updated_message' => 'Nye indstillinger gemt!',
      ] ); 
    echo '</div>';
  }

  public function enqueue_acf_assets( $hook ) {
    // Only load on this admin menu page
    if ( $hook !== $this->settings_page_hook ) {
      return;
    }

    wp_enqueue_style( 
      'aenother-option-page-admin-core', 
      $this->get_url( 'css/admin-core.css' ),
      [], 
      filemtime( $this->get_path( 'css/admin-core.css' ) ) 
    );
  }

  /**
   * Static helper to get options without typing 'option' every time
   * 
   * Usage(s):
   * use Aenother\Modules\OptionPage\OptionPage;
   * echo OptionPage::get('company_phone', '+45 00 00 00 00');
   * 
   * $phone_number = \Aenother\Modules\OptionPage\OptionPage::get('company_phone', '+45 00 00 00 00');
   * echo $phone_number;
   */
  public static function get( $field_name, $default = null ) {
    if ( function_exists( 'get_field' ) ) {
      $value = get_field( $field_name, 'option' );
      return ! empty( $value ) ? $value : $default;
    }
      
    return $default;
  }
}
<?php
namespace Aenother\Modules\OptionPage;

use Aenother\Modules\AbstractModule;

class OptionPage extends AbstractModule {

  private $settings_page_hook;
  private $acf_groups = [ 'group_69ce7981d7168' ];

  public function __construct() {
    add_action( 'admin_menu', [ $this, 'register_custom_settings_page' ] );

    add_action( 'admin_init', [ $this, 'handle_acf_form_submission' ] );
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
    printf(
      '<div class="wrap"><h1>Side indstillinger</h1>%s</div>',
      acf_form( [
        'post_id'         => 'option',          
        'field_groups'    => $this->acf_groups, 
        'submit_value'    => 'Gem indstillinger',
        'updated_message' => 'Nye indstillinger gemt!',
      ] ),
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
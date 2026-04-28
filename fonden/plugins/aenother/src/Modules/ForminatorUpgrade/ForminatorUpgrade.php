<?php
namespace Aenother\Modules\ForminatorUpgrade;

use Aenother\Modules\BaseModule;

class ForminatorUpgrade extends BaseModule {

  public function __construct() {
    add_filter( 'forminator_render_button_markup', [ $this, 'fu_custom_submit_button' ], 10, 2 );
    add_filter( 'forminator_field_file_markup',    [ $this, 'fu_custom_file_upload' ],   10, 2 );
  }

  function fu_custom_submit_button( $html, $button ) {
    $label = ! empty( $button ) ? esc_html( $button ) : 'Submit';

    $html = '<button class="cta__button" type="submit">
              <span class="button__content">
                <span class="button__labels l1">
                  <span class="button__label">' . $label . '</span>
                  <span class="button__label">' . $label . '</span>
                </span>
              </span>

              <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.74722 25.547L24.208 7.08627L8.57506 6.95172L8.5793 6.45186L8.58422 5.95131L25.9003 6.10074L26.0498 23.4169L25.5492 23.4218L25.0494 23.426L24.9148 7.79312L6.45407 26.2539L5.74722 25.547Z" fill="currentColor"/>
              </svg>
            </button>';

    return $html;
  }

  function fu_custom_file_upload( $html, $field ) {
    $label       = ! empty( $field['field_label'] ) ? esc_html( $field['field_label'] ) : 'Upload';
    $description = ! empty( $field['description'] ) ? esc_html( $field['description'] ) : '';

    $custom_html = '<div class="uploader">
                      <div class="uploader__main">
                        <span class="uploader__title">' . $label . '</span>
                        <span class="uploader__description">' . $description . '</span>
                      </div>

                      <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M15.5007 30.0011L15.5007 3.89365L4.3514 14.8526L4.00094 14.4962L3.65048 14.1388L16.0005 2.00009L28.3505 14.1388L28 14.4962L27.6496 14.8526L16.5003 3.89365L16.5003 30.0011L15.5007 30.0011Z" fill="currentColor"/>
                      </svg>' . 
                      $html . 
                    '</div>';
    return $custom_html;
  }
}
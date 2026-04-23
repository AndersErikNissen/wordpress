<?php
$data      = get_field( 'section_form' );
$shortcode = $data['shortcode'] ?? '';

if ( ! class_exists( 'Forminator' ) || empty( $data ) || empty( $shortcode ) ) return; ?>

<section class="form section">
  <div class="grid pw">
    <div class="grid__item">
      <?php echo apply_shortcodes( $shortcode ); ?>
    </div>
  </div>
</section>
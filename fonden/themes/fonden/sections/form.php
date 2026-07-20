<?php
$data      = get_field( 'section_form' );
$shortcode = $data['shortcode'] ?? '';

if ( ! class_exists( 'Forminator' ) || empty( $data ) || empty( $shortcode ) ) return; ?>

<section id="form" class="form">
  <div class="form__main">
    <?php echo apply_shortcodes( $shortcode ); ?>
  </div>
</section>
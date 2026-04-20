<?php
$data = get_field( 'section_introduction' );

if ( empty( $data ) ) return;

$title   = $data['title']                   ?? null;
$buttons = array_filter( $data['buttons'] ) ?: null; 
$image   = is_array( $data['image'] )       ? $data['image'] : null; ?>

<section class="introduction section">
  <div class="grid pw">
    <div class="grid__item tablet:end-10">
      <?php if ( $title ) {
        printf( '<h1 class="h1">%s</h1>', esc_html( $title ) );
      } ?>

      <?php if ( $buttons ) { ?>
        <ul class="buttons mt-2">
          <?php foreach ( array_values( $buttons ) as $index => $button ) {
            echo '<li>';
              render_button( $button, $index !== 0 );
            echo '</li>'; 
          } ?>
        </ul>
      <?php } ?>
    </div>

    <?php if ( $image ) { ?>
      <div class="grid__item mt-6">
        <?php render_img( $image, priority: 'high', use_alt: true ); ?>
      </div>
    <?php } ?>
  </div>
</section>
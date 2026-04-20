<?php
$data = get_field( 'section_sequence' );

if ( empty( $data ) ) return;

$items = array_filter( $data, function( $item ) {
  return ! empty( $item ) && ! empty( $item['title'] ) && ! empty( $item['text'] );
} );

if ( empty( $items ) ) return; ?>

<section class="sequence">
  <div class="sequence__content grid pw">
    <?php foreach ( array_values( $items ) as $index => $item ) { ?>
      <div class="sequence__title grid__item laptop:start-2 laptop:end-6">
        <h2 class="h1">
          <?php echo esc_html( $item['title'] ); ?>
        </h2>
      </div>

      <p class="sequence__text grid__item p1 laptop:start-7 laptop:end-12">
        <?php echo esc_html( $item['text'] ); ?>
      </p>
    <?php }; ?>
</div>
</section>
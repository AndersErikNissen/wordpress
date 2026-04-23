<?php
$data = get_field( 'section_cards' );

if ( empty( $data ) ) return;

$title = $data['title'] ?? null;

$items = array_filter( $data['items'], function( $item ) {
  return ! empty( $item ) && ! empty( $item['title'] ) && ! empty( $item['text'] );
} );

if ( empty( $items ) ) return;

$item_count = count ( $items );
$item_class = 'cards__item grid__item tablet:span-1/2';

if ( $item_count === 1 ) {
  $item_class .= ' tablet:start-4';
} ?>

<section class="cards section">
  <div class="grid pw">
    <?php if ( $title ) { ?>
      <div class="grid__item">
        <h2 class="h2 mb-1">
          <?php echo esc_html( $title ); ?>
        </h2>
      </div>
    <?php } ?>

    <ul class="grid__item grid">
      <?php foreach ( $items as $item ) { 
        $title = $item['title'] ?? null; 
        $text = $item['text'] ?? null;

        if ( ! $title && ! $text ) return; ?>

        <li class="<?php echo $item_class; ?>">
          <?php if ( $title ) { ?>
            <p class="h4 mb-1"><?php echo esc_html( $title ); ?></p>
          <?php } ?>

          <?php if ( $text ) { ?>
            <div class="rte">
              <?php echo $text; ?>
            </div>
          <?php } ?>
        </li>
      <?php } ?>
    </ul>
  </div>
</section>
<?php
$data = get_field( 'section_faq' );

if ( empty( $data ) ) return;

$items = array_filter( $data['items'], function( $item ) {
  return ! empty( $item ) && ! empty( $item['question'] ) && ! empty( $item['answer'] );
} );

if ( empty( $items ) ) return; 

$title = $data['title'] ?? null; ?>

<section id="faq" class="faq section">
  <div class="grid pw">
    <div class="grid__item tablet:start-2 tablet:end-12">
      <?php if ( $title ) { ?>
        <h2 class="h1 mb-2"><?php echo $title; ?></h2>
      <?php } ?>

      <ul class="accordion-collection">
        <?php foreach ( array_values( $items ) as $index => $item ) { ?>
          <li class="accordion">
            <div class="accordion__header">
              <p class="l2"><?php echo $item['question']; ?></p>
              <?php render_icon( 'chevron' ); ?>
            </div>

            <div class="accordion__drawer">
              <div class="accordion__content">
                <div class="rte p1">
                  <?php echo $item['answer']; ?>
                </div>
              </div>
            </div>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</section>
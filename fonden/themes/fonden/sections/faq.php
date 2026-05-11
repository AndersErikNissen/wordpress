<?php
$data = get_field( 'section_faq' );

if ( empty( $data ) ) return;

$items = array_filter( $data['items'], function( $item ) {
  return ! empty( $item ) && ! empty( $item['question'] ) && ! empty( $item['answer'] );
} );

if ( empty( $items ) ) return; 

$title = $data['title'] ?? null; ?>

<section id="faq" class="faq section" itemscope itemtype="https://schema.org/FAQPage">
  <div class="grid pw">
    <div class="grid__item">
      <?php if ( $title ) { ?>
        <h2 class="h2 mb-2"><?php echo $title; ?></h2>
      <?php } ?>

      <ul class="accordion-collection">
        <?php foreach ( array_values( $items ) as $index => $item ) { ?>
          <li class="accordion" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
            <div class="accordion__header">
              <p class="l2" itemprop="name">
                <?php echo esc_html( $item['question'] ); ?>
              </p>
              <?php render_icon( 'chevron' ); ?>
            </div>

            <div class="accordion__drawer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
              <div class="accordion__content">
                <div class="rte p1" itemprop="text">
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
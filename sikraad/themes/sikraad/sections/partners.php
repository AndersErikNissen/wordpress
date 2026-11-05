<?php
$data = get_field( 'section_partners' );

$text = $data['text'] ?? null;

// IMAGES
// IMAGES
// IMAGES
// IMAGES

if ( ! $text ) return; ?>

<section class="partners">
  <div class="content--min">
    <div class="partners__description rte--large">
      <?php echo $text; ?>
    </div>


  </div>
</section>
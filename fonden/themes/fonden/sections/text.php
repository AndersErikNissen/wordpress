<?php
$data = get_field( 'section_text' );

if ( empty( $data ) ) return;

$content = $data['content'] ?? null;

if ( empty( $content ) ) return; ?>

<section class="text section">
  <div class="grid pw">
    <div class="grid__item">
      <?php if ( $content ) { ?>
        <div class="rte narrow">
          <?php echo $content; ?>
        </div>
      <?php } ?>
    </div>
  </div>
</section>
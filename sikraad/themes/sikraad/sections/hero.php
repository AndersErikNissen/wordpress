<?php
$data = get_field( 'section_hero' );

if ( empty( $data ) ) return;

$title = $data['title'] ?? null;
$button = $data['button'] ?? null;
$image = $data['image'] ?? null;

if ( ! $title ) return; ?>

<section class="hero">
  <div class="content">
    <div class="hero__content">
      <?php if ( $title ) { ?>
        <h1 class="hero__title h1">
          <?php echo $title; ?>
        </h1>
      <?php }

      if ( $button ) { ?>
        <div>
          <?php render_btn( 
            $button, 
            [ 'class' => 'txt-btn' ],
            'arrow-right'
          ); ?>
        </div>
      <?php } ?>
    </div>

    <?php if ( $image ) { ?>
      <div class="ratio-container portrait landscape--desktop">
        <?php render_img( 
          $image, 
          priority: 'high',
        ); ?>
      </div>
    <?php } ?>
  </div>
</section>
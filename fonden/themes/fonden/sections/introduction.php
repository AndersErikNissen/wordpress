<?php
$data = get_field( 'section_introduction' );

if ( empty( $data ) ) return;

$title   = $data['title']                   ?? null;
$buttons = array_filter( $data['buttons'] ) ?: null; 
$image   = is_array( $data['image'] )       ? $data['image'] : null; ?>

<section class="section">
  <div class="grid">
    <div class="grid__item end-10">
      <?php if ( $title ) {
        printf( '<h1 class="h1">%s</h1>', esc_html( $title ) );
      } ?>

      <?php if ( $buttons ) { ?>
        <ul class="buttons">
          <?php $index = 0; 
          foreach ( $buttons as $button ) {
            printf( '<li><a class="button%s" href="%s" target="%s">%s</a></li>',
              $index > 0 ? ' outlined' : '',
              esc_url( $button['url'] ),
              esc_attr( $button['target'] ?: '_self' ),
              esc_html( $button['title'] )
            );
            $index++;
          } ?>
        </ul>
      <?php } ?>
    </div>

    <?php if ( $image ) { ?>
      <div class="grid__item">
        <?php render_acf_img( $image ); ?>
      </div>
    <?php } ?>
  </div>
</section>
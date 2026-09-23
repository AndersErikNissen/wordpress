<?php
$data = get_field( 'section_icon_w_text' );

if ( empty( $data ) ) return;

$title = $data['title'] ?? null;
$text = $data['text'] ?? null;
$button = $data['button'] ?? null;

if ( ! $title ) return; ?>

<section class="icon-w-text">
  <div class="icon-w-text__icon">
    <svg width="1939" height="3123" viewBox="0 0 1939 3123" fill="none" xmlns="http://www.w3.org/2000/svg">
      <g clip-path="url(#clip0_344_151)">
        <path d="M-98.0137 3041.47L1741.6 1201.86C1834.54 1108.92 1834.54 958.228 1741.6 865.284C1648.65 772.34 1497.96 772.34 1405.01 865.284L719.793 1550.86C626.848 1643.81 476.155 1643.81 383.21 1550.86C290.265 1457.92 290.265 1307.23 383.21 1214.28L1074.5 532.002C1167.45 439.057 1167.45 288.362 1074.5 195.417C981.557 102.473 830.863 102.473 737.919 195.417L-98.0128 1031.71" stroke="currentColor" stroke-width="250"/>
      </g>

      <defs>
        <clipPath id="clip0_344_151">
          <rect width="1939" height="3123" fill="white"/>
        </clipPath>
      </defs>
    </svg>
  </div>
  
  <div class="icon-w-text__content content">
    <div class="icon-w-text__text">
      <?php if ( $title ) { ?>
        <h2 class="h2">
          <?php echo $title; ?>
        </h2>
      <?php }
      
      if ( $text ) { ?>
        <div class="rte">
          <?php echo $text; ?>
        </div>
      <?php }
  
      if ( $button ) { ?>
        <?php render_btn( 
          $button, 
          [ 'class' => 'icon-w-text__btn btn' ]
        ); ?>
      <?php } ?>
    </div>
  </div>
</section>

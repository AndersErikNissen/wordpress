<?php
$content = get_field( 'content' );
$settings = get_field( 'settings' );

if ( empty( $content ) ) return;

$name = $content['name'] ?? null;
$acronym = $content['acronym'] ?? null;
$synopsis = $content['synopsis'] ?? null;
$color_theme = $settings['color_theme'] ?? null;

if ( ! $name || ! $acronym ) return; ?>

<div class="expertise color-theme--<?php echo $color_theme; ?> contain">
  <div class="expertise__content">
    <p class="expertise__name l1">
      <?php echo $name; ?>
    </p>

    <div class="expertise__title">
      <h3 class="expertise__acronym h1">
        <?php echo $acronym; ?>
      </h3>

      <div class="circle-btn--invert">
        <?php echo get_icon( 'arrow-up-right' ); ?>
      </div>
    </div>
  </div>

  <a href="cover"></a>
</div>
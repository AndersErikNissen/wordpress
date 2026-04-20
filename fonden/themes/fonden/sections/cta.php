<?php
$data = get_field( 'section_cta' );

if ( empty( $data ) ) return;

$title        = $data['title']     ?? null;
$link         = $data['page_link'] ?? null;
$sticky_class = $data['is_sticky'] ? ' sticky' : '';

if ( ! $title || ! $link ) return; ?>

<div class="cta<?php echo $sticky_class ?>">
  <div class="grid pw">
    <div class="grid__item">
      <a class="cta__button" href="<?php echo $link; ?>" target="_self">
        <span class="button__content">
          <span class="button__labels l1">
            <span class="button__label"><?php echo $title; ?></span>
            <span class="button__label"><?php echo $title; ?></span>
          </span>
        </span>

        <?php render_icon( 'arrow-right' ); ?>
      </a>
    </div>
  </div>
</div>
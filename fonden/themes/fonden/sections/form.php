<?php
$data      = get_field( 'section_form' );
$shortcode = $data['shortcode'] ?? '';

if ( ! class_exists( 'WPCF7' ) || empty( $data ) || empty( $shortcode ) ) return; 

[
    'title' => $title,
    'label' => $label,
] = wp_parse_args( $args ?? [], [
    'title' => 'Skriv til os',
    'label' => 'Send besked',
] );

// Find the CF7 form ID ("-o1", is only if there is one form on the page)
preg_match( '/\bid=["\']?(\d+)["\']?/', $shortcode, $matches );
$cf7_id  = $matches[1] ?? null;
$form_id = "wpcf7-f{$cf7_id}-p" . get_the_ID() . "-o1"; ?>

<section class="form section">
  <div class="grid">
    <div class="grid__item">
      <h2 class="h2"><?php echo $title; ?></h2>
    </div>
    
    <div class="grid__item">
      <?php echo $shortcode; ?>

      <button class="cta__button" type="submit" form="<?php echo $form_id; ?>">
        <span class="button__content">
          <span class="button__labels l1">
            <span class="button__label"><?php echo $label; ?></span>
            <span class="button__label"><?php echo $label; ?></span>
          </span>
        </span>

        <?php render_icon( 'arrow-right' ); ?>
      </button>
    </div>
  </div>
</section>

<!-- <label class="form__label">
  Navn...
  <span class="form__required">*</span>
  [text* your-name autocomplete:name]
</label>

<div class="grid">
  <div class="grid__item span-1/2">
    <label class="form__label">
      E-mail...
      <span class="form__required">*</span>
      [email* your-email autocomplete:email]
    </label>
  </div>
  
  <div class="grid__item span-1/2">
    <label class="form__label">
      Telenfonnummer...
      [tel* your-tel autocomplete:tel]
    </label>
  </div>
</div>

<label class="form__label">
  Besked...
  <span class="form__required">*</span>
  [text-area* your-message]
</label> -->
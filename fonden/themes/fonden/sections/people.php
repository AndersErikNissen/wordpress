<?php
$data = get_field( 'section_people' );

if ( empty( $data ) ) return;

$title  = $data['title'];
$people = $data['people'];

if ( empty( $people ) ) return; ?>

<section id="people" class="people background-section">
  <div class="grid pw">
    <?php if ( $title ) { ?>
      <div class="grid__item">
        <h2 class="h2 mb-1"><?php echo esc_html( $title ); ?></h2>
      </div>
    <?php } ?>

    <div class="grid__item">
      <ul class="grid">
        <?php foreach ( $people as $person ) { 
          $name         = get_field( 'name', $person->ID );
          $title        = get_field( 'title', $person->ID );
          $email        = get_field( 'email', $person->ID );
          $phone_number = get_field( 'phone_number', $person->ID ); ?>

          <li class="person grid__item laptop:span-1/2">
            <h3 class="h4"><?php echo esc_html( $name ); ?></h3>

            <?php if ( $title ) { ?>
              <span class="l3<?php if ( $email || $phone_number ) echo ' mb-15'; ?>"><?php echo esc_html( $title ); ?></span>
            <?php } ?>
            
            <?php if ( $email ) { ?>
              <a class="link" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
            <?php } ?>
            
            <?php if ( $phone_number ) { ?>
              <a class="mt-075 link" href="tel:<?php echo esc_attr( str_replace( ' ', '', $phone_number ) ); ?>"><?php echo esc_html( $phone_number ); ?></a>
            <?php } ?>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</section>
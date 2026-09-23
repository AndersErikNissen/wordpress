<?php
$data = get_field( 'section_expertises' );

$title = $data['title'] ?? null;
$text = $data['text'] ?? null;

$query_expertises = new WP_Query( [
  'post_type'      => 'expertise', 
  'posts_per_page' => -1,          
  'post_status'    => 'publish',
] );

if ( ! $query_expertises->have_posts() ) return; ?>

<section class="expertises" style="--expertise-count:<?php echo $query_expertises->post_count; ?>">
  <div class="observer-zone"></div>

  <div class="expertises__content content">
    <div class="expertises__description">
      <?php if ( $title ) { ?>
        <h2 class="h2">
          <?php echo $title; ?>
        </h2>
      <?php } ?>
      
      <?php if ( $text ) { ?>
        <div class="rte">
          <?php echo $text; ?>
        </div>
      <?php } ?>
    </div>
    
    <div class="expertises__slider">
      <?php while ( $query_expertises->have_posts() ) {
          $query_expertises->the_post();
          get_template_part( 'blocks/expertise' );
      } 

      wp_reset_postdata(); ?>
    </div>
  </div>

  <div class="expertices__backdrop"></div>
</section>


<!-- 
  <div class="content">
    <div class="cards__content">
      <div class="cards__text">
        <?php if ( $title ) { ?>
          <h2 class="cards__title h1">
            <?php echo $title; ?>
          </h2>
        <?php }
  
        if ( $text ) { ?>
          <div class="rte">
            <?php echo $text; ?>
          </div>
        <?php } ?>
      </div>
      

      <?php if ( $items ) : ?>
        <div class="section-cards__cards">
          <div class="section-cards__cards-main">
            <?php foreach ( $items as $item ) : 
              $item_data = get_fields( $item->ID ); 
              $name = ( $item_data[ 'name' ] ?: false );
              $shorthand_name = ( $item_data[ 'shorthand_name' ] ?: false ); 
              $colors = ( $item_data[ 'colors' ] ?: false ); 
              $card_classes = 'c-bg--' . $colors[ 'background' ] . ' c-text--' . $colors[ 'text' ];
              $icon_classes = 'c-bg--' . $colors[ 'text' ] . ' c-text--' . $colors[ 'background' ]; ?>

              <div class="section-cards__card">
                <div class="section-cards__card-main cover-wrapper <?= $card_classes; ?>">
                  <?php if ( $name ) : ?> 
                    <p class="l1"><?= $name; ?></p>
                  <?php endif; ?>

                  <div class="section-cards__card-name-wrapper">
                    <h3 class="h1"><?= $shorthand_name; ?></h3>

                    <div class="circle-icon <?= $icon_classes; ?>">
                      <?php render_icon( 'arrow-up-right' ); ?> 
                    </div>
                  </div>

                  <a class="cover" href="<?php the_permalink( $item->ID ); ?>"></a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div> -->

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

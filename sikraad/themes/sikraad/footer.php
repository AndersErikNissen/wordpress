<?php 
  $company = get_field( 'company', 'option' );
  $contact = get_field( 'contact', 'option' );
  
  $description = $company['description'] ?? null;
  $cvr = $company['cvr'] ?? null;
  $email = $contact['email'] ?? null;
  $phone_number = $contact['phone_number'] ?? null;
?>    

      </main>

      <footer class="the-footer">
        <div class="content">
          <?php wp_nav_menu( array (
            'menu'       => 'footer',
            'menu_class' => 'the-footer__menu',
            'container'  => 'ul'
          ) ); ?>
          
          <!-- Partners ??? -->
          <!-- Company info - CVR + EMAIL + TLF -->
          <!-- Copyright -->
          <!-- Handcrafted by Aenders.dk -->
          
        </div>
      </footer>
    </div>
    
    <?php wp_footer(); ?>
  </body>
<html>
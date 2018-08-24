<?php
/**
 * The template for displaying the full page search form
 */
?>
<section class="czr-overlay search_o">
  <a href="#" class="search-close_btn search-toggle_btn czr-overlay-toggle_btn" role="button"><span class="sr-only"><?php esc_html_e( 'Close search', 'customizr' ) ?></span><i class="icn-close" role="image" aria-hidden="true"></i></a>
  <div class="overlay-content">
    <div class="search__wrapper">
    <?php get_search_form() ?>
    </div>
  </div>
</section>
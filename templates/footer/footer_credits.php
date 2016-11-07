<?php
/**
 * The template for displaying the footer credits
 * (generally the central colophon element)
 *
 * @package Customizr
 * @since Customizr 3.5.0
 */
?>
<div class="credits" <?php czr_fn_echo('element_attributes') ?>>
  <p>
    &middot; <span class="tc-copyright-text">&copy; <?php echo esc_attr( date('Y') ) ?></span> <a href="<?php echo esc_url( home_url() ) ?>" title="<?php echo esc_attr( get_bloginfo() ) ?>"><?php echo esc_attr( get_bloginfo() ) ?></a>
    &middot; <span class="tc-credits-text"><?php _e( 'Designed by', 'customizr' ) ?></span> <a href="<?php echo CZR_WEBSITE ?>">Press Customizr</a>
    &middot; <span class="tc-wp-powered-text"><?php _e('Powered by', 'customizr') ?></span> <a class="icon-wordpress" target="_blank" href="https://wordpress.org" title="<?php _e('Powered by Wordpress', 'customizr') ?>"></a> &middot;
  </p>
</div>

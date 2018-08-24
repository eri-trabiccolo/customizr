<?php
/**
 * The template for displaying the footer credits
 *
 */
?>
<div id="footer__credits" class="footer__credits" <?php czr_fn_echo('element_attributes') ?>>
  <p class="czr-copyright">
    <span class="czr-copyright-text">&copy;&nbsp;<?php echo esc_attr( date('Y') ) ?>&nbsp;</span><a class="czr-copyright-link" href="<?php echo esc_url( home_url() ) ?>"><?php echo esc_attr( get_bloginfo() ) ?></a><span class="czr-rights-text">&nbsp;&ndash;&nbsp;<?php esc_html_e( 'All rights reserved', 'customizr') ?></span>
  </p>
  <p class="czr-credits">
    <span class="czr-designer">
      <span class="czr-wp-powered"><span class="czr-wp-powered-text"><?php _e( 'Powered by', 'customizr') ?>&nbsp;</span><a class="czr-wp-powered-link fab fa-wordpress" title="<?php esc_attr_e( 'Go to WordPress.org', 'customizr' ) ?>" href="<?php echo esc_url( __( 'https://wordpress.org/', 'customizr' ) ); ?>" target="_blank"><span class="sr-only"><?php esc_html_e( 'Powered by WordPress', 'customizr' ) ?></span></a></span><span class="czr-designer-text">&nbsp;&ndash;&nbsp;<?php printf( __('Designed with the %s', 'customizr'), sprintf( '<a class="czr-designer-link" href="%1$s">%2$s</a>', esc_url( CZR_WEBSITE . 'customizr' ), __('Customizr theme', 'customizr') ) ); ?></span>
    </span>
  </p>
</div>

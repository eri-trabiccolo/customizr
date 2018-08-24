<?php
/**
 * The template for displaying the menu button ( both in the navbar and sidenav )
 */
?>
<<?php czr_fn_echo('element_tag') ?> class="hamburger-toggler__container <?php czr_fn_echo( 'element_class' ) ?>" <?php czr_fn_echo('element_attributes') ?>>
  <a href="#" class="ham-toggler-menu czr-collapsed" <?php czr_fn_echo('data_attributes') ?> role="button"><span class="sr-only"><?php esc_html_e( 'Menu', 'customizr' ); ?></span><div class="ham__toggler-span-wrapper"><span class="line line-1"></span><span class="line line-2"></span><span class="line line-3"></span></div></a>
</<?php czr_fn_echo('element_tag') ?>>

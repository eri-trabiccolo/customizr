<?php
/**
 * The template for displaying the back to top link
 * ( generally in the colophon right )
 *
 * @package Customizr
 * @since Customizr 3.5.0
 */
?>
<div class="backtop <?php echo is_rtl() ? 'pull-left' : 'pull-right' ?>" <?php czr_fn_echo('element_attributes') ?>>
  <a class="back-to-top" href="#"><?php _e( 'Back to top', 'customizr' ) ?></a>
</div>

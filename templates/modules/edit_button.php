<?php
/**
 * The template for displaying the edit button
 *
 */
?>
<a class="btn btn-edit <?php czr_fn_echo( 'edit_button_class' ) ?>"
   title="<?php echo esc_attr( czr_fn_get( 'edit_button_title' ) ? czr_fn_get( 'edit_button_title' ) : czr_fn_get( 'edit_button_text' ) )?>"
   href="<?php echo esc_url( czr_fn_get( 'edit_button_link' ) )?>">
   <i class="icn-edit"></i><?php czr_fn_echo( 'edit_button_text' ) ?>
</a>
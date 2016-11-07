<?php
/**
 * The template for displaying the help block placeholders
 *
 * @package Customizr
 * @since Customizr 3.5.0
 */
?>
<<?php

  /* generally a div, can be aside when in a widget area*/
  czr_fn_echo( 'element_tag' )

?> class="tc-placeholder-wrap <?php czr_fn_echo( 'element_class' ) ?>" <?php
  /* specific placeholders data:
   * data-notice_id   => (required) a reference to the notice id
   * data-user_option => (optional) a reference to the user option to unset
   */
   czr_fn_echo( 'help_block_data' ) ?> <?php czr_fn_echo( 'element_attributes' ) ?>>
    <span class="tc-admin-notice"> <?php _e( 'This block is visible for admin users only.', 'customizr') ?></span>
  <?php
  /* Print help block title */
  if ( czr_fn_get( 'help_title' ) ) :

  ?>
    <h4><?php czr_fn_echo( 'help_title') ?></h4>

  <?php

  endif //title

  ?>
  <p><strong>
  <?php
    /* Print the message: contains html */
    czr_fn_echo( 'help_message' );
  ?>
  </strong></p>
  <?php

    /* Print the secondary message: contains html */
    czr_fn_echo( 'help_secondary_message' );

  ?>
  <a class="tc-dismiss-notice" href="#" title="<?php _e( 'dismiss notice', 'customizr' ) ?>"><?php _e( 'dismiss notice', 'customizr' ) ?> x</a>
</<?php czr_fn_echo( 'element_tag' ) ?>>


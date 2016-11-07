<?php
class CZR_cl_thumbnail_rectangular_model_class extends CZR_cl_thumbnail_model_class {
  public $thumb_wrapper_class   = '';
  public $link_class            = 'tc-rectangular-thumb';

  public $type                  = 'rectangular';

  /* override */
  function czr_fn_get_no_effect_class( $thumb_model ) {
    return array();
  }

  /**
  * override
  *
  * @package Customizr
  * @since Customizr 3.2.0
  */
  function czr_fn_get_thumb_size( $_default_size = 'tc-thumb' ) {
    $_position = esc_attr( czr_fn_get_opt( 'tc_post_list_thumb_position' ) );
    return ( 'top' == $_position || 'bottom' == $_position ) ? 'tc_rectangular_size' : $_default_size;
  }

  /**
  * hook : czr_fn_user_options_style
  * @return css string
  *
  * @package Customizr
  * @since Customizr 3.2.6
  */
  function czr_fn_user_options_style_cb( $_css ) {
    $_list_thumb_height     = esc_attr( czr_fn_get_opt( 'tc_post_list_thumb_height' ) );
    $_list_thumb_height     = (! $_list_thumb_height || ! is_numeric($_list_thumb_height) ) ? 250 : $_list_thumb_height;
    return sprintf("%s\n%s",
      $_css,
      ".tc-rectangular-thumb {
        max-height: {$_list_thumb_height}px;
        height :{$_list_thumb_height}px
      }\n"
    );
  }
}

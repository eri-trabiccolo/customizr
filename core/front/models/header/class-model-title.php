<?php
class CZR_cl_title_model_class extends CZR_cl_Model {
  public $link_class;
  public $link_url;

  /**
  * @override
  * fired before the model properties are parsed
  *
  * return model params array()
  */
  function czr_fn_extend_params( $model = array() ) {
    $model[ 'element_class' ]       = apply_filters( 'czr_logo_class', $this -> get_title_wrapper_class(), $model );
    $model[ 'link_title' ]          = apply_filters( 'czr_site_title_link_title', sprintf( '%1$s | %2$s' ,
                                             __( esc_attr( get_bloginfo( 'name' ) ) ),
                                             __( esc_attr( get_bloginfo( 'description' ) ) )
                                         ),
                                         $model
                                     );
    $model[ 'link_url'   ]          = apply_filters( 'czr_logo_link_url', esc_url( home_url( '/' ) ), $model );

    return $model;
  }

  /* the same in the logo wrapper class, some kind of unification will be needed IMHO */
  function get_title_wrapper_class() {
    $_class     = array( 'brand', 'span3' );
    $_layout    = esc_attr( czr_fn_get_opt( 'tc_header_layout') );
    $_class[]   = 'right' == $_layout ? 'pull-right' : 'pull-left';
    return $_class;
  }

  /**
  * @override
  * Allow filtering of the header class by registering to its pre view rendering hook
  */
  function czr_fn_maybe_filter_views_model() {
    parent::czr_fn_maybe_filter_views_model();
    add_action( 'pre_rendering_view_header', array( $this, 'pre_rendering_view_header_cb' ) );
  }


  /**
  * parse header model before rendering to add 'sticky' title visibility
  * and shrinking classes
  */
  function pre_rendering_view_header_cb( $header_model ) {
    if ( esc_attr( czr_fn_get_opt( "tc_sticky_header") || CZR() -> czr_fn_is_customizing() ) )
      array_push( $header_model -> element_class,
          0 != esc_attr( czr_fn_get_opt( 'tc_sticky_shrink_title_logo') ) ? ' tc-shrink-on' : ' tc-shrink-off',
          0 != esc_attr( czr_fn_get_opt( 'tc_sticky_show_title_logo') ) ? 'tc-title-logo-on' : 'tc-title-logo-off'
      );
  }

  /**
  * Adds a specific style to allow the title shrinking
  * hook : czr_fn_user_options_style
  *
  * @package Customizr
  */
  function czr_fn_user_options_style_cb( $_css ) {
    //title shrink
    if ( ( 0 != esc_attr( czr_fn_get_opt( 'tc_sticky_header') ) && 0 != esc_attr( czr_fn_get_opt( 'tc_sticky_shrink_title_logo') ) ) || CZR() -> czr_fn_is_customizing() ) {
      $_title_font 	= implode (';' , apply_filters('czr_title_shrink_css' , array("font-size:0.6em","opacity:0.8","line-height:1.2em") ) );

      $_css = sprintf("%s%s",
          $_css,
          "
      .sticky-enabled .tc-shrink-on .brand .site-title {
        {$_title_font}
      }"
      );
    }
    return $_css;
  }//end inline css func
}

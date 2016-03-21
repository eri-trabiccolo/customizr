<?php
class TC_post_list_content_model_class extends TC_Model {
  public  $content_cb;
  private $content;
  public  $content_class;

  function __construct( $model = array() ) {
    //Fires the parent constructor
    parent::__construct( $model );

    //filter the excerpt length
    add_filter( 'excerpt_length'        , array( $this , 'tc_set_excerpt_length') , 999 );

    //filter our countent
    add_filter( 'tc_the_content'        , array( $this , 'tc_add_support_for_shortcode_special_chars') );

    //inside the loop but before rendering set some properties
    add_action( $model['hook']          , array( $this, 'tc_set_this_properties' ), 0 );
  }


  function tc_get_element_class(){
    return 'tc-content ' . get_query_var( 'tc_content_width' );
  }


  function tc_the_post_list_content( $more  = null ) {
    if ( $this -> content )
      echo $this -> content;
    elseif ( 'get_the_excerpt' == $this -> content_cb )
      echo apply_filters( 'the_excerpt', get_the_excerpt() );  
    else
      echo apply_filters( 'tc_the_content', get_the_content( $more ) );
  }

  
  
  /**
  * hook : excerpt_length hook
  * @return string
  * @package Customizr
  * @since Customizr 3.2.0
  */
  function tc_set_excerpt_length( $length ) {
    $_custom = esc_attr( TC_utils::$inst->tc_opt( 'tc_post_list_excerpt_length' ) );
    return ( false === $_custom || !is_numeric($_custom) ) ? $length : $_custom;
  }


  /**
  * hook : tc_the_content
  * Applies tc_the_content filter to the passed string
  *
  * @param string
  * @return  string
  *
  * @package Customizr
  * @since Customizr 3.3+
  */
  function tc_add_support_for_shortcode_special_chars( $_content ) {
    return str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $_content ) );
  }


  function tc_set_this_properties() {
    $show_excerpt        = get_query_var( 'tc_show_excerpt' );  
    $content_class       = array( 'entry-summary' );
    $content_cb          = $show_excerpt ? 'get_the_excerpt' : 'get_the_content' ;
    $content             = '';

    if ( in_array( get_post_format(), array( 'image' , 'gallery' ) ) )
    {
      $content_class     = array( 'entry-content');
      $content           = '<p class="format-icon"></p>';
    }
    elseif ( in_array( get_post_format(), array( 'quote', 'status', 'link', 'aside', 'video' ) ) ) {
      $content_class     = array( 'entry-content', apply_filters( 'tc_post_list_content_icon', 'format-icon' ) );
      $content_cb        = 'get_the_content';
    }

    $this -> tc_update( compact( 'content_class', 'content_cb', 'content' ) );
  }

  /**
  * parse this model properties for rendering
  */ 
  function pre_rendering_my_view_cb( $model ) {
    parent::pre_rendering_my_view_cb( $model );  
    $model -> content_class = $this -> tc_stringify_model_property( 'content_class' );
  }
}

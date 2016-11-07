<?php
class CZR_cl_slide_model_class extends CZR_cl_Model {
  public $img_wrapper_class;
  public $caption_class;

  public $slide_background;

  public $title;
  public $title_class;
  public $title_tag;

  public $text;
  public $text_class;

  public $button_text;
  public $button_class;
  public $button_link;

  public $link_url;
  public $link_target;
  public $link_whole_slide;

  public $color_style = '';

  public $has_caption;
  public $slide_id;

  public $slider_name_id;

  function czr_fn_setup_late_properties() {
    //get the current slide;
    $current_slide        = czr_fn_get( 'current_slide' );

    if ( empty ( $current_slide ) )
      return;

    //array( $slide, $slide_id );
    extract( $current_slide );

    $slider_name_id = czr_fn_get( 'slider_name_id' );
    $img_size       = czr_fn_get( 'img_size');

    //demo data
    if ( 'demo' == $slider_name_id && is_user_logged_in() )
      $slide = array_merge( $slide,  $this -> czr_fn_set_demo_slide_data( $slide, $slide_id ) );

    //array( $title, $text, $button_text, $link_id, $link_url, $link_target, $link_whole_slide, $active, $color_style, $slide_background )
    extract ( $slide );

    $element_class = array_filter( array( 'slide-'. $slide_id, $active ) );

    //caption elements
    $caption           = $this -> czr_fn_get_slide_caption_model( $slide, $slider_name_id, $slide_id );
    $has_caption       = ! empty( $caption );

    $link_whole_slide  = isset($link_whole_slide) && $link_whole_slide && $link_url;

    //img elements
    $img_wrapper_class = apply_filters( 'czr_slide_content_class', sprintf('carousel-image %1$s' , $img_size ) );

    $this -> czr_fn_update(
        array_merge( $slide, $caption, compact('element_class', 'img_wrapper_class', 'has_caption', 'link_whole_slide', 'slider_name_id', 'slide_id' ) )
    );
  }


  /**
  * Slide caption submodel
  * @param $_view_model = array( $id, $data , $slider_name_id, $img_size )
  *
  * @package Customizr
  * @since Customizr 3.3+
  *
  * return array( 'button' => array(), $text,
  */
  function czr_fn_get_slide_caption_model( $slide, $slider_name_id, $id ) {
    extract( $slide );

    //filters the data before (=> used for demo for example )
    $data                   = apply_filters( 'czr_slide_caption_data', $slide, $slider_name_id, $id );
    $show_caption           = ! ( $title == null && $text == null && $button_text == null ) ;
    if ( ! apply_filters( 'czr_slide_show_caption', $show_caption , $slider_name_id ) )
      return array();


    //apply filters first
    /* classes and tags can be skipped if we decided that must be changed only in the templates */
    $caption_class          = apply_filters( 'czr_slide_caption_class', array( 'carousel-caption' ), $show_caption, $slider_name_id );

    $_title                  = isset($title) ? apply_filters( 'czr_slide_title', $title , $id, $slider_name_id ) : '';
    $_text                   = isset($text) ? esc_html( apply_filters( 'czr_slide_text', $text, $id, $slider_name_id ) ) : '';

    $_button_text            = isset($button_text) ? apply_filters( 'czr_slide_button_text', $button_text, $id, $slider_name_id ) : '';

    //computes the link
    $button_link            = apply_filters( 'czr_slide_button_link', $link_url ? $link_url : 'javascript:void(0)', $id, $slider_name_id );

    //defaults => reset caption elements
    $defaults  = array(
      'title'        => '',
      'text'         => '',
      'button_text'  => ''
    );


    // title elements
    if ( apply_filters( 'czr_slide_show_title', $_title != null, $slider_name_id ) ) {
      $title_tag    = apply_filters( 'czr_slide_title_tag', 'h1', $slider_name_id );
      $title        = $_title;
      $title_class  = implode( ' ', apply_filters( 'czr_slide_title_class', array( 'slide-title' ), $title , $slider_name_id ) );
    }

    // text elements
    if (  apply_filters( 'czr_slide_show_text', $_text != null, $slider_name_id ) ) {
      $text         = $_text;
      $text_class   = implode( ' ', apply_filters( 'czr_slide_text_class', array( 'lead' ), $text, $slider_name_id ) );
    }

    // button elements
    if ( apply_filters( 'czr_slide_show_button', $_button_text != null, $slider_name_id ) ) {
      $button_text  = $_button_text;
      $button_class = implode( ' ', apply_filters( 'czr_slide_button_class', array( 'btn', 'btn-large', 'btn-primary' ), $button_text, $slider_name_id ) ) ;
      $button_link  = apply_filters( 'czr_slide_button_link', $link_url ? $link_url : 'javascript:void(0)', $id, $slider_name_id ) ;
    }

    //re-check the caption elements are set
    if ( ! ( isset($title) || isset($text) || isset($button_text) ) )
      return array();

    $caption_elements = wp_parse_args( compact( 'title', 'button_text', 'text' ), $defaults );

    return array_merge(
        $caption_elements,
        compact( 'caption_class', 'title_class', 'title_tag', 'text_class', 'button_link', 'button_class' )
    );
  }



  /******************************
  * HELPERS / SETTERS / CALLBACKS
  *******************************/
  /**
  * Returns the modified caption data array with a link to the doc
  * Only displayed for the demo slider and logged in users
  *
  * @package Customizr
  * @since Customizr 3.3.+
  *
  */
  function czr_fn_set_demo_slide_data( $slide, $id ) {
    switch ( $id ) {
      case 1 :
        $slide['title']        = __( 'Discover how to replace or remove this demo slider.', 'customizr' );
        $slide['link_url']     = implode('/', array('http:/','docs.presscustomizr.com' , 'article', '102-customizr-theme-options-front-page/#front-page-slider' ) ); //do we need an anchor in the doc?
        $slide['button_text']  = __( 'Check the front page slider doc &raquo;' , 'customizr');
      break;
      case 2 :
        $slide['title']        = __( 'Easily create sliders and add them in any posts or pages.', 'customizr' );
        $slide['link_url']     = implode('/', array('http:/','docs.presscustomizr.com' , 'article', '3-creating-a-slider-with-customizr-wordpress-theme' ) );
        $slide['button_text']  = __( 'Check the slider doc now &raquo;' , 'customizr');
      break;
    };
    return $slide;
  }



  /**
  * parse this model properties for rendering
  */
  function czr_fn_sanitize_model_properties( $model ) {
    parent::czr_fn_sanitize_model_properties( $model );
    foreach ( array( 'caption', 'text', 'title', 'button' ) as $property ) {
      $model -> {"{$property}_class"} = $this -> czr_fn_stringify_model_property( "{$property}_class" );
    }
  }

}//end class

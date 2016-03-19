<?php
class TC_grid_wrapper_model_class extends TC_article_model_class {
  public $is_first_of_row;
  public $is_last_of_row;
  public $section_cols;
//  public $text;

  private $expanded_sticky = false;

  private $post_id;

  /* override */
  function __construct( $model ) {
    //Fires the parent constructor
    parent::__construct( $model );

    $this -> post_id = TC_utils::tc_id();

    //inside the loop but before rendering set some properties
    add_action( $this -> hook, array( $this, 'set_this_properties' ), 0 );
  }

  function set_this_properties() {
    //
    $section_wrapper = $this -> tc_get_section_wrapper_params();
    $this -> tc_update( $section_wrapper );
  }


  /*
  * Wrap articles in a grid section
  */
  function tc_get_section_wrapper_params() {
    global $wp_query;
    
    $current_post      = $wp_query -> current_post;
    $start_post        = $this -> expanded_sticky ? 1 : 0;
    $cols              = $this -> tc_get_grid_section_cols();

    $is_first_of_row = false;
    $is_last_of_row  = false;
    $section_cols    = '';

    if ( $start_post == $current_post || 0 == ( $current_post - $start_post ) % $cols ) {
      $is_first_of_row = true;
      $section_cols    = $cols;
    }
    if ( $wp_query->post_count == ( $current_post + 1 ) || 0 == ( ( $current_post - $start_post + 1 ) % $cols ) )
      $is_last_of_row  = true;

    return compact( 'is_first_of_row', 'is_last_of_row', 'section_cols' );
  }

  /* retrieves number of cols option, and wrap it into a filter */
  private function tc_get_grid_cols() {
    return apply_filters( 'tc_get_grid_cols',
      esc_attr( TC_utils::$inst->tc_opt( 'tc_grid_columns') ),
      TC_utils::tc_get_layout( $this -> post_id , 'class' )
    );
  }

  /* returns articles wrapper section columns */
  private function tc_get_grid_section_cols() {
    return apply_filters( 'tc_grid_section_cols',
      $this -> tc_force_current_post_expansion() ? '1' : $this -> tc_get_grid_cols()
    );
  }

  /*
  * @return bool
  * returns if the current post is the expanded one
  */
  private function tc_force_current_post_expansion(){
    global $wp_query;
    return ( $this -> expanded_sticky && 0 == $wp_query -> current_post );
  }

}

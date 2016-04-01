<?php
class TC_no_results_model_class extends TC_article_model_class {
  public $wrapper_class;
  public $inner_class;
  public $title_class;

  /*
  * @override
  * fired before the model properties are parsed
  *
  * return model params array()
  */
  function tc_extend_params( $model = array() ) {
    $model                                = parent::tc_extend_params( $model );
    $model[ 'wrapper_class' ]             = apply_filters( 'tc_no_results_wrapper_class', array('tc-content', 'span12', 'format-quote' ) );
    $model[ 'inner_class' ]               = array( 'entry-content', apply_filters( 'tc_no_results_content_icon', 'format-icon') );
    $model[ 'title_class' ]               = array_merge( apply_filters( 'tc_archive_icon', ( esc_attr( TC_utils::$inst->tc_opt( 'tc_show_archive_title_icon' ) )
          && esc_attr( TC_utils::$inst->tc_opt( 'tc_show_title_icon' ) ) ) ? array( 'format-icon' ) : array() ), array('entry-title') );

    return $model;
  }

  function tc_get_the_article_selectors() {
    $selectors = apply_filters( 'tc_article_selectors', apply_filters( 'tc_no_results_selectors' , 'id="post-0" class="post error404 no-results not-found row-fluid"' ) );

    return $selectors;
  }

  /**
  * parse this model properties for rendering
  */
  function tc_sanitize_model_properties( $model ) {
    parent::tc_sanitize_model_properties( $model );
    foreach ( array('wrapper', 'inner', 'title' ) as $property )
      $model -> {"{$property}_class"} = $this -> tc_stringify_model_property( "{$property}_class" );
  }
}

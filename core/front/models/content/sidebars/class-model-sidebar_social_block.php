<?php
class CZR_cl_sidebar_social_block_model_class extends CZR_cl_social_block_model_class {
  public $element_tag        = 'aside';
  /*
  * @override
  */
  function czr_fn_get_socials_where( $model ) {
    $this -> where = strpos( $model['hook'], 'right' ) > 0 ? 'right-sidebar' : 'left-sidebar';
    parent::czr_fn_get_socials_where( $model );
  }

  /*
  * @override
  */
  function czr_fn_get_before_socials() {
    $_title = esc_attr( CZR_cl_utils::$inst->czr_fn_opt( 'tc_social_in_sidebar_title') );
    return ! $_title ? '' : apply_filters( 'tc_sidebar_socials_title' , sprintf( '<h3 class="widget-title">%1$s</h3>', $_title ) );
  }

  /*
  * @override
  */
  function czr_fn_social_block_get_class( $model ) {
    return apply_filters( 'tc_sidebar_block_social_class' , array( 'widget', 'widget_social' ), $model );
  }
}

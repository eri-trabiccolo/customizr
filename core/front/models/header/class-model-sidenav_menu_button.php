<?php
class CZR_cl_sidenav_menu_button_model_class extends CZR_cl_menu_button_model_class {

  /*
  * @override
  * fired before the model properties are parsed
  *
  * return model params array()
  */
  function czr_fn_extend_params( $model = array() ) {
    $model = parent::czr_fn_extend_params( $model );

    array_push( $model[ 'element_class' ], 'sn-toggle' );
    //@todo fix this condition
    if ( 'sidenav_menu_button' == $model['id'] ) {
      $_close_message             = __('Close', 'customizr' );
      //button label
      $model[ 'button_label' ]    = $model[ 'button_label' ] ? sprintf( '<span class="menu-label">%s</span>', $_close_message ) : '';
      //button title
      $model[ 'button_title' ]    =  $_close_message;
    }

    $model['button_attr']        = '';
    return $model;
  }
}

<?php
class CZR_cl_post_list_header_model_class extends CZR_cl_Model {
  public $has_header_format_icon;

  function czr_fn_setup_late_properties() {
    $this -> czr_fn_set_property( 'has_header_format_icon', czr_fn_get( 'has_header_format_icon' ) );
  }

}
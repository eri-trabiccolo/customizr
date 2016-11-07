<?php
class CZR_cl_post_page_headings_model_class extends CZR_cl_Model {
  public $thumbnail_position;

  function czr_fn_extend_params( $model = array() ) {
    $model[ 'thumbnail_position' ] = '__after_content_title' == czr_fn_get_single_thumbnail_position() ? 'after_title' : '';

    return $model;
  }

  function czr_fn_setup_children() {
    $children = array (
      array(
        'id' => 'post_page_title',
        'model_class' => 'content/singles/post_page_title'
      ),
    );
    return $children;
  }
}

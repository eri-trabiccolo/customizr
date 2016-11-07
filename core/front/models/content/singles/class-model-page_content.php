<?php
class CZR_cl_page_content_model_class extends CZR_cl_Model {

  function czr_fn_setup_children() {
    $children = array(
      //singular smartload help block
      array(
        'hook'        => '__before_page_entry_content',
        'template'    => 'modules/help_block',
        'id'          => 'singular_smartload_help_block',
        'model_class' => array( 'parent' => 'modules/help_block', 'name' => 'modules/singular_smartload_help_block'),
        'priority'    => 20
      )
    );
    return $children;
  }

  function czr_fn_get_article_selectors( $model = array() ) {
    return czr_fn_get_the_singular_article_selectors( 'row-fluid' );
  }
}

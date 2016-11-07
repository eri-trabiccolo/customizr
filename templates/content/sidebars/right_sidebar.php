<?php
/*
* Template for dislplaying the right sidebar
*/
?>
<div class="right tc-sidebar <?php czr_fn_echo('element_class') /* the width depends on the layout see the sidebar model*/ ?>" <?php czr_fn_echo('element_attributes') ?> >
  <div id="right" class="widget-area" role="complementary">
    <?php if ( czr_fn_has( 'right_sidebar_social_block' ) )
      czr_fn_render_template('modules/social_block', 'right_sidebar_social_block');
    ?>
    <?php do_action( '__before_inner_right_sidebar' ) ?>
    <?php dynamic_sidebar( 'right' ) ?>
    <?php do_action( '__after_inner_right_sidebar' ) ?>
  </div>
</div>

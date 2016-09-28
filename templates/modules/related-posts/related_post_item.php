<?php
/*
*
*/
?>
<article <?php czr_fn_echo('article_selectors') ?> <?php czr_fn_echo('element_attributes') ?>>
  <div class="grid-post tc-grid-post">
    <?php
      if ( czr_fn_has('media') ) { czr_fn_render_template('content/post-lists/post_list_media', 'post_list_media'); }
      if ( czr_fn_has('content') ) { czr_fn_render_template('content/post-lists/post_list_content', 'post_list_content'); }
    ?>
  </div>
</article>
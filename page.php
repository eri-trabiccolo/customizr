<?php
/**
 * The template for displaying pages
 *
 */
?>
<?php get_header() ?>

  <?php

    /* SLIDERS : standard or slider of posts */
    if ( czr_fn_has('main_slider') ) {
      czr_fn_render_template('modules/slider/slider', 'main_slider');
    }

    if( czr_fn_has( 'main_posts_slider' ) ) {
      czr_fn_render_template('modules/slider/slider', 'main_posts_slider');
    }

  ?>

  <?php do_action('__before_main_wrapper'); ?>
    <div id="main-wrapper" class="section">

      <?php do_action('__before_main_container'); ?>

      <div class="container" role="main">

        <?php if ( czr_fn_has('breadcrumb') ) { czr_fn_render_template('modules/breadcrumb'); } ?>

        <div class="<?php czr_fn_column_content_wrapper_class() ?>">

          <?php do_action('__before_content'); ?>

          <div id="content" class="<?php czr_fn_article_container_class() ?>">
            <?php
              if ( have_posts() ) {
                while ( have_posts() ) {
                  the_post();
                  czr_fn_render_template('content/singular/page_content');
                }//endwhile;
              }//endif;
            ?>
          </div>
          <?php do_action('__after_content'); ?>

          <?php

            if ( czr_fn_has('left_sidebar') ) {
              czr_fn_render_template('content/sidebars/left_sidebar', 'left_sidebar');
            }

          ?>
          <?php

            if ( czr_fn_has('right_sidebar') ) {
              czr_fn_render_template('content/sidebars/right_sidebar', 'right_sidebar');
            }

          ?>
        </div><!-- .column-content-wrapper -->

        <?php if ( czr_fn_has('comments') ) : ?>
          <div class="row">
            <div class="col-xs-12">
              <?php czr_fn_render_template('content/comments/comments'); ?>
            </div>
          </div>
        <?php endif ?>
      </div><!-- .container -->

      <?php do_action('__after_main_container'); ?>

    </div><!-- #main-wrapper -->

    <?php do_action('__after_main_wrapper'); ?>

    <?php
      if ( czr_fn_has('post_navigation') ) :
    ?>
      <div class="container-fluid">
        <div class="row">
        <?php czr_fn_render_template('content/singular/post_navigation_singular', 'post_navigation');  ?>
        </div>
      </div>
    <?php endif ?>

<?php get_footer() ?>
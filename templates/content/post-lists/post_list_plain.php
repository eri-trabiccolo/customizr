<?php
/**
 * The template for displaying the article wrapper in a post list context
 *
 * In WP loop
 *
 * @package Customizr
 */
?>
<?php if ( czr_fn_is_loop_start() ) : ?>
<div class="grid-container__plain <?php czr_fn_echo('element_class') ?>" <?php czr_fn_echo('element_attributes') ?>>
<?php
  do_action( '__post_list_plain_loop_start', czr_fn_get('id') );
endif ?>
  <article <?php czr_fn_echo( 'article_selectors' ) ?> >
    <div class="sections-wrapper grid__item <?php czr_fn_echo( 'sections_wrapper_class' ) ?>">
      <?php
        if ( ( $has_post_media = czr_fn_get('has_post_media') ) && czr_fn_has('media') ) {
          czr_fn_render_template('content/post-lists/singles/post_list_single_media', 'post_list_media', array(
             'has_post_media'           => $has_post_media,
             'is_full_image'            => czr_fn_get( 'is_full_image' )
            )
          );
        }
      ?>
      <section class="tc-content entry-content__holder">
        <?php
        /*
        * Get the category list if any
        * impacts on inner layout
        */
        if ( czr_fn_has('post_metas') )
         $cat_list = czr_fn_get( 'cat_list', 'post_metas');

        if ( czr_fn_has('post_list_header') )
          czr_fn_render_template('content/post-lists/singles/headings/post_list_single_header-no_metas', 'post_list_header', array(
            'entry_header_inner_class' => $cat_list ? czr_fn_get( 'entry_header_inner_class' ) : array('col-xs-12'),
            'element_class'            => czr_fn_get( 'entry_header_class' )
          ));
        ?>
        <div class="entry-content__wrapper row <?php czr_fn_echo('inner_wrapper_class') ?>">
          <?php
          if ( $cat_list ) :
          ?>
            <div class="entry-meta tax__container col-md-3 col-xs-12 small caps">
              <?php echo $cat_list ?>
            </div>

          <?php
          endif;
          /* Content Inner */
          ?>
          <div class="tc-content-inner-wrapper <?php
            if ( $cat_list )
              czr_fn_echo( 'content_inner_class' );
            else
              echo 'col-xs-12';
            ?>" >
            <?php
            /* Content Inner */
            czr_fn_render_template('content/post-lists/singles/contents/post_list_single_content_inner', 'post_list_content_inner',
              array(
                $show_full_content = true,
              )
            );

            if ( czr_fn_has('post_metas') && (bool) $tag_list = czr_fn_get( 'tag_list', 'post_metas' ) ) : ?>
              <div class="entry-meta post-tags">
                <ul class="tags">
                  <?php echo $tag_list ?>
                </ul>
              </div>
            <?php endif; ?>
              <div class="post-share">
                <!-- fake need to have social links somewhere -->
                <ul class="socials">
                  <li><a href="http://facebook.com/"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="http://linkedin.com/"><i class="fa fa-linkedin"></i></a></li>
                  <li><a href="http://twitter.com/"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="http://plus.google.com/"><i class="fa fa-instagram"></i></a></li>
                  <li><a href="http://plus.google.com/"><i class="fa fa-pinterest"></i></a></li>
                  <li><a href="http://plus.google.com/"><i class="fa fa-google-plus"></i> </a></li>
                </ul>
              </div>
          </div>
        </div>
        <?php if ( czr_fn_has('post_list_footer') ) czr_fn_render_template('content/post-lists/singles/footers/post_list_single_footer_author', 'post_list_footer' ); ?>
      </section>
    </div>
  </article>
<?php if ( czr_fn_is_loop_end() ) :
  do_action( '__post_list_plain_loop_start', czr_fn_get('id') );
?>
</div>
<?php endif;
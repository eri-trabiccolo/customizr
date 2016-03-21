<figcaption class="tc-grid-excerpt">
  <div class="entry-summary">
    <div class="tc-g-cont"><?php the_excerpt() ?></div>
    <?php if ( isset( $grid_caption_model -> has_heading_in_caption ) ) : ?>
      <h2 class="entry-title"><?php the_title() ?></h2>
    <?php endif ?>
  </div>
  <a class="tc-grid-bg-link" href="<?php the_permalink() ?>" title="<?php esc_attr( strip_tags( get_the_title() ) ) ?>"></a>
  <span class="tc-grid-fade_expt"></span>
</figcaption>

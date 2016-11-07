<?php

//a generic loop that can be used to display WP query

global $wp_query, $_query, $wp_the_query;
//do we have a custom query ?
if ( false !== czr_fn_get( 'query' ) ) {
  $_query = new WP_Query( czr_fn_get( 'query' ) );
  $wp_query = $_query;
}
do_action( '__before_tc_loop', czr_fn_get( 'id' ) );
if ( have_posts() && ! is_404() ) {
  while ( have_posts() ) {
    the_post();
    //if this is the main wp loop then, render the various templates depending on the context and the user options
    if ( 'main_loop' == czr_fn_get('id') ) {

      if ( czr_fn_has('post_list_grid') ) {
        czr_fn_render_template('modules/grid/grid_wrapper', 'post_list_grid');
      }

      elseif ( czr_fn_has('post_list') ){
        czr_fn_render_template('content/post-lists/post_list_wrapper', 'post_list');
      }

      elseif ( czr_fn_has('singular_article') ) {
        czr_fn_render_template('content/singles/article','singular_article');
      }
    }
    else {
      //if this is a custom loop, use a dynamic action hook to load a custom query loop
      do_action( 'in_' . czr_fn_get('id') );
    }

  }//endwhile;
}
do_action( '__after_tc_loop', czr_fn_get( 'id' ) );
//Reset the query to the main WP one if needed
if ( false !== czr_fn_get( 'query' ) ) {
  $wp_query = $wp_the_query;
  wp_reset_postdata();
}

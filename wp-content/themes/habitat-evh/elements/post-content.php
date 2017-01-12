<?php

/**
 * Post Content Shortcode for VC Grid Element
 */

add_filter( 'vc_grid_item_shortcodes', 'my_module_add_grid_shortcodes' );
function my_module_add_grid_shortcodes( $shortcodes ) {
  $shortcodes['evh_post_content'] = array(
    'name' => __( 'Post Content', 'my-text-domain' ),
    'base' => 'evh_post_content',
    'icon' => 'vc_icon-vc-gitem-post-excerpt',
    'category' => __( 'Post', 'my-text-domain' ),
    'description' => __( 'Show current post content', 'my-text-domain' ),
    'post_type' => 'vc_grid_item',
  );

  return $shortcodes;
}

 // output function
add_shortcode( 'evh_post_content', 'evh_post_content_render' );
function evh_post_content_render() { // New function parameter $content is added!
  $output .= '<div class="entry-content">';
  $output .= apply_filters('the_content', "{{ post_data: post_content }}");
  $output .= '</div>';

	return $output;
}

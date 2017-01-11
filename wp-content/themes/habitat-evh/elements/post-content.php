<?php

/**
 * Program Shortcode
 */


// [progression_program heading_pro="" program_description_pro="" etc...]
add_shortcode( 'evh_post_content', 'evh_post_content_func' );
function evh_post_content_func( $atts, $content = null ) { // New function parameter $content is added!

  global $post;

	ob_start();
  ?>

    <div class="entry-content">
      {{ post_data: post_content }}
    </div>

	<?php
	return ob_get_clean();
}

add_action( 'vc_before_init', 'evh_post_content_integrateVC' );
function evh_post_content_integrateVC() {
   vc_map( array(
		'name' => __( 'Post Content', 'js_composer' ),
		'base' => 'evh_post_content',
		'icon' => 'vc_icon-vc-gitem-post-excerpt',
		'category' => __( 'Post', 'js_composer' ),
		'description' => __( 'Full post content', 'js_composer' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'js_composer' ),
				'param_name' => 'el_class',
				'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
			)
		),
    'post_type' => 'vc_grid_item'
		// 'post_type' => Vc_Grid_Item_Editor::postType()
   ) );
}

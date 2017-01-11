<?php
//
// Recommended way to include parent theme styles.
//  (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//
add_action( 'wp_enqueue_scripts', 'habitat_progression_studios_enqueue_styles' );
function habitat_progression_studios_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}
//
// Your code goes below
//

// Custom Grid Elements
if( function_exists( 'vc_manager' ) ) {
	require_once( get_stylesheet_directory() . '/elements/post-content.php' );
}

// Change order of programs on home page
 add_action( 'pre_get_posts', 'evh_programs_order' );
 function evh_programs_order($query) {
   if ($query->get('post_type') == 'program') {
     $query->set('orderby', 'menu_order');
     $query->set('order', 'ASC');
   }
 }

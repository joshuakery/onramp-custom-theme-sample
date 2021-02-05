<?php /*

  This file is part of a child theme called Astra Child.
  Functions in this file will be loaded before the parent theme's functions.
  For more information, please read
  https://developer.wordpress.org/themes/advanced-topics/child-themes/

*/

// this code loads the parent's stylesheet (leave it in place unless you know what you're doing)


function your_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, 
      get_template_directory_uri() . '/style.css'); 

    wp_enqueue_style( 'child-style', 
      get_stylesheet_directory_uri() . '/style.css', 
      array($parent_style), 
      wp_get_theme()->get('Version') 
    );
}

add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');

/*  Add your own functions below this line.
    ======================================== */ 
    
include('custom-shortcodes.php');
    
require_once('custom-widgets/my-widgets.php');
    
function my_theme_scripts() {
    wp_register_script( 'widget1_v3', get_stylesheet_directory_uri() .
                    '/js/widget1_v3.js' );
    wp_localize_script( 'widget1_v3', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script( 'widget1_v3' );
    
    
    if ( is_front_page() ) {
        wp_enqueue_script( 'front_page_v3', get_stylesheet_directory_uri() . '/js/front_page_v3.js', array( 'jquery' ), '1.0.0', true );
        if (isset($_REQUEST) && isset($_REQUEST['towheel']) && $_REQUEST['towheel'] == 'true' ) {
            wp_enqueue_script( 'scroll_to_wheel', get_stylesheet_directory_uri() . '/js/scroll_to_wheel.js', array( 'jquery' ), '1.0.0', true );
        }
    } else {
        /** Call regular enqueue */
    }

}
add_action( 'wp_enqueue_scripts', 'my_theme_scripts' );

function orc_add_google_fonts() {
   wp_enqueue_style( 'orc-google-fonts', 'https://fonts.googleapis.com/css2?family=Jaldi:wght@400;700&display=swap', false );
}
add_action( 'wp_enqueue_scripts', 'orc_add_google_fonts' );







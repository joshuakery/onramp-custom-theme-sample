<?php

/* OnRamp Custom Shortcodes */
add_shortcode('onramp_splash', function( $atts = array() ){
       $a = shortcode_atts( array(
            'title' => '',
            'body' => '',
            'centerimageurl' => '',
            'image1url' => '',
            'image2url' => '',
            
    	), $atts );  
    	
    	$output = '';
    	
    	$output .= '<div class="onramp_splash_container">';
    	
    	$output .= '<div class="onramp_splash_image1">' .
    	           '<img src="'.$a['image1url'].'"/>' .
    	           '</div>';
    	           
        $output .= '<div class="onramp_splash_image2">' .
    	           '<img src="'.$a['image2url'].'"/>' .
    	           '</div>';
    	           
    	$output .= '<div class="onramp_splash_center_container">' .
    	           '<div class="onramp_splash_center">' .
    	           '<img src="'.$a['centerimageurl'].'"/>' .
    	           '<h1>'.$a['title'].'</h1>' .
    	           '<div class="onramp_splash_desktop_body">'.$a['body'].'</div>' .
    	           '</div>' .
    	           '</div>';
    	           
	    $output .= '<div class="onramp_splash_mobile_body">'.$a['body'].'</div>' ;
    	
    	$output .= '</div>';
    	
    	return $output;
});

add_shortcode('onramp_spacer', function( $atts = array() ){
    $a = shortcode_atts( array(
        'unique_id' => null,
    ), $atts );
    
    $output = '';
    $output .= '<div class="onramp_spacer">';
    if ($a['unique_id']) {
        $output .= '<input type="hidden" value="' . $a['unique_id'] . '" />';
    }
    $output .= '</div>';
    return $output;
    
});

add_shortcode('category_title', function( $atts = array() ){
       $a = shortcode_atts( array(
            'slug'     => null,
            'fontsize' => '1em',
    	), $atts );  
    	
    	$category = get_category_by_slug($a['slug']);
    	
    	$output = '';
    	$output .= '<div class="orc-category-title wheel_color_border_'.
    	            $category->slug .
    	            '">';
    	$output .= '<div class="orc-category-title-image-container wheel_color_'.$category->slug.'"><img src="' .
                    get_stylesheet_directory_uri() .
                    '/img/icon_' . $category->slug . '.png' .
                    '"></div>';
        $output .= '<p
            style="font-size:'.$a['fontsize'].'"
            class="wheel_color_text_' . 
            $category->slug .
            '">' .
                $category->name .
            '</p>';         
    	$output .= '</div>';
    	return $output;
});

add_shortcode('wheel_header', function(){
    include_once 'shortcodes/onramp-custom-wheel-header.php';
});

// add_shortcode('public_events', function() {
//     $output = \EM_Events::output(array('event-is-open'=>'yes'));
//     return $output;
// });

add_shortcode('see_more', function( $atts = array(), $content = null ) {
    // set up default parameters
    $a = shortcode_atts( array(
        'tab'     => null,
	), $atts ); 
	
	$output = '';
	if ($a['tab']) {
	    $output .= '<div class="see_more_container">';
	    $output .= '<button class="see_more" onclick="seeContent(event,'.$a['tab'].')">' .
	               $content .
	               '</button>';
	   $output .= '</div>';
	}
	return $output;
	
});

add_shortcode('start_button', function( $atts = array(), $content = null ) {
    
        // set up default parameters
        $a = shortcode_atts( array(
            'unique_id'     => null,
            'hide_self'     => 0,
    		'scroll_target' => null,
    		'scroll_time'   => 400
    	), $atts );
    	
    	$output = '';
    	$output .= '<div class="landing_breadcrumbs">' .
                    '<h3>I want to learn more about...</h3>' .
                    '<div class="wheel_cat_breadcrumb landing_breadcrumb hide">' .
                    '<div class="breadcrumb_image"></div>' .
                    '<h3>which principle?</h3>' .
                    '</div>' .
                    '<div class="content-type_breadcrumb landing_breadcrumb hide">' .
                    '<div class="breadcrumb_image"></div>' .
                    '<h3>how?</h3>' .
                    '</div>' .
                   '</div>';
                   
        $output .= '<form class="submit_container"
                          id="submit_decision_forks" 
                          action="'.esc_url( get_permalink( get_page_by_title( 'All Courses' ) ) ).'"
                          method="get"
                    ></form>';
                    
        $output .= '<div class="loader hide"><h3>loading...</h3></div>';
        
	    $output .= '<div class="scroll_to_next_container"><button class="scroll_to_next ';
	    if ($a['hide']==1) $output .= 'hidden';
        $output .= '"';
        
	    if (!is_null($a['unique_id'])) {
	        $output .= ' id="'.$a['unique_id'].'"';
	    }
	    
	    $output .= ' onclick="startLearning({event:event,
	                                         element:this,';
	    if (!is_null($a['scroll_target'])) {
	        $output .= 'scroll:{
	            target: ' . $a['scroll_target'] . ',
	            time: ' . $a['scroll_time'] . ',
	        }';
	    }
	    $output .= '})" ';

	    $output .= '>';
	    $output .= $content;
        $output .= '</button></div>';
    	
    	return $output;
    	
});

add_shortcode('scroll_to_next_button', function( $atts = array(), $content = null) {
    
        // set up default parameters
        $atts = shortcode_atts( array(
            'unique_id' => null,
    		'target' => null,
    		'hide' => 0,
    		'time' => 400
    	), $atts );
    	
	    $output = '<div class="scroll_to_next_container"><button class="scroll_to_next ';
	    if ($atts['hide']==1) $output .= 'hidden';
        $output .= '"';
	    if (!is_null($atts['unique_id'])) {
	        $output .= ' id="'.$atts['unique_id'].'"';
	    }
	    if (!is_null($atts['target'])) {
	        $output .= ' onclick="scrollToTop(\''.$atts['target'].'\','.$atts['time'].')"';
	    }
	    $output .= '>';
	    $output .= $content;
        $output .= '</button></div>';
        return $output;
    	
});

add_shortcode('submit_decision_forks', function($atts=array(),$content=null){
    
    // set up default parameters
    $atts = shortcode_atts( array(
        'unique_id' => null,
		'show_next' => null,
		'scroll_to_next' => null,
		'time' => 400,
		'call_to_action' => '',
		'hide' => 0,
	), $atts );
	
    $output = '<form class="submit_container" id="submit_decision_forks" 
                     action="'.esc_url( get_permalink( get_page_by_title( 'All Courses' ) ) ).'"
                     method="get"
               >
                    <label for="decision_forks_submit">
                        <input type="submit" name="decision_forks_submit" class="submit_btn" value="'.$content.'"/>
                        <div class="submit_btn_indicator"></div>
                    </label>
                </form><!--END SUBMIT CONTAINER-->';
    return $output;
});

add_shortcode('wheel_category_tablet_choose_this', function( $atts = array() ) {
    
    // set up default parameters
    $a = shortcode_atts( array(
		'scroll_to_next' => null,
		'time' => 400,
	), $atts );
    
    //include necessary js
	wp_register_script( 'onramp-custom-wheel-category-tablet-choose-this', get_stylesheet_directory_uri() . '/js/onramp-custom-wheel-category-tablet-choose-this.js' );
    wp_localize_script( 'onramp-custom-wheel-category-tablet-choose-this', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script( 'onramp-custom-wheel-category-tablet-choose-this' );
    
    $categories = get_categories( array(
            'meta_key' => 'category_order',
	        'orderby'  => 'meta_value',
            'order'   => 'ASC',
            'parent' => 0
        ) );
        
    $output = '';
    
    //choose this button for tablet
    $output .= '<div class="tablet_choose_this_container">';
    $count = 0;
    foreach ( $categories as $category ) {
        if ($category->slug == "uncategorized") {
            continue;
        }
        $choose_this = '';
        $choose_this .= '<div class="tablet_choose_this ' .
                     (($count != 0) ? ' hide ' : '') .
                     ' wheel_color_border_' . $category->slug .
                     
                     '" id="tablet_choose_this_' . $category->slug . '">';
        $choose_this .= '<button class="' .
                    ' wheel_color_border_' . $category->slug .
                    ' wheel_color_text_' . $category->slug . '"';
        $choose_this .= ' onclick="
                        chooseWheelCat({event:event,
                                        element:this,
                                        onMobile:true,
                                        hInput:{
                                            name:\'wheel_cat\',
                                            value:\'' . $category->slug . '\'
                                        },';
        if (!is_null($a['scroll_to_next'])) {
            $choose_this .= 'scroll:{
                                target: \''.$a['scroll_to_next'].'\',
                                time: ' . $a['time'] . '
                            },';
        }
        $choose_this .= '})"';
        $choose_this .= '>' .
                    'CHOOSE THIS' .
                    '</button>';
        $choose_this .= '</div>';
        $output .= $choose_this;
        $count += 1;
    }
    $output .= '</div><!--END TABLET CHOOSE THIS CONTAINER-->';
    
    return $output;
    
});

add_shortcode('wheel_category_overview', function() {
    
	//include necessary js
	wp_register_script( 'onramp-custom-wheel-category-overview', get_stylesheet_directory_uri() . '/js/onramp-custom-wheel-category-overview.js' );
    wp_localize_script( 'onramp-custom-wheel-category-overview', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script( 'onramp-custom-wheel-category-overview' );
    
    $categories = get_categories( array(
            'meta_key' => 'category_order',
	        'orderby'  => 'meta_value',
            'order'   => 'ASC',
            'parent' => 0
        ) );
        
    $output = '';
        
    //overview container for desktop
    $output .= '<div class="overview_container">';
    $count = 0;
    foreach ( $categories as $category ) {
        if ($category->slug == "uncategorized") {
            continue;
        }
        $overview = '';
        $overview .= '<div class="overview ' .
                     (($count != 0) ? ' hide ' : '') .
                     ' wheel_color_border_' . $category->slug .
                     
                     '" id="overview_' . $category->slug . '">';
        $overview .= '<h3 class="overview_title' .
                    ' wheel_color_text_' .
                    $category->slug . '">' .
                        $category->name .
                    '</h3>';
        $overview .= '<p class="overview_description">'.$category->description.'</p>';
        $overview .= '</div>';
        $output .= $overview;
        $count += 1;
    }
    $output .= '</div><!--END OVERVIEW CONTAINER-->';
    
    return $output;
});

add_shortcode('wheel_decision_fork', function( $atts = array(), $content = null ){
    
    // set up default parameters
    $a = shortcode_atts( array(
        'unique_id' => null,
        'clickable' => 1,
		'scroll_to_next' => null,
		'time' => 400,
		'call_to_action' => '',
		'hide' => 0,
	), $atts );
	
	//include necessary js
	wp_register_script( 'onramp-custom-wheel-decision-fork', get_stylesheet_directory_uri() . '/js/onramp-custom-wheel-decision-fork.js' );
    wp_localize_script( 'onramp-custom-wheel-decision-fork', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script( 'onramp-custom-wheel-decision-fork' );
	
	$output = '';
    $output .= '<div class="home_wheel_decision_fork_container';
    if ($a['hide'] == 1) $output .= ' hidden ';
    $output .= '"';
    if (!is_null($a['unique_id'])) $output .= ' id="'.$a['unique_id'].'"';
    $output .= '>';
    
    // $output .= '<div class="filters_info">';
    // $output .= '<h1>'.$content.'</h1>';
    // if ($a['call_to_action'] != '') $output .= '<p>'.$a['call_to_action'].'</p>';
    // $output .= '</div>';
    
    $output .= '<div class="home_wheel_container">';
                    
    $output .= '<div class="home_wheel_background_container">
                    <div class="home_wheel_background">
                        <img src="' . get_stylesheet_directory_uri().'/img/design_principles_ring.png' . '">
                    </div>
                </div>';
                    
    $output .= '<div class="home_wheel">';

    $categories = get_categories( array(
            'meta_key' => 'category_order',
	        'orderby'  => 'meta_value',
            'order'   => 'ASC',
            'parent' => 0
        ) );
    foreach( $categories as $category ) {
        
        if ($category->slug == "uncategorized") {
            continue;
        }
        
        //construct label and input elements
        $output .= '<div class="home_wheel_slice wheel_color_' . $category->slug . '"><label class="home_wheel_slice_contents wheel_color_' . $category->slug . '">';
        // $output .= '<span class="before">';
        $output .= '<input ';
        if ($_REQUEST["wheel_categories"] == $category->slug) {
            $output .= 'checked ';
        }
        $output .= 'type="radio" 
             id="wheel_radio_btn_' . $category->slug . '"  
             name="wheel_cat" 
             value="' . $category->slug . '" 
             class="home_wheel_radio" ';
             
        //onclick function for input element
        if ($a['clickable'] == 1) {
            $output .= 'onchange="
                chooseWheelCat({event:event,
                                element:this,
                                onMobile:false,
                                hInput:{
                                    name:\'wheel_cat\',
                                    value:\'' . $category->slug . '\'
                                },';
                              
                if (!is_null($a['scroll_to_next'])) {
                    $output .= 'scroll:{
                                    target: \''.$a['scroll_to_next'].'\',
                                    time: ' . $a['time'] . '
                                },';
                }     
        
            $output .= '})"';
        }

        $output .= '>'; //end input element opening tag
        
        //add icon to input
        $output .= '<div class="home_wheel_icon"><img src="' .
            get_stylesheet_directory_uri() .
            '/img/icon_' . $category->slug . '.png' .
            '"></div>';
            
        //for mobile, add title
        $output .=  '<div class="home_wheel_title orc_mobile">'.
                    $category->name .
                    '</div>';
        //for mobile, add show more button instead of overview div hover
        $output .= '<div class="home_wheel_show_more orc_mobile">' .
                    '<button onclick="">' .
                    'SHOW MORE' .
                    '</button>' .
                    '</div>';
        //for mobile, feedback user choice this way
        $output .= '<div class="home_wheel_check_confirmation">' .
                   '<img src="' . get_stylesheet_directory_uri().'/img/circle-checkmark.png' . '" />' .
                   '</div>';
        //for mobile add a description of cat instead of overview div
        $output .=  '<div class="home_wheel_description orc_mobile home_wheel_more_item">'.
                    $category->description .
                    '</div>';
        //for mobile, add show less button instead of overview div hover
        $output .= '<div class="home_wheel_show_less orc_mobile home_wheel_more_item">' .
                    '<button onclick="">' .
                    'SHOW LESS' .
                    '</button>' .
                    '</div>';
                    
        $output .= '<button class="home_wheel_slice_button" onclick="orcMobileToggleShow(this)"></button>';
        //for mobile, add choice button instead of overview div onclick
        if ($a['clickable'] == 1) { 
            $output .= '<div class="home_wheel_choose_this orc_mobile home_wheel_more_item">';
            $output .= '<button ';
            $output .= 'onclick="
                    chooseWheelCat({event:event,
                                    element:this,
                                    onMobile:true,
                                    hInput:{
                                        name:\'wheel_cat\',
                                        value:\'' . $category->slug . '\'
                                    },';
                                  
                    if (!is_null($a['scroll_to_next'])) {
                        $output .= 'scroll:{
                                        target: \''.$a['scroll_to_next'].'\',
                                        time: ' . $a['time'] . '
                                    },';
                    }     
            
            $output .= '})"';
            $output .=  '">';
            $output .=  'CHOOSE THIS' .
                        '</button>' .
                        '</div>';
        }
        // $output .= '<span class="after">';
        $output .= '</label></div>'; //closing tag for label and container
        $output .= '';
        
    }
    
    //aesthetic only, the elements to draw cuts into the wheel slices
    foreach ( $categories as $category ) {
        if ($category->slug == "uncategorized") {
            continue;
        }
        $output .= '<div class="home_wheel_cut"><div class="home_wheel_cut_contents"></div></div>';
    }
                        

    $output .= '</div>'; //end .home_wheel div
      
    //wheel center              
    $output .= '<div class="home_wheel_center_container">
                    <div class="home_wheel_center">
                        <img src="' . get_stylesheet_directory_uri().'/img/wheel_center_icon.png' .'">
                    </div>
                </div>';
                
    //no-selection div in case we ever need it
    $output .= '<div class="home_no_selection_container">
                    <label class="home_no_selection_contents">
                        <p>No Selection</p>
                        <input type="radio"
                               id="no_selection"
                               name="wheel_categories"
                               value="no_selection"
                               class="wheel_no_selection_radio">
                        <span class="home_wheel_checkmark"></span>
                    </label>
                </div>';
                    
    $output .= '</div><!--END WHEEL CONTAINER-->';
    
    $output .= '</div><!--END WHEEL DECISION FORK CONTAINER-->';

    return $output;
});

add_shortcode('content_type_decision_fork', function( $atts = array() ){
    
    // set up default parameters
    $atts = shortcode_atts( array(
        'unique_id' => null,
		'title' => '',
		'submit_form' => 'false',
		'hide_description' => 'false',
	), $atts );
    
    $output = '';
    $output .= '<div class="decision_fork_container"';
    if (!is_null($atts['unique_id'])) $output .= ' id="'.$atts['unique_id'].'"';
    $output .= '>';
    $output .= '<h2>'.$atts['title'].'</h2>';
    
    $menu_items = wp_get_nav_menu_items('landing-page-content-type-decision');
    
    $output .= '<div class="decision_forks">';
    if ($menu_items) {
        foreach ($menu_items as $item) {
            $output .= '<div class="decision_fork">';
            $postID = get_post_meta( $item->ID, '_menu_item_object_id', true );
            $submitForm = ($atts['submit_form'] == 'true') ? 'submitForm(event,this);' : '';
            $label = '<label ' .
              'for="' . $postID . '_radio" ' .
              'class="decision_fork_label" ' .
              'id="' . $postID . '_label" ' .
              '>' .
              //input
              '<input ' . 
              'type="radio" ' .
              'id="' . $postID . '_radio" ' .
              'name="content-type" ' .
              'value="' . $postID . '" ' .
              'onchange="chooseContentType({event:event,
                                            element:this,
                                            hInput:{
                                                name: \'content-type\',
                                                value: ' . $postID . '
                                            }
              })"' .
            //   'onchange="{setHiddenInput(event, \'content-type\', ' . $postID . ');' . $submitForm . '}"' .
              '>' .
              //input contents
              '<div class="decision_fork_title_container">' .
              '<div '.
              'class="decision_fork_title" '.
              '>' .
              strtoupper($item->title) .
              '</div>' .
              '</div>' .
              //end input contents
              '</input>' .
              //end input
              '</label>';
            $output .= $label;
            $output .= '<p>'.$item->description.'</p>';
            if (!$atts['hide_description'] == 'true') {
                $output .= '<p>'.$item->description.'</p>';
            }
            $output .= '</div>';
        }
    }
    $output .= '</div>';
    
    $output .= '</div>';
    
    return $output;
});

add_shortcode('filtered_courses', function( $atts = array() ) {
    
    // set up default parameters
    $atts = shortcode_atts( array(
		'limit' => 0
	), $atts );
    
    $category_choice = getCategoryChoice();
    $tag_choice = getTagChoice();
    $search_term = getSearchTerm();
    
    $output = '<div class="orc_course_grid">' .
              orc_get_course_listing_html($category_choice,
                                          $tag_choice,
                                          $search_term,
                                          $atts['limit']) .
              '</div>';
    return $output;
});

add_shortcode('filtered_events', function( $atts = array() ) {
    
    // set up default parameters
    $a = shortcode_atts( array(
		'limit' => 0,
		'show-calendar' => 'true',
		'open-only' => 'no'
	), $atts );
    
    $category_choice = getCategoryChoice();
    $tag_choice = getTagChoice();
    $search_term = getSearchTerm();
    
    
    $output = '<div class="orc_calendar">' .
              orc_get_events_listing_html($category_choice,
                                          $tag_choice,
                                          $search_term,
                                          $a['limit'],
                                          $a['show-calendar'],
                                          $a['open-only']) .
              '</div>';
    
    return $output;
});

//Helper Functions
function getTagChoice() {
    if ( isset( $_REQUEST["grade_band"] ) ) {
        $grade_choice = $_REQUEST["grade_band"];
        if ( $grade_choice == "no_selection" ) { $grade_choice = ""; };
        return $grade_choice;
    }
}

function getCategoryChoice() {
    $category_choice = "";
    if ( isset( $_REQUEST["wheel_cat"] ) ) {
        $wheel_choice = $_REQUEST["wheel_cat"];
        $category_choice = $wheel_choice;
        if ( $wheel_choice == "no_selection" ) {
            $category_choice = "";
        }
        else if ( $wheel_choice == "student-engagement-aligned-to-standards" ) {
            if ( isset( $_REQUEST["subjects"] ) ) {
                $subject_choice = $_REQUEST["subjects"];
                if ( !($subject_choice == "no_selection") )
                {
                    $category_choice = $subject_choice;
                }
            }
        }
    }
    return $category_choice;
}

function getSearchTerm() {
    $search_term = "";
    if ( isset($_REQUEST["search_term"]) ) {
        $search_term = sanitize_text_field($_REQUEST["search_term"]);
    }
    return $search_term;
}

//Render Functions
function orc_get_events_listing_html($category_choice,
                                     $grade_choice,
                                     $search_term,
                                     $event_limit,
                                     $show_calendar,
                                     $open_only) {
    
    //for EM_Events::output                                
    $events_args = array();
    $events_args['category'] = $category_choice;
    $events_args['tag']      = $grade_choice;
    $events_args['search']   = $search_term;
    if ($event_limit > 0)       $events_args['limit']         = $event_limit;
    if ($open_only == 'yes')    $events_args['event-is-open'] = $open_only;
    
    //for EM_Calendar::output
    $cal_events_args = array();
    $cal_events_args['full']        = 1;
    $cal_events_args['long_events'] = 1;
    $cal_events_args['category']    = $category_choice;
    $cal_events_args['tag']         = $grade_choice;
    $cal_events_args['search']      = $search_term;
    if ($event_limit > 0)
        $cal_events_args['limit']         = $event_limit;
    if ($open_only == 'yes')
        $cal_events_args['event-is-open'] = $open_only;
    
    //header containing so far only the calendar/list toggle btn 
    $header =
    '<div class="orc_calendar_header">' .
        (($show_calendar == 'true') ? //if
        '<button id="calendar_list_toggle" onclick="toggleCalendarListings()">
        <img id="cal_toggle_list" alt="Toggle to List View" src="' . get_stylesheet_directory_uri() . '/img/cal_toggle_list.png">
        <img id="cal_toggle_cal" alt="Toggle to Calendar View" src="' . get_stylesheet_directory_uri() . '/img/cal_toggle_cal.png">
        </button>'
        : //else
        ''
        ) .
    '</div>';
    
    //define this here because we intervene with the no events msg below
    $events = \EM_Events::output($events_args);
    
    //change no-events message if open-only
    if ($open_only == 'yes' && !strpos($event_listing,'no_events_message')) {
        //we didn't grab any events
        //and we are trying to show the default error.
        //instead we'll show another one:
        $events = '<div class="open_only no_events_message">There aren\'t any upcoming Core Training sessions scheduled. Check back later for any updates!</div>';
    }
    
    //finally, the HTML:
    $event_listing =
    '<div class="orc_event_listings orc_listings_container">' .
        $events .
    '</div>';
        
    $calendar_listing = 
    ($show_calendar == 'true') ? //if
        '<div class="orc_calendar_listings orc_listings_container">' .
        \EM_Calendar::output($cal_events_args) .
        '</div><script>toggleCalendarListings()</script>'
        : //else
        '';
        
    $body =
    '<div class="orc_calendar_body">' .
    $event_listing .
    $calendar_listing .
    '</div>';
    
    return $header . $body;
}

function orc_add_s_to_course_list_query($filter, $search_term) {
    $filter['s'] = $search_term;
    return $filter;
}

function orc_get_course_listing_html($category_choice,
                                     $grade_choice,
                                     $search_term,
                                     $course_limit) {
     
    //using closures here to do the job of
    //adding a changing parameter
    //to the course list query filter
    //https://wordpress.stackexchange.com/questions/45901/passing-a-parameter-to-filter-and-action-functions
    add_filter(
        'learndash_ld_course_list_query_args',
        function ($filter, $atts) use ( $search_term ) {
            return orc_add_s_to_course_list_query($filter, $search_term);
        },
        10,
        2
    ); 
                                         
    //setup course listing
    $grid_shortcode = '[ld_course_list col="3" category_name="' .
                      $category_choice .
                      '" tag="' .
                      $grade_choice .
                      '"' .
                      (($course_limit > 0) ? ' num="'.$course_limit.'"' : '') . 
                      ']';
    
    $course_listing = '<div class="orc_course_listings orc_listings_container">' . do_shortcode($grid_shortcode)
        . '</div>';
        
    
    return $course_listing;
}
?>
<?php
namespace Elementor;

class My_Widget_1 extends Widget_Base {
	
	public function get_name() {
		return 'title-subtitle';
	}
	
	public function get_title() {
		return 'Video Lessons with Filter';
	}
	
	public function get_icon() {
		return 'fa fa-video';
	}
	
	public function get_categories() {
		return [ 'basic' ];
	}
	
	
	protected function _register_controls() {

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Content', 'elementor' ),
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your title', 'elementor' ),
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXTAREA,
                'placeholder' => __( 'Enter your description', 'elementor' ),
			]
		);

// 		$this->add_control(
// 			'link',
// 			[
// 				'label' => __( 'Link', 'elementor' ),
// 				'type' => Controls_Manager::URL,
// 				'placeholder' => __( 'https://your-link.com', 'elementor' ),
// 				'default' => [
// 					'url' => '',
// 				]
// 			]
// 		);

		$this->end_controls_section();
	}
	
	public function writeMsg() {
	    echo "Hello msg";
	}
	
	function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
	
	protected function render() {
	    
	    $settings = $this->get_settings_for_display();
        
        $categories = get_categories( array(
            'meta_key' => 'category_order',
	        'orderby'  => 'meta_value',
            'order'   => 'ASC',
            'parent' => 0
        ) );
        
        $standards_cat = get_category_by_slug("student-engagement-aligned-to-standards");
        $subjects = get_categories( array(
	        'orderby'  => 'name',
            'order'   => 'ASC',
            'parent' => $standards_cat->term_id
        ) );
        
        $tags = get_tags( array(
            'orderby' => 'name',
            'order'   => 'ASC'  
        ) );
        $tags = orc_sort_by_grade_band($tags);
        
        ?>
        
        <div class="widget1_container">
            <form id="back_to_home_form"
                  method="get"
                  action="<?php echo get_home_url() ?>"
            >
                <input type="hidden" name="towheel" value="true" />
            </form>
            <div class="filters_bar">
            <form action="<?php echo esc_url( get_permalink( get_page_by_title( 'All Courses' ) ) ); ?>"
                  method="get">
            <div class="filters_container">

                <?php if(!is_front_page()): ?>
                <div class="dropdowns_container filter_row">
                    
                    <div class="design_principle_container">
                        <h2 class="filter_label design_principle_label">Instructional Design Principle</h2>
                        <div class="custom-select wheel_select_container">
                            <select name="wheel_categories" class="wheel_select">
                                <option selected
                                        value="no_selection"
                                        class="wheel_option orc_option">
                                    Any
                                </option>
                        
                            <?php
                             
                            foreach( $categories as $category ) {
                                
                                if ($category->slug == "uncategorized") {
                                    continue;
                                }
                                
                                echo '<option ' .
                                    selected( $this->test_input($_REQUEST["wheel_cat"]),  $category->slug) .
                                    'value=' .$category->slug .
                                    ' class="wheel_option orc_option  wheel_color_text_' . $category->slug .
                                    '">' .
                                    strtoupper($category->name) .
                                    '</option>';
                                
                            }
                            
                            ?>
                 
                            </select>
                        </div>
                    </div><!--END DESIGN PRINCIPLE CONTAINER-->
                    
                    <div class="other_dropdowns_container">
                        
                        <div class="subjects_container">
                            <h2 class="filter_label subject_label">Subject</h2>
                            <div class="custom-select subjects_select_container">
                                <select name="subjects"
                                        class="subjects_select
                                        <?php echo ' wheel_color_' . $this->test_input($_REQUEST["wheel_categories"]); ?>
                                        "
                                >
                                    <option selected
                                            value="no_selection"
                                            class="subjects_option orc_option">
                                        Any
                                    </option>
                            
                                <?php
                                 
                                foreach( $subjects as $subject ) {
                                    
                                    echo '<option ' . selected( $this->test_input($_REQUEST["subjects"]),  $subject->slug) . 'value=' . $subject->slug . ' class="subjects_option orc_option">' . $subject->name . '</option>';
                                    
                                }
                                
                                ?>
                     
                                </select>
                            </div>
                            <div class="subjects_unselectable">N/A</div>
                        </div><!--END SUBJECTS CONTAINER-->
                        
                        <div class="grade_band_container">
                            <h2 class="filter_label grade_band_label">Grade Band</h2>
                            <div class="custom-select grade_band_select_container">
                                <select name="grade_band" class="grade_band_select">
                                    <option selected
                                            value="no_selection"
                                            class="grade_band_option orc_option">
                                        Any
                                    </option>
                            
                                <?php
                                 
                                foreach( $tags as $tag ) {
                                    
                                    echo '<option ' .
                                           selected( $this->test_input($_REQUEST["grade_band"]), $tag->slug) .
                                           'value=' . $tag->slug .
                                           ' class="grade_band_option orc_option">' . $tag->name .
                                          '</option>';
                                    
                                }
                                
                                ?>
                     
                                </select>
                            </div>
                        </div><!--END GRADE BAND CONTAINER-->
                        
                        <div class="search_bar_container">
                            <input type="text" name="onramp-search" placeholder="search" />
                            <button onclick="searchContent(event)">
                                <div></div>
                            </button>
                        </div><!--END SEARCH BAR CONTAINER-->
                        
                    </div><!--END OTHER DROPDOWNS CONTAINER-->
                    <?php endif; ?>
                    
                </div><!--END DROPDOWNS CONTAINER-->
                
                <div class="tabs_container filter_row">
                    
                    <?php if(!is_front_page()): ?>
                    <div class="content_type_container">
                        <h2 class="filter_label content_type_label">Content Type</h2>
                        <div class="content_type_input_container">
                            
            <?php
            $menu_items = wp_get_nav_menu_items('content-menu');
            $output = '';
            if ($menu_items) {
                for($m=0; $m<count($menu_items); $m++ ) {
                    $item = $menu_items[$m];
                    $postID = get_post_meta( $item->ID, '_menu_item_object_id', true );

                    $checked = '';
                    if ($this->test_input($_REQUEST["content-type"]) == $postID) {
                        $checked = ' checked ';
                    } else if ($m == 0) {
                        $checked = ' checked ';
                    }
                    
                    $label = '<label ' .
                              'for="' . $postID . '_radio" ' .
                              'class="content_type_label" ' .
                              'id="' . $postID . '_label" ' .
                              '>' .
                              //input
                              '<input ' . 
                              $checked .
                              'type="radio" ' .
                              'id="' . $postID . '_radio" ' .
                              'name="content-type" ' .
                              'value="' . $postID . '" ' .
                              '>' .
                              //input contents
                              '<div class="toggle_switch_container">' .
                              '<button '.
                              'class="toggle_switch" '.
                              'onclick="seeContent(event, '.$postID.')"' .
                              '>' .
                              strtoupper($item->title) .
                              '</button>' .
                              '<div class="toggle_switch_bottom"></div>' .
                              '</div>' .
                              //end input content
                              '</input>' .
                              //end input
                              '</label>';
                              
                    $output .= $label;
                }
            }
            
            echo $output;
            
            ?>
                        </div>
                    </div><!--END CONTENT TYPE CONTAINER-->
                        
                    <div class="content_type_dropdown_container">
                            <h2 class="filter_label content_type_dropdown_label">Content Type</h2>
                            <div class="custom-select content_type_select_container">
                                <select name="content-type" class="content_type_select">
                            
            <?php
            $menu_items = wp_get_nav_menu_items('content-menu');
            $output = '';
            if ($menu_items) {
                for($m=0; $m<count($menu_items); $m++ ) {
                    $item = $menu_items[$m];
                    $postID = get_post_meta( $item->ID, '_menu_item_object_id', true );

                    $checked = '';
                    if ($this->test_input($_REQUEST["content-type"]) == $postID) {
                        $checked = ' checked ';
                    } else if ($m == 0) {
                        $checked = ' checked ';
                    }
                    
                    $option = '<option ' .
                               selected( $this->test_input($_REQUEST["content-type"]), $postID) .
                               'value=' . $postID .
                               ' class="content_type_option orc_option">' .
                               strtoupper($item->title) .
                              '</option>';
                    $output .= $option;
                }
            }
            echo $output;
            ?>
                     
                                </select>
                            </div>
                    </div><!--END CONTENT TYPE DROPDOWN CONTAINER-->
                        
                    
                    <?php endif; ?>
                    
                    <?php if(is_front_page()): ?>

                    <?php else: ?>
                    <div class="submit_container">
                        <div class="submit_btn_container">
                        <input type="submit" name="wheel_category_submit" class="submit_btn" value="SUBMIT CHANGES"/>
                        </div>
                    </div><!--END SUBMIT CONTAINER-->
                    <?php endif; ?>
                    
                </div><!--END TABS CONTAINER-->
             
            </div><!--END FILTERS CONTAINER--> 
            </form>
            </div><!--END FILTERS BAR-->
            
            <?php
            $output = '<div id="orc_content_container" class="orc_content_container">';
            
            //using postID here because the different tabs
            //(synchronous, asynchronous, all content)
            //have been prepared as posts of the default post type, 'post'
            //and to display them, we are simply getting the content of
            //the relevant post
            if (isset($_REQUEST['content-type'])) {
                $postID = $this->test_input($_REQUEST['content-type']);
                // $output .= '<h1>' . get_the_title($postID) . '</h1>';
                $output .= get_the_content(null,false,$postID);
            } else if ($menu_items) {
                $postID = get_post_meta( $menu_items[0]->ID, '_menu_item_object_id', true );
                // $output .= '<h1>' . get_the_title($postID) . '</h1>';
                $output .= get_the_content(null,false,$postID);
            }
            $output .= '</div>';
            echo $output;
            ?>
            
        </div><!--END WIDGET1 CONTAINER-->
        <div class="loader hide"><h3>loading...</h3></div>

        <?php
	}

	protected function _content_template() {

    }
	
	
}
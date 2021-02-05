<?php
namespace Elementor;

class Orc_Home_Widget extends Widget_Base {
	
	public function get_name() {
		return 'title-subtitle';
	}
	
	public function get_title() {
		return 'Home Page Wheel';
	}
	
	public function get_icon() {
		return 'fa fa-font';
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
			'subtitle',
			[
				'label' => __( 'Sub-title', 'elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter your sub-title', 'elementor' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'default' => [
					'url' => '',
				]
			]
		);

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
        
        ?>
        
        <div class="widget1_container">
            <div class="filters_bar">
            <form action="<?php echo esc_url( get_permalink( get_page_by_title( 'All Courses' ) ) ); ?>"
                  method="post">
            <div class="filters_container">
                
                <div class="wheel_container">
                    
                    <div class="wheel_background_container">
                        <div class="wheel_background"></div>
                    </div>
                    
                    <div class="wheel">
                        <?php
                        foreach( $categories as $category ) {
                            
                            if ($category->slug == "uncategorized") {
                                continue;
                            }
                            
                            ob_start();
                            
                            echo '<div class="wheel_slice"><label class="wheel_slice_contents wheel_color_' . $category->slug . '">';
                            echo '<input ';
                            if ($this->test_input($_POST["wheel_categories"] == $category->slug)) {
                                echo 'checked ';
                            }
                            echo 'type="radio" 
                                 id="wheel_radio_btn_' . $category->slug . '"  
                                 name="wheel_categories" 
                                 value="' . $category->slug . '" 
                                 class="wheel_radio">';
                            // echo '<span class="wheel_checkmark"></span>';
                            echo '</label></div>';
                                 
                            $input_element = ob_get_clean();
                            echo $input_element;
                            
                        }
                        
                        foreach ( $categories as $category ) {
                            if ($category->slug == "uncategorized") {
                                continue;
                            }
                            echo '<div class="wheel_cut"><div class="wheel_cut_contents"></div></div>';
                        }
                        
                        ?>
                    </div>
                    
                    <div class="wheel_center_container">
                        <div class="wheel_center"></div>
                    </div>
                    
                    <div class="no_selection_container">
                        <label class="no_selection_contents">
                            <p>No Selection</p>
                            <input type="radio"
                                   id="no_selection"
                                   name="wheel_categories"
                                   value="no_selection"
                                   class="wheel_no_selection_radio">
                            <span class="wheel_checkmark"></span>
                        </label>
                    </div>
                    
                </div>
                
                <div class="design_principle_container filter_container">
                    <h2 class="filter_label design_principle_label">Instructional Design Principle</h2>
                    <?php
                        foreach( $categories as $category ) {
                            
                            if ($category->slug == "uncategorized") {
                                continue;
                            }
                            
                            ob_start();
                            
                            echo '<div class="design_principle_name wheel_color_' . $category->slug . '" id="design_principle_name_' . $category->slug . '">' . $category->name . '</div>';
                            
                            $description = category_description($category->term_id);
                            echo '<div class="design_principle_description wheel_color_' . $category->slug . '" id="design_principle_description_' . $category->slug . '">' . $description . '</div>';
                                 
                            $input_element = ob_get_clean();
                            echo $input_element;
                            
                        }
                    ?>
                </div>

                <div class="subjects_container filter_container">
                    <h2 class="filter_label subject_label">Subject</h2>
                    <div class="custom-select subjects_select_container">
                        <select name="subjects"
                                class="subjects_select
                                <?php echo ' wheel_color_' . $this->test_input($_POST["wheel_categories"]); ?>
                                "
                        >
                            <option selected
                                    value="no_selection"
                                    class="subjects_option">
                                Any
                            </option>
                    
                        <?php
                         
                        foreach( $subjects as $subject ) {
                            
                            echo '<option ' . selected( $this->test_input($_POST["subjects"]),  $subject->slug) . 'value=' . $subject->slug . ' class="subjects_option">' . $subject->name . '</option>';
                            
                        }
                        
                        ?>
             
                        </select>
                    </div>
                    <div class="subjects_unselectable">N/A</div>
                </div>
                
                <div class="grade_band_container filter_container">
                    <h2 class="filter_label grade_band_label">Grade Band</h2>
                    <div class="custom-select grade_band_select_container">
                        <select name="grade_band" class="grade_band_select">
                            <option selected
                                    value="no_selection"
                                    class="grade_band_option">
                                Any
                            </option>
                    
                        <?php
                         
                        foreach( $tags as $tag ) {
                            
                            echo '<option ' . selected( $this->test_input($_POST["grade_band"]),  $tag->slug) . 'value=' . $tag->slug . ' class="grade_band_option">' . $tag->name . '</option>';
                            
                        }
                        
                        ?>
             
                        </select>
                    </div>
                </div>
                
                <div class="content_type_container filter_container">
                    <h2 class="filter_label content_type_label">Content Type</h2>
                    <div class="content_type_input_container">

                        <label for="video_lessons" class="content_type_label">
                            
                            <input
                            <?php
                                if ($this->test_input($_POST["video_lessons"] == "video-lessons")) {
                                    echo ' checked ';
                                }
                            ?>
                                type="checkbox" id="video_lessons" name="video_lessons" value="video-lessons">
                                <div class="toggle_switch">VIDEO LESSONS</div>
                        </label>

                        <label for="zoom_presentations" class="content_type_label">
                            
                            <input
                            <?php
                                if ($this->test_input($_POST["zoom_presentations"] == "zoom-presentations")) {
                                    echo ' checked ';
                                }
                            ?>
                            type="checkbox" id="zoom_presentations" name="zoom_presentations" value="zoom-presentations">
                            <div class="toggle_switch">ZOOM PRESENTATIONS</div>
                        </label>
                        
                        <label for="coach-ins" class="content_type_label coach-ins_label">
                            
                            <input
                            <?php
                                if ($this->test_input($_POST["coach-ins"] == "coach-ins")) {
                                    echo ' checked ';
                                }
                            ?>
                            type="checkbox" id="coach-ins" name="coach-ins" value="coach-ins">
                            <div class="toggle_switch">COACH-INS</div>
                        </label>
                    </div>
                </div>
                
                <div class="submit_container filter_container">
                    <input type="submit" name="wheel_category_submit" class="submit_btn" value="FILTER"/>
                </div>
             
            </div>   
            </form>
            </div>

        <?php
        
        $wheel_choice = "";
        $grade_choice = "";
        $subject_choice = "";
        $category_choice = "";
        if ( isset( $_POST ) ) {
            
            //category_choice
            //if selected, subject choice replaces wheel choice 
            $wheel_choice = $this->test_input($_POST["wheel_categories"]);
            if ( $wheel_choice == "no_selection" )
            {
                $category_choice = "";
            }
            else if ( $wheel_choice == "student-engagement-aligned-to-standards" )
            {
                $subject_choice = $this->test_input($_POST["subjects"]);
                if ( $subject_choice == "no_selection" )
                {
                    $category_choice = $wheel_choice;
                }
                else
                {
                    $category_choice = $subject_choice;
                }
            }
            else
            {
                $category_choice = $wheel_choice;
            }
            
            //tag choice
            $grade_choice = $this->test_input($_POST["grade_band"]);
            if ( $grade_choice == "no_selection" ) { $grade_choice = ""; };
            
            // echo '<div>Filtering for: ' . $wheel_choice . '</div>';
            
            //not sure if global vars is better, but it works
            global $orc_global_cat_to_filter;
            $orc_global_cat_to_filter = $category_choice;
            global $orc_global_tag_to_filter;
            $orc_global_tag_to_filter = $grade_choice;

            echo '<div class="content_container">';
            
            if ($this->test_input($_POST["video_lessons"]) == "video-lessons") {
                //display courses, filtered
                $grid_shortcode = '[ld_course_list col="3" category_name="' . $category_choice . '" tag="' . $grade_choice . '"]';
                
                echo '<div class="orc_course_grid orc_content_container">' .
                        '<h1>Video Lessons</h1>' .
                        '<h3 class="orc_content_subtitle">created by teachers</h3>' .
                        '<div class="orc_course_listings orc_listings_container">' .
                        do_shortcode($grid_shortcode) .
                        '</div>' .
                        '</div>';
            }
            
            if ($this->test_input($_POST["zoom_presentations"]) == "zoom-presentations") {
                //display calendar with events, filtered
                echo '<div class="orc_calendar orc_content_container">' .
                        '<h1>Zoom Presentations</h1>' .
                        '<h3 class="orc_content_subtitle">synchronous learning events</h3>' .
                        '<div class="orc_calendar_listings orc_listings_container">' .
                        do_shortcode('[MEC id="25212"]') .
                        '</div>' .
                        '</div>';
            }

            if ($this->test_input($_POST["coach-ins"]) == "coach-ins") {
                //nothing for now :)
            }
            
            echo '</div>';

        }
        
        ?>
        
        </div>
        
        <?php
		 

	}
	

	
	protected function _content_template() {

    }
	
	
}
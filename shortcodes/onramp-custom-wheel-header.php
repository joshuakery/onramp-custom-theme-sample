<?php
/**
 * Create the wheel header element
 *
 * This file is used to create the wheel header element
 *
 * @link       plugin_name.com/team
 * @since      1.0.0
 *
 * @package    PluginName
 * @subpackage PluginName/admin/partials
 */

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
<div class="wheel_container filter_row">
    
    <div class="wheel_background_container">
        <div class="wheel_background">
            <img src=<?php
                echo get_stylesheet_directory_uri().'/img/design_principles_ring.png';
                ?>>
        </div>
    </div>
    
    <div class="wheel">
        <?php
        foreach( $categories as $category ) {
            
            if ($category->slug == "uncategorized") {
                continue;
            }
            
            $input_element = '';
            $input_element .= '<div class="wheel_slice"><label class="wheel_slice_contents wheel_color_' . $category->slug . '">';
            $input_element .= '<input ';
            if ($_REQUEST["wheel_categories"] == $category->slug) {
                $input_element .= 'checked ';
            }
            $input_element .= 'type="radio" 
                 class="wheel_radio wheel_radio_btn_' . $category->slug . '"  
                 name="wheel_categories" 
                 value="' . $category->slug . '">';
            $input_element .= '<div class="wheel_icon"><img src="' .
                get_stylesheet_directory_uri() .
                '/img/icon_' . $category->slug . '.png' .
                '"></div>';
            $input_element .= '</label></div>';

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
        <div class="wheel_center">
            <img src=<?php echo '"' .
                get_stylesheet_directory_uri() .
                '/img/wheel_center_icon.png' .
                '"'; ?>>
        </div>
    </div>
    
    <?php if(!is_front_page()): ?>
    <button class="back_to_home" onclick="submitBackToHomeForm(event)"></button>
    <?php endif; ?>
    
    <div class="no_selection_container">
        <label class="no_selection_contents">
            <p>No Selection</p>
            <input type="radio"
                   name="wheel_categories"
                   value="no_selection"
                   class="wheel_no_selection_radio">
            <span class="wheel_checkmark"></span>
        </label>
    </div>
    
</div><!--END WHEEL CONTAINER-->
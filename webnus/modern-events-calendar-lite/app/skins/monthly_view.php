<?php
/** no direct access **/
defined('MECEXEC') or die();

/**
 * Webnus MEC monthly view class.
 * @author Webnus <info@webnus.biz>
 */
class MEC_skin_monthly_view extends MEC_skins
{
    /**
     * @var string
     */
    public $skin = 'monthly_view';
    
    /**
     * Constructor method
     * @author Webnus <info@webnus.biz>
     */
    public function __construct()
    {
        parent::__construct();
        
        //JK
        // $this->wheel_cat = "";
        // if (isset($_POST["wheel_categories"])) {
        //     $wheel_cat = sanitize_text_field($_POST["wheel_categories"]);
        //     $this->wheel_cat = get_category_by_slug($wheel_cat);
        // }
    }
    
    /**
     * Registers skin actions into WordPress
     * @author Webnus <info@webnus.biz>
     */
    public function actions()
    {
        $this->factory->action('wp_ajax_mec_monthly_view_load_month', array($this, 'load_month'));
        $this->factory->action('wp_ajax_nopriv_mec_monthly_view_load_month', array($this, 'load_month'));
    }
    
    /**
     * Initialize the skin
     * @author Webnus <info@webnus.biz>
     * @param array $atts
     */
    public function initialize($atts)
    {
        // error_log("calling initialize");
        $this->atts = $atts;
        
        // Skin Options
        $this->skin_options = (isset($this->atts['sk-options']) and isset($this->atts['sk-options'][$this->skin])) ? $this->atts['sk-options'][$this->skin] : array();
        
        // Search Form Options
        $this->sf_options = (isset($this->atts['sf-options']) and isset($this->atts['sf-options'][$this->skin])) ? $this->atts['sf-options'][$this->skin] : array();
        
        // Search Form Status
        $this->sf_status = isset($this->atts['sf_status']) ? $this->atts['sf_status'] : true;
        
        // The events
        $this->events_str = '';

        // Generate an ID for the sking
        $this->id = isset($this->atts['id']) ? $this->atts['id'] : mt_rand(100, 999);
        
        // Set the ID
        if(!isset($this->atts['id'])) $this->atts['id'] = $this->id;
        
        // The style
        $this->style = isset($this->skin_options['style']) ? $this->skin_options['style'] : 'modern';
        if($this->style == 'fluent' and !is_plugin_active('mec-fluent-layouts/mec-fluent-layouts.php')) $this->style = 'modern';
        
        // Next/Previous Month
        $this->next_previous_button = isset($this->skin_options['next_previous_button']) ? $this->skin_options['next_previous_button'] : true;
        
        // Override the style if the style forced by us in a widget etc
        if(isset($this->atts['style']) and trim($this->atts['style']) != '') $this->style = $this->atts['style'];
        
        // HTML class
        $this->html_class = '';
        if(isset($this->atts['html-class']) and trim($this->atts['html-class']) != '') $this->html_class = $this->atts['html-class'];

        // Booking Button
        $this->booking_button = isset($this->skin_options['booking_button']) ? (int) $this->skin_options['booking_button'] : 0;
        
        // SED Method
        $this->sed_method = isset($this->skin_options['sed_method']) ? $this->skin_options['sed_method'] : '0';

        // reason_for_cancellation
        $this->reason_for_cancellation = isset($this->skin_options['reason_for_cancellation']) ? $this->skin_options['reason_for_cancellation'] : false;

        // display_label
        $this->display_label = isset($this->skin_options['display_label']) ? $this->skin_options['display_label'] : false;

        // Image popup
        $this->image_popup = isset($this->skin_options['image_popup']) ? $this->skin_options['image_popup'] : '0';
        
        // From Widget
        $this->widget = (isset($this->atts['widget']) and trim($this->atts['widget'])) ? true : false;

        // Display Price
        $this->display_price = (isset($this->skin_options['display_price']) and trim($this->skin_options['display_price'])) ? true : false;
        
        // Init MEC
        $this->args['mec-init'] = true;
        $this->args['mec-skin'] = $this->skin;
        
        // Post Type
        $this->args['post_type'] = $this->main->get_main_post_type();

        // Post Status
        $this->args['post_status'] = 'publish';
        
        // Keyword Query
        $this->args['s'] = $this->keyword_query();
        
        // Taxonomy
        
        $this->args['tax_query'] = $this->tax_query();
        
        // Meta
        $this->args['meta_query'] = $this->meta_query();
        
        // Tag
        $this->args['tag'] = $this->tag_query();
        
        // Author
        $this->args['author'] = $this->author_query();
        
        // Pagination Options
        $this->paged = get_query_var('paged', 1);
        $this->limit = (isset($this->skin_options['limit']) and trim($this->skin_options['limit'])) ? $this->skin_options['limit'] : 12;
        
        $this->args['posts_per_page'] = $this->limit;
        $this->args['paged'] = $this->paged;
        
        // Sort Options
        $this->args['orderby'] = 'meta_value_num';
        $this->args['order'] = 'ASC';
        $this->args['meta_key'] = 'mec_start_day_seconds';

        // Show Only Expired Events
        $this->show_only_expired_events = (isset($this->atts['show_only_past_events']) and trim($this->atts['show_only_past_events'])) ? '1' : '0';

        // Show Past Events
        if($this->show_only_expired_events) $this->atts['show_past_events'] = '1';

        // Show Past Events
        $this->args['mec-past-events'] = isset($this->atts['show_past_events']) ? $this->atts['show_past_events'] : '0';
        
        // Start Date
        list($this->year, $this->month, $this->day) = $this->get_start_date();
        
        $this->start_date = date('Y-m-d', strtotime($this->year.'-'.$this->month.'-'.$this->day));
        $this->active_day = $this->year.'-'.$this->month.'-'.current_time('d');
        
        // We will extend the end date in the loop
        $this->end_date = $this->start_date;
    }
    
    /**
     * Search and returns the filtered events
     * @author Webnus <info@webnus.biz>
     * @return array of objects
     */
    public function search()
    {
        if($this->show_only_expired_events)
        {
            $start = date('Y-m-d H:i:s', current_time('timestamp', 0));
            $end = $this->start_date;
        }
        else
        {
            $start = $this->start_date;
            $end = date('Y-m-t', strtotime($this->start_date));
        }

        // Date Events
        $dates = $this->period($start, $end, true);

        // Limit
        $this->args['posts_per_page'] = $this->limit;

        $events = array();
        foreach($dates as $date=>$IDs)
        {
            // No Event
            if(!is_array($IDs) or (is_array($IDs) and !count($IDs)))
            {
                $events[$date] = array();
                continue;
            }

            // Include Available Events
            $this->args['post__in'] = $IDs;

            // Count of events per day
            $IDs_count = array_count_values($IDs);

            // The Query
            $query = new WP_Query($this->args);
            if($query->have_posts())
            {
                if(!isset($events[$date])) $events[$date] = array();

                // Day Events
                $d = array();

                // The Loop
                while($query->have_posts())
                {
                    $query->the_post();
                    $ID = get_the_ID();

                    $ID_count = isset($IDs_count[$ID]) ? $IDs_count[$ID] : 1;
                    for($i = 1; $i <= $ID_count; $i++)
                    {
                        $rendered = $this->render->data($ID);

                        $data = new stdClass();
                        $data->ID = $ID;
                        $data->data = $rendered;

                        $data->date = array
                        (
                            'start'=>array('date'=>$date),
                            'end'=>array('date'=>$this->main->get_end_date($date, $rendered))
                        );

                        $d[] = $this->render->after_render($data, $this, $i);
                    }
                }

                usort($d, array($this, 'sort_day_events'));
                $events[$date] = $d;
            }

            // Restore original Post Data
            wp_reset_postdata();
        }

        return $events;
    }
    
    /**
     * Returns start day of skin for filtering events
     * @author Webnus <info@webnus.biz>
     * @return array
     */
    public function get_start_date()
    {
        // Default date
        $date = current_time('Y-m-d');
        
        if(isset($this->skin_options['start_date_type']) and $this->skin_options['start_date_type'] == 'start_current_month') $date = date('Y-m-d', strtotime('first day of this month'));
        elseif(isset($this->skin_options['start_date_type']) and $this->skin_options['start_date_type'] == 'start_next_month') $date = date('Y-m-d', strtotime('first day of next month'));
        elseif(isset($this->skin_options['start_date_type']) and $this->skin_options['start_date_type'] == 'date') $date = date('Y-m-d', strtotime($this->skin_options['start_date']));
        
        // Hide past events
        if(isset($this->atts['show_past_events']) and !trim($this->atts['show_past_events']))
        {
            $today = current_time('Y-m-d');
            if(strtotime($date) < strtotime($today)) $date = $today;
        }
        
        // Show only expired events
        if(isset($this->show_only_expired_events) and $this->show_only_expired_events)
        {
            $yesterday = date('Y-m-d', strtotime('Yesterday'));
            if(strtotime($date) > strtotime($yesterday)) $date = $yesterday;
        }
        
        $time = strtotime($date);
        return array(date('Y', $time), date('m', $time), date('d', $time));
    }
    
    /**
     * Load month for AJAX requert
     * @author Webnus <info@webnus.biz>
     * @return void
     */
    public function load_month()
    {
        // error_log("calling load_month");
        $this->sf = $this->request->getVar('sf', array());
        $apply_sf_date = $this->request->getVar('apply_sf_date', 1);
        $atts = $this->sf_apply($this->request->getVar('atts', array()), $this->sf, $apply_sf_date);
        // $atts['category'] = $this->wheel_cat;
        $navigator_click = $this->request->getVar('navigator_click', false);

        // Initialize the skin
        $this->initialize($atts);
        
        // Search Events If Not Found In Current Month 
        $c = 0;
        $break = false;

        do
        {
            if($c > 12) $break = true;
            if($c and !$break)
            {
                if(intval($this->month) == 12)
                {
                    $this->year = intval($this->year)+1;
                    $this->month = '01';
                }

                $this->month = sprintf("%02d", intval($this->month)+1);
            }
           else
            {
                // Start Date
                $this->year = $this->request->getVar('mec_year', current_time('Y'));
                $this->month = $this->request->getVar('mec_month', current_time('m'));
            }

            if($this->show_only_expired_events)
            {
                $this->start_date = date('Y-m-d', strtotime($this->year.'-'.$this->month.'-01'));
                $this->active_day = date('Y-m-t', strtotime($this->year.'-'.$this->month.'-01'));
            }
            else
            {
                $this->start_date = date('Y-m-d', strtotime($this->year.'-'.$this->month.'-01'));

                $day = current_time('d');
                $this->active_day = $this->year.'-'.$this->month.'-'.$day;

                // If date is not valid then use the first day of month
                if(!$this->main->validate_date($this->active_day, 'Y-m-d')) $this->active_day = $this->year.'-'.$this->month.'-01';
            }
            
            // We will extend the end date in the loop
            $this->end_date = $this->start_date;
            
            // Return the events
            $this->atts['return_items'] = true;
            
            // Fetch the events
            $this->fetch();
            // $this->events = apply_filters('orc_filter_events',$this->events);
            
            // Break the loop if not resault
            if($break)
            {
                break;
            }

            // Set active day to current day if not resault
            if(count($this->events)) $this->active_day = key($this->events);
            if($navigator_click) break;
          
            $c++;
        }
        while(!count($this->events));
        
        // Return the output
        $output = $this->output();
        
        echo json_encode($output);
        exit;
    }
}
<?php

/**
Plugin Name: Twitter Badge Widget
Plugin URI: http://wordpress.org/extend/plugins/twitter-badge-widget/
Description: Show a simple twitter badge with the latest tweets on a web page
Author: Andy Clark, visioniz
Author URI: http://workshopshed.com/
Version: 1.74
Stable tag: 1.74
License: GPLv2
*/
if(!class_exists('AGC_Widget_Base')) {
    class AGC_Widget_Base extends WP_Widget {
        /* Extend the default widget to include stuff for rendering the AdminUI form
        */
        
        public function form_input_text($field,$caption,$value) {
         printf ('<p><label for="%s">%s: <input class="widefat" id="%s" name="%s" type="text" value="%s" " /></label></p>',$this->get_field_id($field),esc_html($caption),$this->get_field_id($field),$this->get_field_name($field),esc_attr($value));
        }
        
        public function form_input_option($field,$caption,$value,$options){
            
        foreach ($options as $option) {    
            if ($option == $value) {$select = 'selected="selected"'; } else {$select = '';}
            $opthtml = sprintf('%s<option %s>%s</option>',$opthtml,$select,$option );
        }
        printf('<p><label for="%s">%s:</label><select id="%s" name="%s" class="widefat" >%s</select></p>',$this->get_field_id($field),esc_html($caption),$this->get_field_id($field),$this->get_field_name($field),$opthtml);
        }
        
        public function form_input_checkbox($field,$caption,$value){
            if ($value) {
                $checked = 'checked="checked"';
            }
            printf ('<p><input id="%s" class="checkbox" type="checkbox" name="%s" %s /><label for="%s"> %s</label></p>',$this->get_field_id($field),$this->get_field_name($field),$checked,$this->get_field_id($field),esc_html($caption));
        }
    }
}
 
class TwitterBadgeWidget extends AGC_Widget_Base {
   function TwitterBadgeWidget() {
    //Constructor
   add_action( 'TBW_RenderTwitterBadge', array('TwitterBadgeWidget', 'render'),10,8 );   
   add_action( 'TBW_QueueScripts', array('TwitterBadgeWidget', 'queuescripts'),10,0 ); 
   //add_shortcode('TBW', array('TwitterBadgeWidget','rendershortcode' ));

   $this->init_locale();    
   $widget_ops = array('classname' => 'TwitterBadgeWidget', 'description' => __('A widget to show twitter badge and latest tweet','TBWidget'));
   $this->WP_Widget('TwitterBadgeWidget', 'Twitter Badge Widget', $widget_ops);


   if ( is_active_widget( false, false, $this->id_base ) && !is_admin() ) {
            do_action( 'TBW_QueueScripts');
        }
   }
   
   function queuescripts(){
    
    //A localised version of the relative time function
        wp_enqueue_script('LocRelativeTime',plugins_url('/loc_relative_time.js', __FILE__),'','',true);
        wp_localize_script('LocRelativeTime', 'RelTimeL10n', array(
	                       'RelTSeconds' => __('less than a minute ago','TBWidget'),
	                       'RelTMinute' => __( 'about a minute ago','TBWidget' ),
                           'RelTMinutes' => __('%s minutes ago','TBWidget'),
                           'RelTHour' => __('about an hour ago','TBWidget'),
                           'RelTHours' => __('%s hours ago','TBWidget'),
                           'RelTDay' =>  __('yesterday','TBWidget'),
                           'RelTDays' =>  __('%s days ago','TBWidget')
                            ) );
    
        //Run these ones at the end of the page
        wp_enqueue_script('JQuery-JSON',plugins_url('/jquery.json-2.3.js', __FILE__),array('jquery'),'',true);
        wp_enqueue_script('JStorage',plugins_url('/jstorage.js', __FILE__),array('jquery','JQuery-JSON'),'',true);
        wp_enqueue_script('TwitterBadgeScript',plugins_url('/twitterbadgescript.js', __FILE__),array('jquery','JStorage','LocRelativeTime'),'',true);

        //Load css file, apply alternate if desired.
        $cssFile = apply_filters('TBW_AlternateCSS',plugins_url('/twitterbadge.css', __FILE__));
        if ($cssFile <> '') {                    
            wp_enqueue_style('TwitterBadgeStyle',$cssFile);
            }
        }

    
   function widget($args, $instance) {
    // prints the widget to the user
   extract($args, EXTR_SKIP);
    
   $Twitter_ID = empty($instance['Twitter_ID']) ? ' ' : apply_filters('widget_Twitter_ID', $instance['Twitter_ID']);
   $Count = absint(empty($instance['Count']) ? 1 : $instance['Count']);
   $title = apply_filters('widget_title', $instance['title']);
   $Format = empty($instance['Format']) ? 'Narrow' : $instance['Format'];
   $InteractiveFollow = (bool) $instance['InteractiveFollow'];
   $InteractiveCount = (bool) $instance['InteractiveCount'];  
   $Lang = WPLANG == '' ? 'EN' : WPLANG;
 
   $widget_id = $args['widget_id'];
   $div_id = 'TBW_'.$widget_id;
     
   echo $before_widget;
       if ( !empty( $title ) ) { 
        	echo $before_title . $title . $after_title; 
       };
       do_action( 'TBW_RenderTwitterBadge', $div_id,$title,$Format,$Twitter_ID,$Count,$InteractiveFollow,$InteractiveCount,$Lang );
   echo $after_widget;   
   }
    
   function render($div_id,$title,$Format,$Twitter_ID,$Count,$InteractiveFollow,$InteractiveCount,$Lang) 
   {
       $Follow = sprintf(esc_html__('Follow @%s','TBWidget'), $Twitter_ID);
       $url = esc_url('http://www.twitter.com/'.$Twitter_ID);

       //See http://dev.twitter.com/doc/get/statuses/user_timeline for other parameters that can be used here.
       //API Change 13/12/2011 Need to set trim user to false not true
           $jsonurl = esc_url('http://api.twitter.com/1/statuses/user_timeline.json?screen_name='.$Twitter_ID).'&amp;callback=?&amp;trim_user=false&amp;include_entities=false';
           //include_rts=false&amp;exclude_replies=true';

       if ($Count > 20) {
             $jsonurl = $jsonurl.'&amp;count='.$Count;
       } 

       //Cache busting technque for IE n.b. We then cache the results in the javascript
       $nonce= wp_create_nonce  ('TBW_NoCache');
       $jsonurl = $jsonurl.'&amp;nocache='.$nonce;

//       if ($Twitter_ID == 'TBWDEBUG123')
//       {
//            //Special Debug file
//            $jsonurl = plugins_url('/test.json', __FILE__);
//       }

       $imgtrans = plugins_url( 'spacer.gif' , __FILE__ );
       $InteractiveParams = '';
       
       if ($InteractiveFollow){
            $InteractiveParams = ' class="twitter-follow-button" data-lang="'.$Lang.'"';
            if ($InteractiveCount != true){
            $InteractiveParams = $InteractiveParams.' data-show-count="false"';
            }
       }
   
       printf ('<div class="TBW_%s">',$Format);
       $latest = sprintf(esc_html__('Latest on twitter from %s','TBWidget'), $Twitter_ID);
       printf ('<a href="%s" title="%s"><div class="TBW_Picture"><img alt="%s" src="%s" />&nbsp;</div></a>',$url,$Follow,$latest,$imgtrans);
       printf ('<div class="TBW_Status"><ul id="%s" class="TBW_Data" data-TBWjsonurl="%s" data-TBWtwitterid="%s" data-TBWcount="%s"><li class="TBW_Loading">%s</li></ul></div>',$div_id,$jsonurl,$Twitter_ID,$Count,esc_html__('loading...','TBWidget'));
       printf ('<div class="TBW_Follow"><a href="%s" %s>%s</a></div></div>',$url,$InteractiveParams,$Follow);
      
   }

   
   function update($new_instance, $old_instance) {
    //save the widget
   $instance = $old_instance;
   $instance['title'] = strip_tags($new_instance['title']);
   $instance['Twitter_ID'] = strip_tags($new_instance['Twitter_ID']);
   $count = absint(strip_tags($new_instance['Count'])); 
   if ($count > 200) { $count = 200;}
   if ($count < 1) { $count = 1;}
   $instance['Count'] = $count;    
   $instance['Format'] = strip_tags($new_instance['Format']);   
   
   $instance['InteractiveFollow'] = isset($new_instance['InteractiveFollow']);
   if ((bool) $instance['InteractiveFollow']){
        $instance['InteractiveCount'] = isset($new_instance['InteractiveCount']);
    }
   else{
     $instance['InteractiveCount'] = false;
   }
        
    
   return $instance;
   }
    
   function form($instance) {
    //widgetform in backend
   $instance = wp_parse_args( (array) $instance, array( 'title' => 'Twitter Badge Widget', 'Twitter_ID' => '','Count' => 1, 'Format' => 'Narrow', 'InteractiveFollow' => false, 'InteractiveCount' => false ) );
   $title = strip_tags($instance['title']);
   $Twitter_ID = strip_tags($instance['Twitter_ID']);
   $Count = absint($instance['Count']);
   $Format = $instance['Format'];
   $InteractiveFollow = (bool) $instance['InteractiveFollow'];
   $InteractiveCount = (bool) $instance['InteractiveCount'];   

   //See Wickett widget for a technique for giving a user a number box with spin buttons
   
    $this->form_input_text('title',__('Title','TBWidget'),$title);
    $this->form_input_text('Twitter_ID',__('Twitter Screen name','TBWidget'),$Twitter_ID);
    $this->form_input_text('Count',__('Number of tweets to display (default = 1)','TBWidget'),$Count);
    //Not sure how to handle language on this one
    $this->form_input_option('Format',__('Format','TBWidget'),$Format,array ('Narrow','Wide'));

//Should move this to the CSS file??    
    echo   '<style>fieldset p { padding-left: 10px !important;}</style>';
    printf('<fieldset><legend>%s</legend>',esc_html__('Interactive Follow','TBWidget'));
    $this->form_input_checkbox('InteractiveFollow',__('Enable','TBWidget'),$InteractiveFollow);
    $this->form_input_checkbox('InteractiveCount',__('Include count','TBWidget'),$InteractiveCount);
    echo '</fieldset>';
   }
   
   function init_locale() {
  	    load_plugin_textdomain('TBWidget',false, dirname( plugin_basename( __FILE__ ) ) . '/locale/');    
   }
}   

add_action( 'widgets_init', create_function('', 'return register_widget("TwitterBadgeWidget");') );

?>
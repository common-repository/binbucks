<?php
/* 
Plugin Name: Binbucks
Plugin URI: http://www.binbucks.com
Description: Shrink Your Links And Earn Money With Binbucks Url Shortener. This Tools Generate Mass Paid Links For You.
Version: 1.0
Author: Binbucks
*/


function binbucks_needed_before_script()
{	
     $whitelist=get_option('bin_white','');
     $Shrink=get_option('bin_shrink','');
     $bin_code=get_option('bin_code','');
     if(empty($whitelist))
     {
       $whitelist=[null];       
     }
     else
     {
     	$whitelist=str_replace('\\','',json_encode(unserialize($whitelist)));
     }
     if(empty($Shrink)) 
     {
     	  $Shrink=[null];
     }
     else
     {
     	$Shrink=str_replace('\\','',json_encode(unserialize($Shrink)));
     }
     if(empty($bin_code))
     {
     	$bin_code="MQ==";
     }
	wp_enqueue_script( 'bin-js', esc_url( plugins_url( 'js/shrinker.js', __FILE__ ) ),'','1.0',true );
	wp_add_inline_script( 'bin-js', "varurl = 'http://www.binbucks.com/site/sc?v=".$bin_code."&c='; var shortem = ".$Shrink."; var bin_custom_white=".$whitelist.";",'before');
}

function binbucks_register_fields() 
{
	  
        register_setting( 'general','bin_code','esc_attr' );
        register_setting( 'general','bin_shrink','esc_attr' );
        register_setting( 'general','bin_white','esc_attr' );
        add_settings_field('bin_code','Binbucks-Wordpress-Code','binbucks_fields_html','general','default',array ( 'bin_id' => 'code' ));
        add_settings_field('bin_shrink', 'Binbucks-DomainList' , 'binbucks_fields_html' , 'general','default',array ( 'bin_id' => 'shrink' ));
        add_settings_field('bin_white', 'Binbucks-WhiteList' , 'binbucks_fields_html' , 'general','default',array ( 'bin_id' => 'white' ));
         
      
}
function binbucks_fields_html($args) 
{
	   if($args['bin_id']=="code")
	   {
	   	  $value = get_option( 'bin_code', '' );
         echo '<input type="text" id="binbucks-code" name="bin_code" value="' . $value . '" />';
	   }
	   if($args['bin_id']=="shrink")
	   {
	   	 $value = get_option( 'bin_shrink', '' );
	   	 if(is_array(unserialize($value)))
       {
         $value=implode(',',unserialize($value));
       }
       else
       {
         $value="";
       }
       
         echo '<textarea rows="4" cols="50" id="binbucks-shrink" name="bin_shrink">'.$value.'</textarea>';
       
	   }
	   if($args['bin_id']=="white")
	   {
	   	  $value = get_option( 'bin_white', '' );
       
       if(is_array(unserialize($value)))
       {
         $value=implode(',',unserialize($value));
       }
       else
       {
         $value="";
       }
      
	   	  echo '<textarea rows="4" cols="50" id="binbucks-white" name="bin_white">'.$value.'</textarea>';
	   }
        
        
}

function binbucks_update_field_data( $new_value, $old_value,$os ) {
	if($os=="bin_shrink")
	{
      if(empty(trim($old_value)))
      {
      	$new_value=serialize(array("google.com","facebook.com"));
      }
      else
      {
        $new_value=serialize(explode(",",$new_value));
      }
	}
	if($os=="bin_code")
	{
		if(empty(trim($old_value)))
      {
      	$new_value="MQ==";
      }

	}
	if($os=="bin_white")
	{
      if(empty(trim($old_value)))
      {
      	$new_value=serialize(array("http://www.google.com/example/*bin_white*/"));
      }
      else
      {
      	$new_value=serialize(explode(",",$new_value));
      }
	}
	
	return $new_value;
}
function binbucks_init_data() 
{
	add_filter( 'pre_update_option_bin_shrink', 'binbucks_update_field_data', 10, 3 );
	add_filter( 'pre_update_option_bin_code', 'binbucks_update_field_data', 10, 3 );
	add_filter( 'pre_update_option_bin_white', 'binbucks_update_field_data', 10, 3 );
}
add_filter( 'admin_init' ,'binbucks_register_fields');
add_action('wp_enqueue_scripts','binbucks_needed_before_script');
add_action( 'init', 'binbucks_init_data' );
 ?>
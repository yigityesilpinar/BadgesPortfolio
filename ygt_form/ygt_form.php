<?php 
/*
* Plugin Name: yigit_form
* Plugin URI: http://localhost/wordpress/
* Version: 1.0
* Author: Yigit_Yesilpinar
* Author URI: https://www.facebook.com/profile.php?id=804207715
* License: GPL2
*/


register_activation_hook(__FILE__,'ygt_form_create_update_table');
  include('inc/csv.inc.php');
  include('inc/langs.inc.php');
function ygt_form_create_update_table() {
global $wpdb; // to access this variable
 $charset_collate = $wpdb->get_charset_collate();
   $table_name = $wpdb->prefix . "ygt_form"; 
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
$sql = "CREATE TABLE $table_name (
  form_id bigint(20) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  lang varchar(60) NOT NULL,
  level varchar(10) NOT NULL,
  answers varchar(60) NOT NULL,
  UNIQUE KEY id (form_id)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

} 
 $table_name = $wpdb->prefix . "ygt_form_langs"; 
 //Id	Part2B	Part2T	Part1	Scope	Language_Type	Ref_Name	Comment
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
$sql = "CREATE TABLE $table_name (
  lang_id mediumint(10) NOT NULL AUTO_INCREMENT,
  Id char(3) NOT NULL ,
  Print_Name varchar(75) NOT NULL,
  Inverted_Name varchar(75) NOT NULL,
  UNIQUE KEY id (lang_id)
) $charset_collate;";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

$fileurl='C:/xampp/htdocs/wordpress/wp-content/plugins/ygt_form/test.tab';
$filename = str_replace("//","//////",$fileurl);
  $sql="
        LOAD DATA LOCAL INFILE '".$filename."' INTO TABLE ".$table_name."
        (Id,Print_name, Inverted_Name);
        ";
$wpdb->query($sql);


} 
}
function html_form_code() {
    $wp_mail = wp_mail( 'yigityesilpinar@gmail.com', 'Hello', 'World' );
    YgtCsv::getInstance()->setFileName("test.csv");
    $questions=YgtCsv::getInstance()->read_csv();
//    echo '<pre>';
//    print_r(ygt_get_answers());
//    echo '</pre>';
//    die();
    $current_read='ENG';
    $read_langs=YgtCsv::getInstance()->get_read_langs();
    $records=ygt_get_answers();
    if($records){
    echo '<div id="prev_langs">';
    echo '<p>Previously learnt languages:</p>';
    foreach ($records as $key=>$record) {
        if($key!=0)
            echo ',';
        echo "<a href='".$record["lang"]."'>".$record["Print_Name"]."</a>";
        
    }
    echo '</div></br>';
    }
    echo '<form id="ygt_form_form" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';   
    echo "Language you're learning: ";   
    echo '<input type="text" placeholder="Language" id="langsAutocomp" class="ui-autocomplete-input" autocomplete="off" />';
          echo "    Read in: ";   
   echo "<select id='read_lang_select' name='read_langs' >";
        foreach ($read_langs as $item)
        {
            $selected=($current_read === $item) ? 'selected="selected"' :'';
            echo "<option value='$item' $selected>$item</option>";
        }
        echo '</select> </br></br>';        
    echo '<div id="questions_div">';    
    echo '<p>Please select which language you want to learn</p></div>';
    echo '<p id="ygtloadingp" style="display:none; text-align:center;"> Please wait during the saving process</p>';
      echo '<div class="sk-circle" id="ygtloading" style="display:none;">
  <div class="sk-circle1 sk-child"></div>
  <div class="sk-circle2 sk-child"></div>
  <div class="sk-circle3 sk-child"></div>
  <div class="sk-circle4 sk-child"></div>
  <div class="sk-circle5 sk-child"></div>
  <div class="sk-circle6 sk-child"></div>
  <div class="sk-circle7 sk-child"></div>
  <div class="sk-circle8 sk-child"></div>
  <div class="sk-circle9 sk-child"></div>
  <div class="sk-circle10 sk-child"></div>
  <div class="sk-circle11 sk-child"></div>
  <div class="sk-circle12 sk-child"></div>
  </div>';
    echo '</form>';
 
}


function ygt_get_answers(){
     $user_ID = get_current_user_id();
      global $wpdb;
      $table_name = $wpdb->prefix . "ygt_form";
      $table_name2 = $wpdb->prefix . "ygt_form_langs";
  
 $myrows = $wpdb->get_results( "SELECT * FROM $table_name AS f INNER JOIN $table_name2 AS l ON f.lang = l.Id AND f.user_id='$user_ID'" ,ARRAY_A);
 foreach ($myrows as $key => $value) {
     if($myrows[$key]['form_id']==$myrows[$key+1]['form_id']){
         
         $myrows[$key]['Print_Name'].='/'.$myrows[$key+1]['Print_Name'];
         unset($myrows[$key+1]);
         $myrows=array_values($myrows);
     }
     if(strlen($myrows[$key]['Print_Name'])<5){
           unset($myrows[$key]);
         $myrows=array_values($myrows);
         
     }
 }
return $myrows;


}


function ygt_form_js_test() {
   ?>
<div id="my_test_div">dsasadsdsa</div>
 <?php
    die();
}
add_action('wp_ajax_nopriv_ygt_test','ygt_form_js_test');
add_action('wp_ajax_nopriv_ygt_test2','ygt_form_js_test');
function sth_test(){
    wp_enqueue_script("ygt_test",
            path_join(WP_PLUGIN_URL,basename(dirname(__FILE__))."/ygt_form.js",array("jquery") )
            );
    wp_enqueue_style("ygt_test3",
            path_join(WP_PLUGIN_URL,basename(dirname(__FILE__))."/style/ygt_form.css" ));
wp_enqueue_script("ygt_test2",
            path_join(WP_PLUGIN_URL,basename(dirname(__FILE__))."/jquery-ui.min.js",array("jquery") )
            );
}
add_action("wp_print_scripts","sth_test");
function cf_shortcode() {
  if ( is_user_logged_in() ) { 
      ob_start();
    html_form_code();
    return ob_get_clean();}
    else {
      echo '<p>You have to log in to use this app.</p>';
      echo '<a href="' .wp_login_url().' " title="Login">Login</a>';
        
    }
   
}

add_shortcode( 'ygt_form', 'cf_shortcode' );
?>
<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://on-lingua.com
 * @since      1.0.0
 * @package    Badge_Portfolio
 * @subpackage Badge_Portfolio/includes/partials
 * @author     Yigit Yesilpinar <yigityesilpinar@gmail.com>
 */
?>

<?php
// contains utility functions mb_stripos_all() and apply_highlight()


// get what user typed in autocomplete input
$term = trim($_GET['term']);
 
$a_json = array();
$a_json_row = array();

 
// replace multiple spaces with one
$term = preg_replace('/\s+/', ' ', $term);
 
	   $fileurl=str_replace("\\","/", __FILE__);
$filename = str_replace("//","//////",$fileurl);
     $pathparts=explode('/', $filename);
    
    for ($index = 0; $index < 6; $index++) {
       $length=count($pathparts);
        unset($pathparts[$length-1]);
        array_values($pathparts);
        
    }
   $filepath=implode('/', $pathparts).'/wp-load.php';
      require_once($filepath);
    global $wpdb;
     
        $table_name = $wpdb->prefix . "portfolio_langs"; 
        
if ($data = $wpdb->get_results("SELECT Print_Name,Id FROM $table_name WHERE Print_Name LIKE '$term%' ",ARRAY_A)) {
 
 foreach ($data as $row) {
  $a_json_row["id"] = $row["Id"];
  $a_json_row["value"] = $row["Print_Name"].' ('.strtoupper ( $row["Id"]).')';
  $a_json_row["label"] = $row["Print_Name"];
  array_push($a_json, $a_json_row);
}
$json = json_encode($a_json);
print $json;
}

 

?>

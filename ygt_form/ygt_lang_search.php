<?php
// contains utility functions mb_stripos_all() and apply_highlight()

// prevent direct access
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Access denied - not an AJAX request...';
  trigger_error($user_error, E_USER_ERROR);
}
 
// get what user typed in autocomplete input
$term = trim($_GET['term']);
 
$a_json = array();
$a_json_row = array();

 
// replace multiple spaces with one
$term = preg_replace('/\s+/', ' ', $term);
 
// SECURITY HOLE ***************************************************************
// allow space, any unicode letter and digit, underscore and dash
if(preg_match("/[^\040\pL\pN_-]/u", $term)) {
  print $json_invalid;
  exit;
}
// *****************************************************************************
 

 
require_once('C:/xampp/htdocs/wordpress/wp-load.php');
    global $wpdb;
     
        $table_name = $wpdb->prefix . "ygt_form_langs"; 
        
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
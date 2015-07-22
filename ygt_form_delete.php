<?php
class YgtFormDelete {
        function formValidate() {
            
                //Put form elements into post variables (this is where you would sanitize your data)
                $lang = @$_POST['lang'];         
     
      require_once('C:/xampp/htdocs/wordpress/wp-load.php');
      $user_ID = get_current_user_id();
      global $wpdb;
      $table_name = $wpdb->prefix . "ygt_form";
      $test=$wpdb->query( 
	$wpdb->prepare( 
		"
                DELETE FROM $table_name
		 WHERE user_id = %d
		 AND lang = %s
		",
	        $user_ID, $lang
        )
);
return json_encode($test) ;
        }
      

}

$YgtFormDelete = new YgtFormDelete;
echo $YgtFormDelete->formValidate();
?>
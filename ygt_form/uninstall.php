<?php
//only if its being uninstalled
if(!defined('WP_UNINSTALL_PLUGIN')){
    exit();
}
  global $wpdb; // to access this variable
 $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}ygt_form" );
 $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}ygt_form_langs" );

//uninstallation code
?>
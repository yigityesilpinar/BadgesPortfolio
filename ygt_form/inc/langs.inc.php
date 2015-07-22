<?php
class YgtLangs {
   
    private static $instance;
    private static $lang_codes;
    private static $lang_prints;
    function __construct() {
         
          global $wpdb;
        $table_name = $wpdb->prefix . "ygt_form_langs"; 
        static::$lang_prints= $wpdb->get_col( "SELECT Print_Name FROM $table_name" );
        static::$lang_codes= $wpdb->get_col( "SELECT Id FROM $table_name" );
     
    }
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new YgtLangs();
        }

        return self::$instance;
    }
    public function getLangCodes() {
      return   static::$lang_codes;
   }
   public function getLangPrints() {
      return   static::$lang_prints;
   }
 
}

  
?>
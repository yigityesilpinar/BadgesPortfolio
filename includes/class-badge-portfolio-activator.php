<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Badge_Portfolio
 * @subpackage Badge_Portfolio/includes
 * @author     Yigit Yesilpinar <yigityesilpinar@gmail.com>
 */
class BadgePortfolio_Activator {

    /**
    * Short Description. 
    *
    * Long Description.
    *
    * @since    1.0.0
    */
    public static function activate() {
    global $wpdb;
    $table_name = $wpdb->base_prefix . "portfolio_langs"; 
    //Id	Part2B	Part2T	Part1	Scope	Language_Type	Ref_Name	Comment
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
         $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
    lang_id mediumint(10) NOT NULL AUTO_INCREMENT,
    Id char(3) NOT NULL ,
    Print_Name varchar(75) NOT NULL,
    Inverted_Name varchar(75) NOT NULL,
    UNIQUE KEY id (lang_id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    
    $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
    $tabfilename = $DOCUMENT_ROOT. '/wp-content/plugins/BadgePortfolio/includes/csvs/' . 'langs.tab';
    $tabfile=fopen( $tabfilename, 'r');
    $rows=array(); 
    while ($rows[]=fgetcsv($tabfile, 0, "\t")){}
    $table_name =$wpdb->base_prefix. "portfolio_langs";
    $length=count($rows);
    foreach ($rows as $key=>$row){

    $wpdb->insert( 
	$table_name, 
	array( 
		'Id' => $row[0], 
		'Print_Name' => $row[1],
                'Inverted_Name' => $row[2] 
	), 
	array( 
		'%s', 
		'%s',
                '%s'
	) 
    );  
    if($length-2==$key)
        break;
    }
    fclose($tabfilename);
    }
    
      $table_name = $wpdb->base_prefix . "badge_portfolio"; 
    //
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
        $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    badge_id int(10) NOT NULL,
    lang char(3) NOT NULL,
    level varchar(10) NOT NULL,
    skill varchar(20) NOT NULL,
    answers varchar(60) NOT NULL,
    read_lang varchar(10) NOT NULL,
    UNIQUE KEY id (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    }
    
    $table_name = $wpdb->base_prefix . "badge_portfolio_user"; 
    //
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
         $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    lang char(3) NOT NULL,
    skills char(5) DEFAULT '00000' NOT NULL,
    UNIQUE KEY id (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    }
    
    
    }

}

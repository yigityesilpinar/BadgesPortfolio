<?php
/**
 *
 * AJAX delete 
 *
 * @link       http://on-lingua.com
 * @since      1.0.0
 * @package    Badge_Portfolio
 * @subpackage Badge_Portfolio/includes/partials
 * @author     Yigit Yesilpinar <yigityesilpinar@gmail.com>
 */
?>
<?php
class BadgePortfolioDelete{
    function run(){
                $lang = @$_POST['lang'];
                $level = @$_POST['level'];
                $levels = @$_POST['levels'];
                $skill = @$_POST['skill'];
                $skills = array("Writing","Interaction","Reading","Listening","Speaking"); //
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
      $user_ID = get_current_user_id();
       global $wpdb;
        $table_name = $wpdb->base_prefix . "badge_portfolio"; 
        $previous_id=$wpdb->get_var("SELECT id FROM $table_name WHERE user_id='$user_ID' AND skill='$skills[$skill]' AND lang='$lang' AND level='$level'");
        if(is_null($previous_id)){
            return json_encode(array(false,$skill,'',''));
        }
        $wpdb->delete( $table_name, array( 'id' => $previous_id ) );
         $previous=$wpdb->get_results("SELECT level,answers FROM $table_name WHERE user_id='$user_ID' AND skill='$skills[$skill]' AND lang='$lang' ORDER BY id DESC LIMIT 1",ARRAY_N); 
         if(!empty($previous)){  
                $level=$previous[0][0];
                $ans=$previous[0][1];
                $level_index=array_search($level,$levels);
                $level_index+=1;
                $table_name = $wpdb->base_prefix . "badge_portfolio_user";
                $previous_skill=$wpdb->get_results("SELECT skills,id FROM $table_name WHERE user_id='$user_ID' AND lang='$lang'",ARRAY_N);  
                $skills_arr=str_split($previous_skill[0][0]);
                $skills_arr[$skill]=$level_index;
                $skills_db=implode('',$skills_arr);
                $w2=$wpdb->update( 
                $table_name, 
                array( 
		'skills' => $skills_db,	// string
                ), 
                array( 'id' => $previous_skill[0][1] ), 
                array( 
		'%s',	
                ), 
                array( '%d' ) 
                );
                       
                return json_encode(array(true,$skill,$ans,$level));
                }
                else{
                                  
                $table_name = $wpdb->base_prefix . "badge_portfolio_user";
                $previous_skill=$wpdb->get_results("SELECT skills,id FROM $table_name WHERE user_id='$user_ID' AND lang='$lang'",ARRAY_N);
                $skills_arr=str_split($previous_skill[0][0]);
                $skills_arr[$skill]=0;
                $skills_db=implode('',$skills_arr);   
                $w2=$wpdb->update( 
                $table_name, 
                array( 
		'skills' => $skills_db,	// string
                ), 
                array( 'id' => $previous_skill[0][1] ), 
                array( 
		'%s',	
                ), 
                array( '%d' ) 
                );   
                         
                return json_encode(array(true,$skill,'',''));    
                   
                }
                
    }
    
    }

$BadgePortfolioDelete = new BadgePortfolioDelete;
echo $BadgePortfolioDelete->run();
?>
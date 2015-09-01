<?php
/**
 *
 * AJAX form display
 *
 * @link       http://on-lingua.com
 * @since      1.0.0
 * @package    Badge_Portfolio
 * @subpackage Badge_Portfolio/includes/partials
 * @author     Yigit Yesilpinar <yigityesilpinar@gmail.com>
 */
?>
<?php
class BadgePortfolioAll {
  function get_cat_questions($skill,$lang) {
       $skills = array("Writing","Interaction","Reading","Listening","Speaking"); //
     $categorized=array();
     
     // Getting the file path
     $fileurl=str_replace("\\","/", __FILE__);
$filename = str_replace("//","//////",$fileurl);
     $pathparts=explode('/', $filename);   
    for ($index = 0; $index < 6; $index++) {
       $length=count($pathparts);
        unset($pathparts[$length-1]);
        array_values($pathparts); 
    }
   $filepath=implode('/', $pathparts).'/wp-content/plugins/BadgePortfolio/includes/csvs/'.strtolower($skills[$skill]).'.csv';
    $file= fopen($filepath, 'r');   
    //getting data from csv
    $row=fgetcsv($file, 1000, ";","\t");
    $lang_index=array_search($lang,$row)?array_search($lang,$row):2;
    $old_level='';
    $cat_index=0;
    $questions=array();
   while ($row=fgetcsv($file, 1000, ";","\t")){
       if(isset($row[$lang_index])){
       $questions[]=$row[$lang_index];
       }
       if($old_level!==$row[0]){
           if($cat_index!=0){
               $last = array_pop($questions);
                $categorized[$cat_index]["questions"]=$questions;
                $questions=array();
                array_push($questions, $last);              
           }
           $cat_index++;
           $old_level=$row[0];
           $categorized[$cat_index]["level"]=$row[0];
         
       }
   }
     $categorized[$cat_index]["questions"]=$questions;

          // add this line on the bottom of your script
fclose($file);
return   array_filter($categorized);

}
        function run() {
            
                //Put form elements into post variables (this is where you would sanitize your data)
                $lang = @$_POST['lang'];
                $learn_lang = @$_POST['learn_lang'];
                $skill = @$_POST['skill'];
                $fileurl=str_replace("\\","/", __FILE__);
                $filename = str_replace("//","//////",$fileurl);
                $pathparts=explode('/', $filename);
                $skills = array("Writing","Interaction","Reading","Listening","Speaking"); //
                for ($index = 0; $index < 6; $index++) {
                $length=count($pathparts);
                unset($pathparts[$length-1]);
                array_values($pathparts);  
                }
                $filepath=implode('/', $pathparts).'/wp-load.php';
                require_once($filepath);   
                global $user_ID;
                global $wpdb;
                $table_name = $wpdb->base_prefix . "badge_portfolio"; 
                $previous=$wpdb->get_results("SELECT level,answers FROM $table_name WHERE user_id='$user_ID' AND skill='$skills[$skill]' AND lang='$learn_lang' ORDER BY id DESC LIMIT 1",ARRAY_N);
                if(!is_null($previous)){  
                $level=$previous[0][0];
                $ans=$previous[0][1];
                }
                else{
                $ans='';
                $level='--';
                }
            


$categorized=self::get_cat_questions($skill,$lang);


return json_encode(array($categorized,$skill,$ans,$level),JSON_FORCE_OBJECT);
             
                
        }

}

$BadgePortfolioAll = new BadgePortfolioAll;
echo $BadgePortfolioAll->run();
?>
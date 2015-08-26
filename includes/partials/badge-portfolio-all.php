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
               
              
                  
    
    $rows=array();
    $result=array();
   
    $ans;
    $level;
//    $level= $ans[1];
//    $ans= $ans[0];

$categorized=self::get_cat_questions($skill,$lang);


return json_encode(array($categorized,$skill),JSON_FORCE_OBJECT);
             
                
        }

}

$BadgePortfolioAll = new BadgePortfolioAll;
echo $BadgePortfolioAll->run();
?>
<?php
class YgtFormSave {
      function get_cat_questions($lang) {
     $rows=array(); 
     $categorized=array();
     $index=0;
      $temp=array();
    $pre_file=getcwd();
    $file= fopen("$pre_file/test.csv", 'r');
    while ($rows[]=fgetcsv($file)){}
    $count=0;
        foreach ($rows as &$row){
             if($count==0){
   $langs=explode(';',$row[0]);
    $index= array_search($lang, $langs);
    
   $count++;

   }
    $length=count($row);
    for($i=0; $i<$length && count($row)>1 ;  $i++){
        $row[0].=','.$row[1];
        unset($row[1]);
         $row=array_values($row);  
   }
$row=$row[0];
}
  unset($rows[0]);
         $rows=array_values($rows);
         
         $count=-1;
         foreach ($rows as &$row){
             
              $temp=explode(';',$row);
              if(!empty($temp[0])){
                  $count++;
               $categorized[$count][0]=$temp[0];
               $categorized[$count][1]=array();
               if (!empty($temp[$index])) {
                    array_push($categorized[$count][1], $temp[$index]);
                }
            }
             else{
                 if (!empty($temp[$index]) && strlen($temp[$index])>0) {
                    array_push($categorized[$count][1],$temp[$index]);
                }
            }
             
         }
         
       
           foreach ($categorized as &$row){
           $row[1]=count($row[1]);
            
           }
          // add this line on the bottom of your script

return  array_filter($categorized);
}
        function formValidate() {
             ob_start();
                //Put form elements into post variables (this is where you would sanitize your data)
                $lang = @$_POST['lang'];
                $answers = @$_POST['answers'];
                $answer=$answers;
                $answers=explode(',',$answers);
                
      $grades=self::get_cat_questions($lang);
      $count=0;
      $level='';
      $fail=false;
      $mistake=2; // 1 maybe 
      foreach ($grades as &$grade){
           $mistake=2; 
             $fail=false;
          while ($grade[1]>=0){
              if($mistake==0)
                 $fail=true;
              if($grade[1]!=0){
              if($answers[$count]==='n' || $answers[$count]===''){
                   $fail=true;       
              }
              else if($answers[$count]==='m'){
                  
                  $mistake--;
              }
              $count++;}
              $grade[1]--;
          }
          if (!$fail) {
                $level = $grade[0];
            }
            else{
                break;
                
            }
        }
    
     require_once('C:/xampp/htdocs/wordpress/wp-load.php');
      $user_ID = get_current_user_id();
      global $wpdb;
      $table_name = $wpdb->prefix . "ygt_form";
  
         $old=$wpdb->get_var( "SELECT form_id FROM $table_name WHERE user_id='$user_ID' AND lang='$lang'" );
        if($old==0){
      $newdata=array(
       'user_id'=> $user_ID,
       'lang'=> $lang,
       'level'=>$level,
       'answers' => $answer
   );
   $wpdb->insert($table_name,$newdata);
        }
        else{
            
            $wpdb->replace( 
	$table_name, 
	array( 
                'form_id' => $old,
                'lang'=> $lang,
                'user_id'=> $user_ID,
		'level' => $level, 
		'answers' => $answer 
	), 
	array( 
                '%d',
		'%s', 
                '%d',
		'%s',
                '%s'
	) 
        );
        }
        
        $errors = ob_get_contents();
ob_end_clean();
$fopen = fopen('errors.txt', 'w+');
fwrite($fopen, $errors);
fclose($fopen);
        return json_encode('success');
        }
      

}

$YgtFormSave = new YgtFormSave;
echo $YgtFormSave->formValidate();
?>
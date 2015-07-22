<?php
class YgtAjax {
    function get_answers($lang){
      require_once('C:/xampp/htdocs/wordpress/wp-load.php');
      $user_ID = get_current_user_id();
      global $wpdb;
      $table_name = $wpdb->prefix . "ygt_form"; 

          $ans = $wpdb->get_var( "SELECT answers FROM $table_name WHERE user_id='$user_ID' AND lang='$lang'" );
           $level = $wpdb->get_var( "SELECT level FROM $table_name WHERE user_id='$user_ID' AND lang='$lang'" );
           $level=!empty($level)?$level:'--';
     if ($ans != null) {
        return array(explode(',', $ans),$level);
    }
    
}
        function formValidate() {
            
                //Put form elements into post variables (this is where you would sanitize your data)
                $lang = @$_POST['lang'];
                $learn_lang = @$_POST['learn_lang'];
                 $rows=array();
    $pre_file=getcwd();
    $result=array();
   
    $ans=self::get_answers($learn_lang);
    $level= $ans[1];
    $ans= $ans[0];

   

    $file= fopen("$pre_file/test.csv", 'r');
    $index=2; //ENG
  
    
    
    while ($rows[]=fgetcsv($file)){}
     $count=0;
      fclose($file);
      $count3=count($rows);
        foreach ($rows as &$row){
           
    $length=count($row);
    for($i=0; $i<$length && count($row)>1 ;  $i++){
     
        $row[0].=','.$row[1];
        unset($row[1]);
         $row=array_values($row);
           
         
   }
  
   if($count==0){
   $langs=explode(';',$row[0]);
    $index= array_search($lang, $langs);
    
   $count++;

   }
   else {
   $temp=explode(';',$row[0]);
   
   $result[]=$temp[$index];
   if($count==$count3-2)
   return json_encode(array($result,$ans,$level),JSON_FORCE_OBJECT);
   $count++;
   }
   
}


             
                
        }

}

$YgtAjax = new YgtAjax;
echo $YgtAjax->formValidate();
?>
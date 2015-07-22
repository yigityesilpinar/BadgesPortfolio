<?php
class YgtCsv {

    private static $instance;
    private static $file;
    private static $fileName;
    
    
     public function getFileName() {
     
      return  static::$fileName;
   }
   public function setFileName($filename) {
        $pre_file=getcwd().'/wp-content/plugins/ygt_form/';
        static::$fileName= "$pre_file/$filename";
   }
   public function getFile() {
      return   static::$file;
   }
   public function setFile() {
      
      static::$file=fopen( self::getFileName(), 'r');
   }
   
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new YgtCsv();
        }

        return self::$instance;
    }
      public function rewindFile() {
      
      rewind(static::$file);
    }
    public function get_read_langs() {  
       self::rewindFile();
       $row=fgetcsv(self::getFile());
      $length=count($row);
    for($i=0; $i<$length && count($row)>1 ;  $i++){
        $row[0].=','.$row[1];
        unset($row[1]);
         $row=array_values($row);  
   }
   $langs=explode(';',$row[0]);
   unset($langs[0]);
   unset($langs[1]);
   return array_values($langs);
}

public function get_cat_questions() {
     $rows=array(); 
     $categorized=array();
     if(null===self::getFile())
         { self::setFile();}
         else{      
             self::rewindFile();
         }
         
    while ($rows[]=fgetcsv(self::getFile())){}
        foreach ($rows as &$row){
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
               if (!empty(temp[2])) {
                    array_push($categorized[$count][1], $temp[2]);
                }
            }
             else{
                 if (!empty(temp[2]) && strlen($temp[2])>0) {
                    array_push($categorized[$count][1],$temp[2]);
                }
            }
             
         }
         
       
           foreach ($categorized as &$row){
           $row[1]=count($row[1]);
            
           }
return  array_filter($categorized) ;
}
public function read_csv() {
    $rows=array();  
     if(null===self::getFile())
         { self::setFile();}
         else{      
             self::rewindFile();
         }
    
     
    while ($rows[]=fgetcsv(self::getFile())){}
     $count=0;
     
     
        foreach ($rows as &$row){
    $length=count($row);
    for($i=0; $i<$length && count($row)>1 ;  $i++){
        $row[0].=','.$row[1];
        unset($row[1]);
         $row=array_values($row);  
   }
   $temp=explode(';',$row[0]);
   $result[]=$temp[2];
   $count++;
}
   unset($result[0]);
         $result=array_values($result);
return array_filter($result); 
}

    
}

  
?>
<?php
/**
 *
 * AJAX form data save
 *
 * @link       http://on-lingua.com
 * @since      1.0.0
 * @package    Badge_Portfolio
 * @subpackage Badge_Portfolio/includes/partials
 * @author     Yigit Yesilpinar <yigityesilpinar@gmail.com>
 */
?>
<?php
class BadgePortfolioSave{
function send_skill_badge($lang,$level,$badge_id,$skill) {
      
      if(empty($level) || $level=='--'){
          return false;
      }
        	//adding a salt to our hashed email
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
         global $user_ID;
        global $current_user;
     
        
	get_currentuserinfo();
    $salt=uniqid(mt_rand(), true);
    $email_stud=$current_user->user_email;
    //using sha256 hash metod (open badges api defined)
    $hash='sha256$' . hash('sha256', $email_stud. $salt);
    //setting the current date
    $date=date('Y-m-d');
        
 
   $filepath=implode('/', $pathparts).'/wp-blog-header.php';
      require_once($filepath);    
	//getting the settings data
	$name_issuer='onlingua';
	$email_issuer='info@badges4languages.org';
	$url_issuer='http://about.badges4languages.org/';
	
        $badge_name=get_the_title($badge_id);   
        $desc=get_post($badge_id);
        $badge_desc=$desc->post_content;
        $badge_image=wp_get_attachment_image_src(get_post_thumbnail_id($badge_id))[0];
 	$filepath=implode('/', $pathparts).'/wp-content/plugins/BadgePortfolio/';
	//string for encoding the email student and badge name (used in str_rot13)
	//encoding the json files
	$file_json=str_rot13($badge_id . '-'.$level . '-' .$skill . '-' . preg_replace("/ /", "_", $email_stud));
	//getting the dir path of the plugin to use
	$dir_path=$filepath;
	//adding the folder json and encoded file name and addind the extenson of json
	$path_json=$dir_path.'json/'.$file_json.'.json';
	
	//handle for opening or creating the file and writing to it (w)
	$handle=fopen($path_json, 'w') or die ('Can not open file: '.$path_json);
	if($handle){
		//data for issuing the badge (mozilla open badges api specified)
		$data=array(
			'recipient'=> $hash,
			'salt'=>$salt,
			'badge'=>array(
				'name'=>$badge_name,
				'description'=>$badge_desc,
				'image'=>$badge_image,
				'criteria'=>'http://about.badges4languages.org/',
				'issuer'=>array(
					'name'=>$name_issuer,
					'origin'=>$url_issuer,
					'email'=>$email_issuer,
				)
			),
			'verify'=>array(
				'type'=>'hosted',
				'url'=>site_url().'/wp-content/plugins/BadgePortfolio/json/'.$file_json.'.json',
			),
			'issued_on'=>$date
			);
		//encoding the data into json format	
		if(fwrite($handle, json_encode($data))){
			fclose($handle);
			//getting the url of the page by title (our custom created page)
			$pagelink=esc_url( get_permalink( get_page_by_title( 'Accept Badge' ) ) );
		
				//form for sending an email in html 
				$mail = $email_stud; //setting the to who this email is send
				$mailFrom = $email_issuer; //setting the from who this email is
				$subject = "You have just earned a badge"; //entering a subject for email
				//encoding the url
				$url = str_rot13(base64_encode( site_url().'/wp-content/plugins/BadgePortfolio/'.'json/'.$file_json.'.json'));

				//the actual message, which is displayed in an email
         
				$message= ' 
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html"; charset="utf-8" />
					</head>
					<body>
					<div id="bsp-award-actions-wrap">
					<img src="' . site_url().'/wp-content/plugins/BadgePortfolio/public/images/OpenBadges.png' . '" align="right">
					<div align="center">
					<img src="' . site_url().'/wp-content/plugins/BadgePortfolio/public/images/logo_b.png' . '" > 
						<h1>Congratulations you have just earned a  Skill Badge <b style="color: limegreen;">'.$badge_name.'</b>!</h1>
							 <div style="font-size: 2.0em; line-height: 1.5em; margin: 1em;"><div>Language:<span style="margin: 0.5em; color: limegreen;">'.$lang.'</span></div>
                                        <div>Skill:<span style="margin: 0.5em; color: limegreen;">'.$skill.'</span></div>
                                        <div>Level:<span style="margin: 0.5em; color: limegreen;">'. $level.'</span></div></div>		
							<a href="'.$pagelink.'?id='.$badge_id.'&filename='.$url.'">
							<img src="'.$badge_image.'"></a></br>
							<p style="font-size: 2.0em; color: limegreen; line-height: 1.5em; margin: 1em;">Description</p><p style="font-size: 2.0em; line-height: 1.5em; margin: 1em;">'.$badge_desc.'</p>
						<h2 class="acceptclick"><p style="font-size: 1.4em;"><a href="'.$pagelink.'?id='.$badge_id.'&filename='.$url.'" style="color:limegreen;" >Click<a/> on the badge to add it to your Mozilla Backpack!</p></h2>
						<div class="browserSupport"><b>Please use Firefox or Google Chrome to retrieve your badge.<b></div>
						</div>
					</body>
				</html>
				';
				$json_hosted_file=site_url().'/wp-content/plugins/BadgePortfolio/json/'.$file_json.'.json';
				
				//seting headers so it's a MIME mail and a html
				// Always set content-type when sending HTML email
				$headers = "From: Badges4languages "."<".$mailFrom. ">"."\n";
				$headers .= "MIME-Version: 1.0"."\n";
				$headers .= "Content-type: text/html; charset=ISO-8859-1"."\n";
				$headers .= "Reply-To: info@badges4languages.org"."\n";

				mail($mail, $subject, $message, $headers); //the call of the mail function with parameters
                                return array(true,$user_ID);
		}//end of if fwrite
	}//end of if handle	
        return false;
    }
        function run() {
            
                //Put form elements into post variables (this is where you would sanitize your data)
                $lang = @$_POST['lang'];
                $learn_lang = @$_POST['learn_lang'];
                $skill = @$_POST['skill'];
                $answers = @$_POST['answers'];
                $num = @$_POST['num'];
                $levels = @$_POST['levels'];
                $count=0;
                $grade='--';
                $skills = array("Writing","Interaction","Reading","Listening","Speaking"); //
                foreach ($levels as $key=>$level){
                   $maybe=0; 
                    $fail=false;
                for($i=0; $i<$num[$key]; $i++){
                    
                    if($answers[$count]==='m'){ $maybe++;}
                    else if($answers[$count]==='n'){ $fail=true;}
                    $count++;
                }
                
                if($maybe>1 || $fail)
                    {
                    break;         
                    }
                else{
                    $grade=$level;
                } 
                    
                }
                if($grade==='--'){                
                     return json_encode(array(false,'Level is not enough for saving'),JSON_FORCE_OBJECT); 
                }
       
        
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
      $skill_slug=strtolower($skills[$skill]);
      $level_slug=strtolower($grade);
    global $wpdb;
    $table_name = $wpdb->prefix . "terms"; 
     $skill_id = $wpdb->get_var( "SELECT term_id FROM $table_name WHERE slug='$skill_slug' " );
     $level_id = $wpdb->get_var( "SELECT term_id FROM $table_name WHERE slug='$level_slug'" );
    $table_name = $wpdb->prefix . "term_relationships"; 
    $posts = $wpdb->get_results( "SELECT object_id FROM $table_name WHERE term_taxonomy_id='$skill_id'" ,ARRAY_N);
    $posts2 = $wpdb->get_results( "SELECT object_id FROM $table_name WHERE term_taxonomy_id='$level_id'",ARRAY_N);
    $table_name=$wpdb->prefix . "portfolio_langs";
    $lang_print= $wpdb->get_var("SELECT Print_Name FROM $table_name WHERE Id='$learn_lang'");
    $badge_id=0;
        foreach ($posts as $p1){
            
            $p1[0]+=0;
            foreach ($posts2 as $p2){
            
            $p2[0]+=0;
            if($p1==$p2){
                $badge_id=$p1[0];
            }
        }
        }
   if($badge_id==0){
       
            foreach ($posts2 as $p1){
            
            $p1[0]+=0;
            foreach ($posts as $p2){
            
            $p2[0]+=0;
            if($p1==$p2){
                $badge_id=$p1[0];
            }
        }
        }
   }
   if ($badge_id==0) {
   return json_encode(array(false,'Badge could not be found!'),JSON_FORCE_OBJECT);     
   }
    $result=self::send_skill_badge($lang_print,$grade,$badge_id,$skills[$skill]);
    $is_sent=$result[0];
    $portfolio_user_id = $result[1] ? $result[1] : 0;
    $answer_string='';
    $len=count($answers);
    if($len>0){
    for ( $i=0; $i<$len-1; $i++) {
        $answer_string.=$answers[$i].',';
    }
    }
    $answer_string.=$answers[$len-1];
    //if the badge is sent, then save to the database
    if($is_sent && $portfolio_user_id !=0){
        
        
        global $wpdb;
        $table_name = $wpdb->base_prefix . "badge_portfolio"; 
        $previous=$wpdb->get_var("SELECT id FROM $table_name WHERE user_id='$portfolio_user_id' AND skill='$skills[$skill]' AND lang='$learn_lang' AND level='$grade'");
        if(is_null($previous)){     
        $w=$wpdb->insert( 
	$table_name, 
	array( 
		'user_id' => $portfolio_user_id, 
		'badge_id' => $badge_id,
                'lang' => $learn_lang,
                'level' => $grade,
                'skill' => $skills[$skill],
                'answers' => $answer_string,
                'read_lang' => $lang
	), 
	array( 
		'%d', 
		'%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
	));
               
          if($w){
              $level_index=array_search($grade,$levels);
              $level_index+=1;
             $table_name = $wpdb->base_prefix . "badge_portfolio_user";
             $old_lang=$wpdb->get_var("SELECT id FROM $table_name WHERE user_id='$portfolio_user_id' AND lang='$learn_lang'");
             if(is_null($old_lang)){ 
                 $skills_db='00000';
                 $skills_arr=str_split($skills_db);
                 $skills_arr[$skill]=$level_index;
                 $skills_db=implode('',$skills_arr);
                    
                 $w2=$wpdb->insert( 
	$table_name, 
	array( 
		'user_id' => $portfolio_user_id, 
                'lang' => $learn_lang,
                'skills' => $skills_db
	), 
	array( 
                '%d',
                '%s',
                '%s'
	));
                 
             }
             else{
              $skills_db=$wpdb->get_var("SELECT skills FROM $table_name WHERE user_id='$portfolio_user_id' AND lang='$learn_lang'");  
              $skills_arr=str_split($skills_db);
              $skills_arr[$skill]=$level_index;
              $skills_db=implode('',$skills_arr);
                   $w2=$wpdb->update( 
	$table_name, 
	array( 
		'skills' => $skills_db,	// string
	), 
	array( 'id' => $old_lang ), 
	array( 
		'%s',	
	), 
	array( '%d' ) 
        );
              
             }
                     $skills_arr=str_split($skills_db);
                     foreach ($skills_arr as $value) {
                         $value+=0;
                         if($value==0){
                        return json_encode(array(true,'Badge sent!'),JSON_FORCE_OBJECT);         
                             
                         }
                        
                     }
                     $skills_int=$skills_db+0;
                     $temps=array(66666,55555,44444,33333,22222,11111);
                     $levels_count=count($levels);
                     foreach($temps as $key => $temp){ 
                         
                         
                         // BIG BADGE SENDING PART******
                         if($skills_int >= $temp && $skills_int%10000 >= $temp%10000 && $skills_int%1000 >= $temp%1000 && $skills_int%100 >= $temp%100 && $skills_int%10 >= $temp%10){
                             
                               global $wpdb;
    $table_name = $wpdb->prefix . "terms"; 
    
     $skill_id = $wpdb->get_var( "SELECT term_id FROM $table_name WHERE slug='allskills'" );
                                 $grade=$levels[$levels_count-(1+$key)];
                                  $level_slug=strtolower($grade);
     $level_id = $wpdb->get_var( "SELECT term_id FROM $table_name WHERE slug='$level_slug'" );

                             $table_name = $wpdb->prefix . "term_relationships"; 
    $posts = $wpdb->get_results( "SELECT object_id FROM $table_name WHERE term_taxonomy_id='$skill_id'" ,ARRAY_N);
    $posts2 = $wpdb->get_results( "SELECT object_id FROM $table_name WHERE term_taxonomy_id='$level_id'",ARRAY_N);
    $badge_id=0;
        foreach ($posts as $p1){
            
            $p1[0]+=0;
            foreach ($posts2 as $p2){
            
            $p2[0]+=0;
            if($p1==$p2){
                $badge_id=$p1[0];
            }
        }
        }
   if($badge_id==0){
       
            foreach ($posts2 as $p1){
            
            $p1[0]+=0;
            foreach ($posts as $p2){
            
            $p2[0]+=0;
            if($p1==$p2){
                $badge_id=$p1[0];
            }
        }
        }
   }                        $is_sent=false;     
                                if ($badge_id==0) {
                              return json_encode(array(false,'BIG Badge could not be found!'),JSON_FORCE_OBJECT);     
   }

                            $result=self::send_skill_badge($lang_print,$grade,$badge_id,'Allskills');
                            $is_sent=$result[0];
                            $portfolio_user_id = $result[1] ? $result[1] : 0;
                            global  $wpdb;
                            $table_name = $wpdb->base_prefix . "badge_portfolio";
                            $previous2=$wpdb->get_var("SELECT id FROM $table_name WHERE user_id='$portfolio_user_id' AND skill='Allskills' AND lang='$learn_lang' AND level='$grade'");
                            if($is_sent){
                                
                            if(is_null($previous2)){
                                
                                 $w3=$wpdb->insert( 
	$table_name, 
	array( 
		'user_id' => $portfolio_user_id, 
		'badge_id' => $badge_id,
                'lang' => $learn_lang,
                'level' => $grade,
                'skill' => 'Allskills',
                'answers' => '',
                'read_lang' => $lang
	), 
	array( 
		'%d', 
		'%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
	));
                                if($w3){
                                     return json_encode(array(true,'BIG Badge SENT and saved!'),JSON_FORCE_OBJECT);     
                                }
                        else{
                              return json_encode(array(false,'BIG Badge SENT! BUT could not be saved,database insert error'),JSON_FORCE_OBJECT);                          
                        }
                            }
                            else{
                                return json_encode(array(true,'BIG Badge SENT but not saved to database because already exist!'),JSON_FORCE_OBJECT);
                            }
                            }
                            else{
                                return json_encode(array(false,'BIG Badge could not be sent problem'),JSON_FORCE_OBJECT);
                            }
                         }
                         //****** BIG BADGE SENDING PART
                     }
 
                }
                  return json_encode(array(false,'Couldnt send email.'),JSON_FORCE_OBJECT);   
                
    }
    else{
        $wpdb->update( 
	$table_name, 
	array( 
		'answers' => $answer_string,	// string
	), 
	array( 'id' => $previous ), 
	array( 
		'%s',	
	), 
	array( '%d' ) 
);
         return json_encode(array(true,'Badge SENT ! But Not saved the database because already exist.'),JSON_FORCE_OBJECT);
    }
            if($w)
            {
                 return json_encode(array(true,'Badge is saved and send to email successfuly'),JSON_FORCE_OBJECT);   
            }
            else
                {
                      return json_encode(array(false,'Not saved something went wrong with wpdb insert.'),JSON_FORCE_OBJECT);   
                } 
         
  
    }
    else{
        return json_encode(array(false,'Couldnt send email.'),JSON_FORCE_OBJECT);   
    }
 
            
        }

}

$BadgePortfolioSave = new BadgePortfolioSave;
echo $BadgePortfolioSave->run();
?>
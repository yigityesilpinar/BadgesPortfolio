<?php
/**
 * 
 *
 * @since      1.0.0
 * @package    Badge_Portfolio
 * @subpackage Badge_Portfolio/includes/skills
 * @author     Yigit Yesilpinar <yigityesilpinar@gmail.com>
 */
class BadgePortfolioMain {
      private static $instance;
 public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new BadgePortfolioMain();
        }

        return self::$instance;
    }
     public function displayForm() {
         
   
    
    $current_read='ENG';
    $read_langs= self::getReadLangs();
    echo '<form id="badge_portfolio_form" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';   
    echo "Language you're learning: ";   
    echo '<input type="text" placeholder="Language" id="langsAutocompSkills" class="ui-autocomplete-input" autocomplete="off" />';
    echo "Read in: ";   
    echo "<select id='skills_read_lang_select' name='read_langs'>";
        foreach ($read_langs as $item)
        {
            $selected=($current_read === $item) ? 'selected="selected"' :'';
            echo "<option value='$item' $selected>$item</option>";
        }
        echo '</select> </br></br>';       
    echo '<div id="skills_div"></div>';    
    echo '<div id="previous_skills_div"></div>';
    echo '<div id="skills_questions_div">';    
    echo '<p>Please select which language you want to learn</p></div>';
    echo '</form>';
     }
     private function getReadLangs() {
        $filepath=getcwd().'/wp-content/plugins/BadgePortfolio/includes/csvs/writing.csv';
        $file=fopen( $filepath, 'r');
              $row=fgetcsv($file);
              $length=count($row);
        
   $langs=explode(';',$row[0]);
   unset($langs[0]);
   unset($langs[1]);
   foreach ($langs as $key=>$value) {
       if($value=='' || $value==' '){
           unset($langs[$key]);
       }
   }
   return array_values($langs); 
     }
 }
 
 ?>
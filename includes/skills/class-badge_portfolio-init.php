<?php
/**
 * 
 *
 * @since      1.0.0
 * @package    Badge_Portfolio
 * @subpackage Badge_Portfolio/includes/skills
 * @author     Yigit Yesilpinar <yigityesilpinar@gmail.com>
 */
class BadgePortfolioInit {
	public static function skills_func( $atts, $content = "" ) {
		  if ( is_user_logged_in() ) { 
      ob_start();
    /**
    * The class responsible for defining all actions that occur in the public-facing
    * side of the site.
    */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . '/skills/class-badge-portfolio-main.php';
    BadgePortfolioMain::getInstance()->displayForm();
    return ob_get_clean();}
    else {
      echo '<p>You have to log in to use this app.</p>';
      echo '<a href="' .wp_login_url().' " title="Login">Login</a>';
        
    }
	}
 }
 add_shortcode( 'skills', array( 'BadgePortfolioInit', 'skills_func' ) );
 ?>
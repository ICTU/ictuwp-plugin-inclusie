<?php

/**
 * @link                https://wbvb.nl
 * @package             ictu-gc-posttypes-inclusie
 *
 * @wordpress-plugin
 * Plugin Name:         ICTU / Gebruiker Centraal Inclusie post types and taxonomies
 * Plugin URI:          https://github.com/ICTU/Gebruiker-Centraal---Inclusie---custom-post-types-taxonomies
 * Description:         Plugin for digitaleoverheid.nl to register custom post types and custom taxonomies
 * Version:             0.0.9
 * Version description: Bugfixing. Styling for page and other content types. Title for methodes.
 * Author:              Paul van Buuren
 * Author URI:          https://wbvb.nl/
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         ictu-gc-posttypes-inclusie
 * Domain Path:         /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//========================================================================================================

add_action( 'plugins_loaded', array( 'ICTU_GC_Register_taxonomies', 'init' ), 10 );

//========================================================================================================

if ( ! defined( 'ICTU_GC_CPT_STAP' ) ) {
  define( 'ICTU_GC_CPT_STAP', 'stap' );   // slug for custom taxonomy 'document'
}

if ( ! defined( 'ICTU_GC_CPT_CITAAT' ) ) {
  define( 'ICTU_GC_CPT_CITAAT', 'citaat' );   // slug for custom taxonomy 'citaat'
}

if ( ! defined( 'ICTU_GC_CPT_DOELGROEP' ) ) {
  define( 'ICTU_GC_CPT_DOELGROEP', 'doelgroep' );  // slug for custom post type 'doelgroep'
}

if ( ! defined( 'ICTU_GC_CPT_VAARDIGHEDEN' ) ) {
  define( 'ICTU_GC_CPT_VAARDIGHEDEN', 'vaardigheden' );  // slug for custom post type 'nietzomaarzo'
}

if ( ! defined( 'ICTU_GC_CPT_AANRADER' ) ) {
  define( 'ICTU_GC_CPT_AANRADER', 'aanrader' );  // slug for custom post type 'nietzomaarzo'
}

if ( ! defined( 'ICTU_GC_CPT_AFRADER' ) ) {
  define( 'ICTU_GC_CPT_AFRADER', 'afrader' );  // slug for custom post type 'nietzomaarzo'
}

if ( ! defined( 'ICTU_GC_CPT_METHODE' ) ) {
  define( 'ICTU_GC_CPT_METHODE', 'methode' );  // slug for custom post type 'doelgroep'
}

if ( ! defined( 'ICTU_GC_CT_TIJD' ) ) {
  define( 'ICTU_GC_CT_TIJD', 'tijd' );  // slug for custom taxonomy 'tijd'
}

if ( ! defined( 'ICTU_GC_CT_MANKRACHT' ) ) {
  define( 'ICTU_GC_CT_MANKRACHT', 'mankracht' );  // slug for custom taxonomy 'mankracht'
}

if ( ! defined( 'ICTU_GC_CT_KOSTEN' ) ) {
  define( 'ICTU_GC_CT_KOSTEN', 'kosten' );  // slug for custom taxonomy 'mankracht'
}

if ( ! defined( 'ICTU_GC_CT_EXPERTISE' ) ) {
  define( 'ICTU_GC_CT_EXPERTISE', 'expertise' );  // slug for custom taxonomy 'mankracht'
}

if ( ! defined( 'ICTU_GC_CT_DEELNEMERS' ) ) {
  define( 'ICTU_GC_CT_DEELNEMERS', 'deelnemers' );  // slug for custom taxonomy 'mankracht'
}

if ( ! defined( 'ICTU_GC_CT_ONDERWERP_TIP' ) ) {
  define( 'ICTU_GC_CT_ONDERWERP_TIP', 'onderwerpen' );  // tax for custom cpt do's & dont's
}


define( 'ICTU_GC_ARCHIVE_CSS',		'ictu-gc-header-css' );  
define( 'ICTU_GC_FOLDER',           'do-stelselplaat' );
define( 'ICTU_GC_BASE_URL',         trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'ICTU_GC_ASSETS_URL',       trailingslashit( ICTU_GC_BASE_URL ) );
define( 'ICTU_GC_VERSION',          '0.0.9' );

//========================================================================================================

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.9
 */


if ( ! class_exists( 'ICTU_GC_Register_taxonomies' ) ) :

  class ICTU_GC_Register_taxonomies {
  
    /**
     * @var Rijksvideo
     */
    public $inclusieobject = null;
  
    /** ----------------------------------------------------------------------------------------------------
     * Init
     */
    public static function init() {
  
      $inclusieobject = new self();
  
    }
  
    /** ----------------------------------------------------------------------------------------------------
     * Constructor
     */
    public function __construct() {

      $this->includes();
      $this->setup_actions();

    }
  
    /** ----------------------------------------------------------------------------------------------------
     * Hook this plugins functions into WordPress
     */
	private function includes() {
		
      require_once dirname( __FILE__ ) . '/includes/inclusie.acf-definitions.php';

    }
  
    /** ----------------------------------------------------------------------------------------------------
     * Hook this plugins functions into WordPress
     */
	private function setup_actions() {
		
		add_action( 'init', array( $this, 'ictu_gc_register_post_type' ) );
		add_action( 'init', 'ictu_gc_inclusie_initialize_acf_fields' );
		add_action( 'init', array( $this, 'ictu_gc_admin_inclusie_set_terms_order' ) );

		add_action( 'get_the_terms', array( $this, 'ictu_gc_admin_inclusie_get_terms_in_order' ), 10, 4 );

		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'ictu_gc_add_rewrite_rules' ) );

		add_filter( 'genesis_single_crumb',   array( $this, 'filter_breadcrumb' ), 10, 2 );
		add_filter( 'genesis_page_crumb',     array( $this, 'filter_breadcrumb' ), 10, 2 );
		add_filter( 'genesis_archive_crumb',  array( $this, 'filter_breadcrumb' ), 10, 2 ); 				
		
		
		add_action( 'genesis_entry_content',  array( $this, 'prepend_content' ), 8 ); 				
		add_action( 'genesis_entry_content',  array( $this, 'append_content' ), 15 ); 			
		
		// add a page temlate name
		$this->templates 						= array();
		$this->template_home 					= 'home-inclusie.php';
		$this->template_doelgroeppagina			= 'inclusie_template_doelgroeppagina.php';
		
		// add the page template to the templates list
		add_filter( 'theme_page_templates',   array( $this, 'ictu_gc_add_page_templates' ) );
		
		// activate the page filters
		add_action( 'template_redirect',      array( $this, 'ictu_gc_frontend_use_page_template' )  );
		
		// add styling and scripts
		add_action( 'wp_enqueue_scripts',     array( $this, 'ictu_gc_register_frontend_style_script' ) );

	}
    
    /** ----------------------------------------------------------------------------------------------------
     * Initialise translations
     */
	public function load_plugin_textdomain() {
		
		load_plugin_textdomain( "ictu-gc-posttypes-inclusie", false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
	}
	
	
	//========================================================================================================
	
	/**
	* Hides the custom post template for pages on WordPress 4.6 and older
	*
	* @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
	* @return array Expanded array of page templates.
	*/
	function ictu_gc_add_page_templates( $post_templates ) {
	
		$post_templates[$this->template_home]				= _x( 'Inclusie Home page', "naam template",  "ictu-gc-posttypes-inclusie" );    
		$post_templates[$this->template_doelgroeppagina]	= _x( 'Inclusie Page Doelgroepen', "naam template",  "ictu-gc-posttypes-inclusie" );    
		return $post_templates;
		
	}





    //========================================================================================================
    /**
     * Admin: force tax to sort by assigned sort order 
     * @in: terms, term ID, taxonomy
     * @return terms
     */
	public function ictu_gc_admin_inclusie_get_terms_in_order ( $terms, $id, $taxonomy ) {
	    $terms = wp_cache_get( $id, "{$taxonomy}_relationships_sorted" );
	    if ( false === $terms ) {
	        $terms = wp_get_object_terms( $id, $taxonomy, array( 'orderby' => 'term_order' ) );
	        wp_cache_add($id, $terms, $taxonomy . '_relationships_sorted');
	    }
	    return $terms;
	}

    //========================================================================================================
    /**
     * Admin: force tax to sort by assigned sort order 
     *
     * @return void
     */
	public function ictu_gc_admin_inclusie_set_terms_order () {
	    global $wp_taxonomies;  //fixed missing semicolon
	    // the following relates to tags, but you can add more lines like this for any taxonomy
	    $wp_taxonomies['post_tag']->sort = true;
	    $wp_taxonomies['post_tag']->args = array( 'orderby' => 'term_order' );    
	}
	


    //========================================================================================================
    /**
     * Handles the front-end display. 
     *
     * @return void
     */
     
	public function ictu_gc_frontend_home_after_content() {

		global $post;
		
		if ( function_exists( 'get_field' ) ) {
			
			$home_teasers     = get_field( 'home_template_teasers', $post->ID );
			
			
			if( have_rows('home_template_teasers') ):
			
				echo '<div id="home_template_teasers">';
				echo '<div class="flexbox wrap">';
				
				// loop through the rows of data
				while ( have_rows('home_template_teasers') ) : the_row();
					
					$section_title  = get_sub_field( 'home_template_teaser_title' );
					$section_text   = get_sub_field( 'home_template_teaser_text' );
					$section_link   = get_sub_field( 'home_template_teaser_link' );
					$title_id       = sanitize_title( $section_title );
					
					echo '<section aria-labelledby="' . $title_id . '" class="flexblock">';
					echo '<h2 id="' . $title_id . '">' . $section_title . '</h2>';
					echo $section_text;
					if ( $section_link ) {
						echo '<p><a href="' . $section_link['url'] . '" class="cta">' . $section_link['title'] . '</a></p>';
					}
					echo '</section>';
				
				endwhile;
				
				echo '</div>';
				echo '</div>';
			
			endif; 
			
		}
		
	}

    //========================================================================================================
    /**
     * Handles the front-end display. 
     *
     * @return void
     */
	public function ictu_gc_frontend_doelgroeppagina_content() {

		global $post;

		$all_or_some	= get_field('doelgroeppagina_showall_or_select', $post->ID );

		if ( 'showsome' === $all_or_some ) {

			$doelgroepen	= get_field('doelgroeppagina_kies_doelgroepen', $post->ID );

			if ( $doelgroepen ) {
		
				echo '<div class="flexbox">';
				
				$postcounter = 0;
				
				foreach( $doelgroepen as $post):
				
					setup_postdata($post);
				
					$postcounter++;
					$citaat         	= get_field('facts_citaten', $post->ID );
	
					echo $this->ictu_gc_doelgroep_card( $post, $citaat );
	
				endforeach; 
				
				echo '</div>';
				
				wp_reset_query();
				
			}
			else {
				echo '<p>' . _x( 'Geen doelgroepen geselecteerd voor deze pagina.', "error",  "ictu-gc-posttypes-inclusie" ) . '</p>';
			}
		}
		else {

			$args = array(
			    'post_type'             =>  ICTU_GC_CPT_DOELGROEP,
				'posts_per_page'        =>  -1,
				'order'                 =>  'ASC',
				'orderby'               =>  'post_title'
			    
			  );
			$sidebarposts = new WP_query( $args );
			
			if ($sidebarposts->have_posts()) {
		
				echo '<div class="flexbox">';
				
				$postcounter = 0;
				
				while ($sidebarposts->have_posts()) : $sidebarposts->the_post();
				
					$postcounter++;
					
	//				$doelgroep      	= get_field('doelgroep_avatar', $post->ID );
					$citaat         	= get_field('facts_citaten', $post->ID );
	
					echo $this->ictu_gc_doelgroep_card( $post, $citaat );
	
				endwhile;
				
				echo '</div>';
				
				wp_reset_query();
				
			}
				
			}

		
	}

    //========================================================================================================
    /**
     * Handles the front-end display. 
     *
     * @return void
     */
	public function ictu_gc_frontend_doelgroep_before_content() {
		
		global $post;
		
		$stap_inleiding 		= get_field( 'stap_inleiding', $post->ID );
		$stap_methodes  		= get_field( 'stap_methodes', $post->ID );
		$stap_methode_inleiding	= get_field( 'stap_methode_inleiding', $post->ID );

		echo '<div id="doelgroep-inleiding">';
		echo '<header class="entry-header wrap"><h1 class="entry-title" itemprop="headline">' . get_the_title( ) . '</h1></header>';
		
		if ( $stap_inleiding ) {
			echo sanitize_text_field( $stap_inleiding );
		}
		else {
			if ( get_the_excerpt( $post->ID ) ) {
				echo get_the_excerpt( $post->ID );
			}
		}

		$doelgroeppoppetje	= 'poppetje-1';
		if ( get_field('doelgroep_avatar', $post->ID) ) {
			$doelgroeppoppetje	= get_field('doelgroep_avatar', $post->ID);
		}

        $section_title  = _x( 'Citaten', 'titel op methode-pagina', 'ictu-gc-posttypes-inclusie' );
        $title_id       = sanitize_title( $section_title . '-' . $post->ID );
        $facts_citaten  = get_field( 'facts_citaten', $post->ID );

        echo '<div class="" id="doelgroep-inleiding-citaten">';

        // loop through the rows of data
        foreach( $facts_citaten as $post ):

          setup_postdata( $post );

          $theid = $post->ID;
      
          $section_title  = get_the_title( $theid );
          $title_id       = sanitize_title( $section_title );
          $citaat_post    = get_post( $theid );
          $content        = '&rdquo;' . $citaat_post->post_content . '&rdquo;';
          $section_text   = apply_filters('the_content', $content);   
          $citaat_auteur  = sanitize_text_field( get_field( 'citaat_auteur', $theid ) );

          echo '<div class="tegeltje">' . $section_text . '<p class="author"><strong>' . $citaat_auteur . '</strong></p></div>';
      
        endforeach;

		echo '<div class="feat-image ' . $doelgroeppoppetje . '">&nbsp;</div>';

        echo '</div>';
        
		echo '</div>'; // #doelgroep-inleiding
        
        wp_reset_postdata();            

        

	}

    //========================================================================================================
    /**
     * Handles the front-end display. 
     *
     * @return void
     */
	public function ictu_gc_frontend_stap_before_content() {
		
		global $post;
		
		$homepageID = get_option('page_on_front');
		
		if ( ! $homepageID ) {
			return;
		}

		if ( function_exists( 'get_field' ) ) {
			
			$home_stappen     					= get_field( 'home_template_stappen', $homepageID );
		
			if( $home_stappen ): 
				
				$section_title  = _x( 'Stappen', 'titel op home-pagina', 'ictu-gc-posttypes-inclusie' );
				$title_id       = sanitize_title( $section_title . '-' . $post->ID );
				$stepcounter    = 0;
				
				echo '<div aria-labelledby="' . $title_id . '" class="flexbox wrap" id="stappen-chart">';
				echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';
		
				foreach( $home_stappen as $stap ): 
				
					$stepcounter++;
					$current 	= '';
					$class 		= 'deel';
					$titel 		= get_the_title( $stap->ID );
					$tagend		= '</a>';
					$tagstart	= '<a href="' . get_permalink( $stap->ID ) . '" class="label">';
					
					if ( $stap->ID === $post->ID ) {
						$current 	= ' current';
						$tagstart 	= '<span class="label">';
						$tagend		= '</span>';
					}
		
					if ( get_field( 'stap_verkorte_titel', $stap->ID ) ) {
						$titel = get_field( 'stap_verkorte_titel', $stap->ID );
					}
		
					if ( get_field( 'stap_icon', $stap->ID ) ) {
						$class = get_field( 'stap_icon', $stap->ID );
					}
		
					$title_id   = sanitize_title( get_the_title( $stap->ID ) . '-' . $stepcounter );
					$steptitle	= sprintf( _x( '%s. %s', 'Label stappen', 'ictu-gc-posttypes-inclusie' ), $stepcounter, $titel ); 
		
					echo '<div id="step_' . $stepcounter . '" class="step flexblock ' . $class . $current . '">';
					echo '<p id="' . $title_id . '" class="icon ' . $class . $current . '">' . $tagstart . $titel . $tagend . '</p>';
					echo '</div>';
				
				endforeach;
		
				echo '</div>';
			
			endif; 

			$stap_inleiding 		= get_field( 'stap_inleiding', $post->ID );
			$stap_methodes  		= get_field( 'stap_methodes', $post->ID );
			$stap_methode_inleiding	= get_field( 'stap_methode_inleiding', $post->ID );
			$stap_methodes_titel	= get_field( 'stap_methodes_titel', $post->ID );
			
			if ( ! $stap_methodes_titel ) {
				$stap_methodes_titel = _x( 'Methoden', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
			}
			

			echo '<div id="step-inleiding">';
			echo '<header class="entry-header wrap"><h1 class="entry-title" itemprop="headline">' . get_the_title( ) . '</h1></header>';
			
			if ( $section_title ) {
				echo $stap_inleiding;
				$section_title = $stap_methodes_titel;
			}

			echo '</div>'; // #step-inleiding

			
			if( $stap_methodes ):
				
				$title_id       = sanitize_title( $section_title . '-' . $post->ID );
				
				echo '<section aria-labelledby="' . $title_id . '" id="step-methoden">';
				echo '<div class="inleiding">';
				echo '<h2 id="' . $title_id . '">' . $section_title . '</h2>';
				
				if ( $stap_methode_inleiding ) {
					echo $stap_methode_inleiding;
				}
				else {
					echo sprintf( '<p>%s</p>', _x( 'Dit is een selectie van methoden, technieken en instrumenten die je in kunt zetten bij het uitvoeren van deze stap. Soms gaat het om standaardmethoden die worden ingezet voor het uitvoeren van een ontwerptraject waarbij de gebruiker centraal staat. Andere methoden richten zich specifiek op inclusie. ', 'Stap: intro bij methoden', 'ictu-gc-posttypes-inclusie' ) ); 
				}
				echo '</div>'; // .inleiding

				
				echo '<div class="flexbox cards">';
				
				// loop through the rows of data
				foreach( $stap_methodes as $post ):
				
					setup_postdata( $post );
					
					$theid = $post->ID;
					
					$section_title  = get_the_title( $theid );
					$section_text   = get_the_excerpt( $theid );
					$section_link   = get_sub_field( 'home_template_teaser_link' );
					$title_id       = sanitize_title( $section_title );
					
					echo '<div class="flexblock no-image">';
					echo '<h3 id="' . $title_id . '"><a href="' . get_permalink( $theid ) . '">' . $section_title . '</a></h3>';
					echo $section_text;
					echo '</div>';
					
				endforeach;
				
				wp_reset_postdata();            
				
				echo '</div>';
				echo '</section>';
			
			endif; 
			
		
		}
		
	
	}

    //========================================================================================================
    /**
     * Handles the front-end display. 
     *
     * @return void
     */
	public function ictu_gc_frontend_home_before_content() {
		
		global $post;
		
		if ( function_exists( 'get_field' ) ) {
			
			$home_inleiding   					= get_field( 'home_template_inleiding', $post->ID );
			$home_stappen     					= get_field( 'home_template_stappen', $post->ID );
			$home_template_poster				= get_field( 'home_template_poster', $post->ID );
			$home_template_poster_linktekst		= get_field( 'home_template_poster_linktekst', $post->ID );

			echo '<div id="home-inleiding">';
			echo '<header class="entry-header wrap"><h1 class="entry-title" itemprop="headline">' . get_the_title( ) . '</h1></header>';

			
			if ( $home_inleiding ) {
				echo $home_inleiding;
			}
			
			if ( $home_template_poster && $home_template_poster_linktekst ) {
				echo '<a href="' . $home_template_poster['url'] . '" class="download poster">' . $home_template_poster_linktekst . '</a>';
			}
			
			
			if( $home_stappen ): 
				
				$section_title  = _x( 'Stappen', 'titel op home-pagina', 'ictu-gc-posttypes-inclusie' );
				$title_id       = sanitize_title( $section_title . '-' . $post->ID );
				$stepcounter    = 0;
				
				echo '<div aria-labelledby="' . $title_id . '" id="home-chart">';
				echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';
	
				foreach( $home_stappen as $stap ): 
				
					$stepcounter++;
					
					if ( get_field( 'stap_verkorte_titel', $stap->ID ) ) {
						$titel = get_field( 'stap_verkorte_titel', $stap->ID );
					}
					else {
						$titel = get_the_title( $stap->ID );
					}

					$class = 'deel';
					if ( get_field( 'stap_icon', $stap->ID ) ) {
						$class = get_field( 'stap_icon', $stap->ID );
					}

					
					if ( get_field( 'stap_inleiding', $stap->ID ) ) {
						$inleiding = get_field( 'stap_inleiding', $stap->ID );
					}
					else {
						$stap_post      = get_post( $stap->ID );
						$content        = $stap_post->post_content;
						$inleiding      = apply_filters('the_content', $content);   
					}
					
					$xtraclass 	= ' hidden';
					$title_id   = sanitize_title( get_the_title( $stap->ID ) . '-' . $stepcounter );
					$steptitle	= sprintf( _x( '%s. %s', 'Label stappen', 'ictu-gc-posttypes-inclusie' ), $stepcounter, $titel ); 
					$readmore	= sprintf( _x( '%s <span class="visuallyhidden">over %s</span>', 'home lees meer', 'ictu-gc-posttypes-inclusie' ), _x( 'Lees meer', 'home lees meer', 'ictu-gc-posttypes-inclusie' ), get_the_title( $stap->ID ) ); 

					
					echo '<section id="step_' . $stepcounter . '" class="step ' . $class . '" aria-labelledby="' . $title_id . '">';
					echo '<button class="js-openclosebutton">' . $steptitle . '<span>&nbsp;</span></button>';
					echo '<div class="description' . $xtraclass . ' js-descriptionbox">';
					echo '<h3 id="' . $title_id . '">' . get_the_title( $stap->ID ) . '</h3>';
					echo $inleiding;
					echo '<p class="read-more"><a href="' . get_permalink( $stap->ID ) . '" class="cta">' . $readmore . '</a></p>';
					echo '</div>';
					echo '</section>';
				
				endforeach;
	
				echo '</div>';
			
			endif; 
			
			echo '</div>'; // #home-inleiding
			
			
			if( have_rows('home_template_doelgroepen') ):
			
				$section_title	= _x( 'Doelgroepen', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
				$title_id       = sanitize_title( $section_title . '-' . $post->ID );
				$posttype       = '';

				echo '<div class="flexbox" id="home_template_doelgroepen">';
				
				// loop through the rows of data
				while ( have_rows('home_template_doelgroepen') ) : the_row();          
				
					$doelgroep      	= get_sub_field('home_template_doelgroepen_doelgroep');
					$citaat         	= get_sub_field('home_template_doelgroepen_citaat');
					
					echo $this->ictu_gc_doelgroep_card( $doelgroep, $citaat );
				
				endwhile;
				
				echo '</div>';

				$label	= _x( 'Alle doelgroepen', 'Linktekst doelgroepoverzicht', 'ictu-gc-posttypes-inclusie' ); // $obj->name;
				echo '<p id="doelgroep-archive-p"><a href="' . get_post_type_archive_link( ICTU_GC_CPT_DOELGROEP ) . '" class="cta ' . $posttype . '">' . $label . '</a></p>';

			endif; 

		}
		
	}    
  
    //========================================================================================================

    /**
     * Register frontend styles
     */
	public function ictu_gc_register_frontend_style_script( ) {
	
		global $post;
	
		$infooter = true;
		if ( WP_DEBUG ) {
			wp_enqueue_script( 'functions-toggle', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/toggle.js', '', ICTU_GC_VERSION, $infooter );
		}
		else {
			wp_enqueue_script( 'functions-toggle', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/min/toggle-min.js', '', ICTU_GC_VERSION, $infooter );
		}
		
		wp_enqueue_style( ICTU_GC_ARCHIVE_CSS, trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/frontend.css', array(), ICTU_GC_VERSION, 'all' );
		
		$header_css     = '';
		$acfid          = get_the_id();
		$page_template  = get_post_meta( $acfid, '_wp_page_template', true );
		
		if ( !is_admin() && ( $this->template_home == $page_template ) ) {
			
			if( have_rows('home_template_doelgroepen') ):

				// loop through the rows of data
				while ( have_rows('home_template_doelgroepen') ) : the_row();          
					
					$doelgroep      = get_sub_field('home_template_doelgroepen_doelgroep');
					$posttype       = get_post_type( $doelgroep->ID );
					$title_id       = sanitize_title( $posttype . '-' . $doelgroep->ID );
					$image          = wp_get_attachment_image_src( get_post_thumbnail_id( $doelgroep->ID ), 'large' );
					
					if ( $image[0] ) {
						$header_css .= "#" . $title_id ." .featured-image { ";
						$header_css .= "background-image: url('" . $image[0] . "'); ";
						$header_css .= "} ";
					}
					else {
						//              $header_css .= "background: yellow;";
					}

				endwhile;
				
			endif; 
			
		}

		$gerelateerdecontent = get_field( 'gerelateerde_content_toevoegen', $acfid );
		
		if ( $gerelateerdecontent == 'ja' ) {

			$related_items  = get_field('content_block_items');

			// loop through the rows of data
			foreach( $related_items as $post ):
			
				setup_postdata( $post );

				$image          = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
				
				if ( $image[0] ) {
					$header_css .= "#related_" . $post->ID . " .featured-image:after { ";
					$header_css .= "background-image: url('" . $image[0] . "'); ";
					$header_css .= "background-size: cover; ";
					$header_css .= "} ";
				}

				
			endforeach;
			
			wp_reset_postdata();            


		}

		if ( $post ) {
		
			$handigelinks = get_field( 'handige_links_toevoegen', $post->ID );
			
			if ( $handigelinks == 'ja' ) {
				
				$section_title  = get_field( 'links_block_title', $post->ID );
				$title_id       = sanitize_title( $section_title . '-title' );
				
				echo '<section aria-labelledby="' . $title_id . '">';
				echo '<h2 id="' . $title_id . '">' . $section_title . '</h2>';
				
				$links_block_items = get_field('links_block_items');
				
				if( $links_block_items ): 
				
					echo '<ul>';
					
					while( have_rows('links_block_items') ): the_row();
					
						$links_block_item_url         = get_sub_field('links_block_item_url');
						$links_block_item_linktext    = get_sub_field('links_block_item_linktext');
						$links_block_item_description = get_sub_field('links_block_item_description');
						
						echo '<li> <a href="' . esc_url( $links_block_item_url ) . '">' . sanitize_text_field( $links_block_item_linktext ) . '</a>';
						
						if ( $links_block_item_description ) {
							echo '<br>' . sanitize_text_field( $links_block_item_description );
						}
						
						echo '</li>';
						
					endwhile;
					
					echo '</ul>';
					
				endif; 
				
				echo '</section>';
				
			}
		}			

		if ( $header_css ) {
			wp_add_inline_style( ICTU_GC_ARCHIVE_CSS, $header_css );
		}

		
    }

    //========================================================================================================

    /**
    * Modify page content if using a specific page template.
    */
	public function ictu_gc_frontend_use_page_template() {
		
		global $post;
		
		$page_template  = get_post_meta( get_the_ID(), '_wp_page_template', true );
		
		if ( $this->template_home == $page_template ) {
			
			remove_filter( 'genesis_post_title_output', 'gc_wbvb_sharebuttons_for_page_top', 15 );
			
			//* Remove standard header
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
			remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

			//* Remove the post content (requires HTML5 theme support)
			remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
			
			// append content
			add_action( 'genesis_entry_content',  array( $this, 'ictu_gc_frontend_home_before_content' ), 8 ); 				
			add_action( 'genesis_after_content',  array( $this, 'ictu_gc_frontend_home_after_content' ), 12 ); 				
			
			
		}
		elseif ( $this->template_doelgroeppagina == $page_template ) {
			// template voor doelgroeppagina.


			add_action( 'genesis_loop', array( $this, 'ictu_gc_add_posttype_title' ), 9 ); 	

//		    remove_action( 'genesis_loop', 'genesis_do_loop' );
//		    remove_action( 'genesis_loop', 'gc_wbvb_archive_loop' );


//			add_action( 'genesis_loop',  array( $this, 'ictu_gc_frontend_archive_doelgroep_loop' ), 10 ); 		
//			add_action( 'genesis_loop',  array( $this, 'ictu_gc_frontend_archive_doelgroep_loop' ), 11 ); 			
				
			add_action( 'genesis_loop',  array( $this, 'ictu_gc_frontend_doelgroeppagina_content' ), 12 ); 				

			
		}
		elseif ( ICTU_GC_CPT_STAP == get_post_type( ) )  {

			//* Remove standard header
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
			remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

			add_action( 'genesis_before_loop',  array( $this, 'ictu_gc_frontend_stap_before_content' ), 8 ); 				

			add_action( 'genesis_entry_header',  array( $this, 'ictu_gc_frontend_stap_append_title' ), 10 ); 				

			add_action( 'genesis_entry_content',  array( $this, 'ictu_gc_frontend_get_related_content' ), 12 ); 				
	
		}
/*
			elseif ( is_archive( ICTU_GC_CPT_DOELGROEP ) ) {

			add_action( 'genesis_loop', array( $this, 'ictu_gc_add_posttype_title' ), 9 ); 	

		    remove_action( 'genesis_loop', 'genesis_do_loop' );
		    remove_action( 'genesis_loop', 'gc_wbvb_archive_loop' );

			add_action( 'genesis_loop',  array( $this, 'ictu_gc_frontend_archive_doelgroep_loop' ), 10 ); 				
			
			
		}
*/		
		elseif ( is_singular( ICTU_GC_CPT_DOELGROEP ) ) {

			//* Remove standard header
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
			remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
			remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

			add_action( 'genesis_before_entry',  array( $this, 'ictu_gc_frontend_doelgroep_before_content' ), 8 ); 				

			add_action( 'genesis_entry_header',  array( $this, 'ictu_gc_frontend_doelgroep_append_cijfers' ), 10 ); 
			
							
			add_action( 'genesis_entry_content',  array( $this, 'ictu_gc_frontend_doelgroep_append_title' ), 9 ); 				

			add_action( 'genesis_entry_content',  array( $this, 'ictu_gc_frontend_get_related_content' ), 20 ); 				
	
		}


		//=================================================
		
		add_filter( 'genesis_post_info',   array( $this, 'filter_postinfo' ), 10, 2 );
		
	}

	
    /** ----------------------------------------------------------------------------------------------------
     * Add an archive title
     */
    public function ictu_gc_add_posttype_title() {
    
	    if ( ! is_post_type_archive( ICTU_GC_CPT_DOELGROEP ) )
	        return;

		$headline	= '';
		$intro_text	= '';
		$class 		= 'taxonomy-description';

		if ( is_post_type_archive( ICTU_GC_CPT_DOELGROEP ) ) {
			$class 		= 'posttype-description';
			$headline = sprintf( '<h1 class="archive-title">%s</h1>', _x( "Doelgroepen", "Post type name", 'ictu-gc-posttypes-inclusie' ) );
	    }
	
	    if ( $headline || $intro_text ) {
	        printf( '<div class="' . $class . '">%s</div>', $headline . $intro_text );
	    }
	    else {
	        echo '';
	    }
  
	}
    


    /** ----------------------------------------------------------------------------------------------------
     * Post info: do not write any post info
     */
    public function filter_postinfo() {
    
    	return '';
  
	}
    

    /** ----------------------------------------------------------------------------------------------------
     * A new version of the_loop for doelgroepen
     */
	public function ictu_gc_frontend_archive_doelgroep_loop() {

		// code for a completely custom loop
		global $post;

echo '<h1> ictu_gc_frontend_archive_doelgroep_loop </h1>';

		$args = array(
		    'post_type'             =>  ICTU_GC_CPT_DOELGROEP,
			'posts_per_page'        =>  -1,
			'order'                 =>  'ASC',
			'orderby'               =>  'post_title'
		    
		  );
		$sidebarposts = new WP_query( $args );
		
		if ($sidebarposts->have_posts()) {
	
			echo '<div class="flexbox">';
			
			$postcounter = 0;
			
			while ($sidebarposts->have_posts()) : $sidebarposts->the_post();
			
				$postcounter++;
				
//				$doelgroep      	= get_field('doelgroep_avatar', $post->ID );
				$citaat         	= get_field('facts_citaten', $post->ID );

				echo $this->ictu_gc_doelgroep_card( $post, $citaat );

			endwhile;
			
			echo '</div>';
			
			wp_reset_query();
			
		}
	}
		
    /** ----------------------------------------------------------------------------------------------------
     * filter the breadcrumb
     */
    public function prepend_content( $thecontent ) {

      global $post;

      if ( is_singular( ICTU_GC_CPT_STAP ) ) {


      }
      elseif ( is_singular( ICTU_GC_CPT_DOELGROEP ) ) {
	      
      }

    }

    /** ----------------------------------------------------------------------------------------------------
     * Add rewrite rules
     */
    public function ictu_gc_add_rewrite_rules() {
    
    	return '';
  
	}

    /** ----------------------------------------------------------------------------------------------------
     * filter the breadcrumb
     */
    public function append_content( $thecontent ) {

      global $post;
      
      if ( is_singular( ICTU_GC_CPT_CITAAT ) ) {

        if ( function_exists( 'get_field' ) ) {

          $auteur = get_field( 'citaat_auteur', $post->ID );
          
          if ( $auteur ) {
            echo '<p><cite>' . $auteur . '</cite></p>';
          }
          
        }
        
      }
      elseif ( is_singular( ICTU_GC_CPT_STAP ) ) {
      }
      elseif ( is_singular( ICTU_GC_CPT_DOELGROEP ) ) {


        $stap_methodes  = get_field( 'doelgroep_vaardigheden', $post->ID );

        if( $stap_methodes ):

          $section_title = _x( 'Vaardigheden', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
          $title_id       = sanitize_title( $section_title . '-' . $post->ID );
        
          echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';
        
          // loop through the rows of data
          foreach( $stap_methodes as $post ):

            setup_postdata( $post );

            $theid = $post->ID;
        
            $section_title  = get_the_title( $theid );
            $section_text   = get_the_excerpt( $theid );

            $content        = $post->post_content;
            $section_text   = apply_filters('the_content', $content);   

            $section_link   = get_sub_field( 'home_template_teaser_link' );
            $title_id       = sanitize_title( $section_title );
        
            echo '<section aria-labelledby="' . $title_id . '" class="vaardigheid">';
            echo '<h3 id="' . $title_id . '">' . $section_title . '</h3>';
            echo $section_text;

            $vaardigheid_afraders   = get_field( 'vaardigheid_afraders', $theid );
            $vaardigheid_aanraders  = get_field( 'vaardigheid_aanraders', $theid );

            if( $vaardigheid_afraders || $vaardigheid_aanraders ):

              echo '<div class="flexbox dosdonts">';

              if ( $vaardigheid_aanraders ) {
                $section_title = _x( 'Aanraders', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
                echo '<div class="aanrader flexblock">';
                echo '<h4 id="' . $title_id . '">' . $section_title . '</h4>';
                echo '<ul>';
                foreach( $vaardigheid_aanraders as $dingges ):
                  echo '<li>' . get_the_title( $dingges->ID ) . '</li>';
                endforeach;
                wp_reset_postdata();          
                echo '</ul>';
                echo '</div>';
              }
              if ( $vaardigheid_afraders ) {
                $section_title = _x( 'Afraders', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
                echo '<div class="afrader flexblock">';
                echo '<h4 id="' . $title_id . '">' . $section_title . '</h4>';
                echo '<ul>';
                foreach( $vaardigheid_afraders as $dingges ):
                  echo '<li>' . get_the_title( $dingges->ID ) . '</li>';
                endforeach;
                wp_reset_postdata();          
                echo '</ul>';
                echo '</div>';
              }

              echo '</div>';
            
            endif;



            
            echo '</section>';
        
          endforeach;
          
          wp_reset_postdata();          
          
        endif;

      }
      elseif ( is_singular( ICTU_GC_CPT_VAARDIGHEDEN ) ) {
      }
      elseif ( is_singular( ICTU_GC_CPT_METHODE ) ) {

        $classificering  = '';        
        $theid           = get_the_id();

        $classificering  = $this->get_classifications( $theid, ICTU_GC_CT_TIJD );
        $classificering .= $this->get_classifications( $theid, ICTU_GC_CT_MANKRACHT );
        $classificering .= $this->get_classifications( $theid, ICTU_GC_CT_KOSTEN );
        $classificering .= $this->get_classifications( $theid, ICTU_GC_CT_EXPERTISE );
        $classificering .= $this->get_classifications( $theid, ICTU_GC_CT_DEELNEMERS );

        if ( $classificering ) {
        
          $section_title  = _x( 'Classificering', 'titel op methode-pagina', 'ictu-gc-posttypes-inclusie' );
          $title_id       = sanitize_title( $section_title . '-title' );
          $related_items  = get_field('content_block_items');
        
          echo '<section aria-labelledby="' . $title_id . '" class="related-content">';
          echo '<h2 id="' . $title_id . '">' . $section_title . '</h2>';
          echo '<ul class="methode-classifications">';
          echo $classificering;
          echo '</ul>';
          echo '</section>';
          
        }
        
      }
      
      if ( is_singular( ICTU_GC_CPT_STAP ) || is_singular( 'page' ) ) {
	  	// 
      }

      return $thecontent;

    }

    
    /** ----------------------------------------------------------------------------------------------------
     * Prepends a title before the content
     */
    public function ictu_gc_frontend_get_related_content() {
	    
	    global $post;

		if ( function_exists( 'get_field' ) ) {
			
			$gerelateerdecontent = get_field( 'gerelateerde_content_toevoegen', get_the_id() );
			
			if ( $gerelateerdecontent == 'ja' ) {
				
				$section_title  = get_field( 'content_block_title', $post->ID );
				$title_id       = sanitize_title( $section_title . '-title' );
				$related_items  = get_field('content_block_items');
				
				echo '<section aria-labelledby="' . $title_id . '" class="related-content">';
				echo '<h2 id="' . $title_id . '">' . $section_title . '</h2>';
				echo '<div class="flexbox cards">';
				
				// loop through the rows of data
				foreach( $related_items as $post ):
				
					setup_postdata( $post );
					
					$theid = $post->ID;
					
					$section_title  	= get_the_title( $theid );
					$section_text   	= get_the_excerpt( $theid );
					$section_link   	= get_sub_field( 'home_template_teaser_link' );
					$title_id       	= sanitize_title( $section_title );
					$block_id       	= sanitize_title( 'related_' . $theid );
					$imageplaceholder	= '';
					$image          	= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
					
					if ( $image[0] ) {
						$class = ' with-image';
						$imageplaceholder = '<div class="featured-image">&nbsp;</div>';
					}
					else {
						$class = ' no-image';
					}
					
					
					echo '<div class="flexblock' . $class . '" id="' . $block_id . '">' . $imageplaceholder;
					echo '<h3 id="' . $title_id . '"><a href="' . get_permalink( $theid ) . '">' . $section_title . '</a></h3>';
					echo "<p>"  . $section_text . "</p>";
					echo '</div>';
					
				endforeach;
				
				wp_reset_postdata();            
				
				echo '</div>';
				echo '</section>';
				
			}
			
			$handigelinks = get_field( 'handige_links_toevoegen', $post->ID );
			
			if ( $handigelinks == 'ja' ) {
				
				$section_title  = get_field( 'links_block_title', $post->ID );
				$title_id       = sanitize_title( $section_title . '-title' );
				
				echo '<section aria-labelledby="' . $title_id . '" class="related-links">';
				echo '<h2 id="' . $title_id . '">' . $section_title . '</h2>';
				
				$links_block_items = get_field('links_block_items');
				
				if( $links_block_items ): 
				
					echo '<ul>';
					
					while( have_rows('links_block_items') ): the_row();
					
						$links_block_item_url         = get_sub_field('links_block_item_url');
						$links_block_item_linktext    = get_sub_field('links_block_item_linktext');
						$links_block_item_description = get_sub_field('links_block_item_description');
						
						echo '<li> <span><a href="' . esc_url( $links_block_item_url ) . '">' . sanitize_text_field( $links_block_item_linktext ) . '</a>';
						
						if ( $links_block_item_description ) {
							echo '<br>' . sanitize_text_field( $links_block_item_description );
						}
						
						echo '</span></li>';
						
					endwhile;
					
					echo '</ul>';
					
				endif; 
				
				echo '</section>';
				
			}
		}
	}

    /** ----------------------------------------------------------------------------------------------------
     * Prepends a title before the content
     */
    public function ictu_gc_frontend_doelgroep_append_title() {
	    
		global $post;
		
		$title	= sprintf( _x( 'Over %s', 'Label stappen', 'ictu-gc-posttypes-inclusie' ), get_the_title( )  ); 
	    echo '<h2>' . $title. '</h2>';
	    
	}

    /** ----------------------------------------------------------------------------------------------------
     * Prepends a title before the content
     */
    public function ictu_gc_frontend_doelgroep_append_cijfers() {
	
		global $post;

		$section_title      = _x( 'Cijfers', 'titel op doelgroep-pagina', 'ictu-gc-posttypes-inclusie' );
		$title_id           = sanitize_title( $section_title . '-title' );
		$facts_title        = get_field( 'facts_title' );
		$facts_description  = get_field( 'facts_description' );
		$facts_source_url   = get_field( 'facts_source_url' );
		$facts_source_label = get_field( 'facts_source_label' );
		
		if ( $facts_title ) {
		
			echo '<section aria-labelledby="' . $title_id . '" class="facts-figures">';
			echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';
			echo '<span class="hugely">' . sanitize_text_field( $facts_title ) . '</span><span class="source">';
			echo sanitize_text_field( $facts_description );
			if ( $facts_source_url && $facts_source_label ) {
				echo '<cite>' . _x( 'Bron:', "Cijfers",  "ictu-gc-posttypes-inclusie" ) . ' ';
				echo '<a href="' . esc_url( $facts_source_url ) . '">' . sanitize_text_field( $facts_source_label ) . '</a></cite>';
			}
			echo '</span></section>';
			
		}
		

    }


    /** ----------------------------------------------------------------------------------------------------
     * Prepends a title before the content
     */
    public function ictu_gc_frontend_stap_append_title() {
	
		global $post;

		$section_title = _x( 'Tips', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
		$title_id       = sanitize_title( $section_title . '-' . $post->ID );
		
		// force a title, but do not make it seeable
		echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';

    }

    /** ----------------------------------------------------------------------------------------------------
     * Prepends a title before the content
     */
    public function ictu_gc_doelgroep_card( $doelgroep, $citaat ) {

		if ( is_object( $citaat ) && 'WP_Post' == get_class( $citaat ) ) {
			$citaat_post    	= get_post( $citaat->ID );
			$citaat_auteur  	= sanitize_text_field( get_field( 'citaat_auteur', $citaat->ID ) );
			$content        	= '&ldquo;' . $citaat_post->post_content . '&rdquo;';
		}
		else {
			if ( $citaat[0]->post_content ) {
				$content        = '&ldquo;' . $citaat[0]->post_content . '&rdquo;';
				$citaat_auteur  = sanitize_text_field( get_field( 'citaat_auteur', $citaat[0]->ID ) );
			}
			else {
				return '';
			}
		}

		$content        	= apply_filters('the_content', $content);   
		
		if ( is_object( $doelgroep ) ) { 
			$doelgroep_ID		= $doelgroep->ID;
		}
		elseif( $doelgroep > 0 ) {
			$doelgroep_ID		= $doelgroep;
		}
		else {
			return;
		}

		$posttype       	= get_post_type( $doelgroep_ID );
		$title_id       	= sanitize_title( 'title-' . $posttype . '-' . $doelgroep_ID );
		$section_id     	= sanitize_title( 'section-' . $posttype . '-' . $doelgroep_ID );
		$doelgroeppoppetje	= 'poppetje-1';

		if ( get_field('doelgroep_avatar', $doelgroep_ID ) ) {
			$doelgroeppoppetje	= get_field('doelgroep_avatar', $doelgroep_ID );
		}

		$return 	= '<section aria-labelledby="' . $title_id . '" class="flexblock" id="' . $section_id . '">';
		$return    .= '<div  class="doelgroepcard ' . $doelgroeppoppetje . '"><h2 id="' . $title_id . '"><a href="' . get_permalink( $doelgroep_ID ) . '"><span>' . _x( 'Ontwerpen voor', 'Home section doelgroep', 'ictu-gc-posttypes-inclusie' ) . ' </span><span>' . get_the_title( $doelgroep_ID ) . '</span></a></h2>';
		$return    .= '<div class="tegeltje">' . $content . '<p><strong>' . $citaat_auteur . '</strong></p></div>';
		$return    .= '</div>';
		$return    .= '</section>';
			
		return $return;

    }

    /** ----------------------------------------------------------------------------------------------------
     * Do actually register the post types we need
     */
    public function ictu_gc_register_post_type() {
      

      // ---------------------------------------------------------------------------------------------------
      // custom post type voor 'stappen'
    	$labels = array(
    		"name"                  => _x( 'Stappen', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"singular_name"         => _x( 'Stap', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"menu_name"             => _x( 'Stappen', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"all_items"             => _x( 'Alle stappen', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"add_new"               => _x( 'Nieuwe stap toevoegen', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"add_new_item"          => _x( 'Nieuwe stap toevoegen', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"edit_item"             => _x( 'Stap bewerken', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"new_item"              => _x( 'Nieuwe stap', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"view_item"             => _x( 'Stap bekijken', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"search_items"          => _x( 'Zoek een stap', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"not_found"             => _x( 'Niets gevonden', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"not_found_in_trash"    => _x( 'Niets gevonden', 'stap type', 'ictu-gc-posttypes-inclusie' ),
    		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
    		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
    		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
    		);
    
    	$args = array(
    		"label"                 => _x( 'Stappen', 'Stappen label', 'ictu-gc-posttypes-inclusie' ),
    		"labels"              => $labels,
      "menu_icon"           => "dashicons-editor-ol",      		
    		"description"         => "",
    		"public"              => true,
    		"publicly_queryable"  => true,
    		"show_ui"             => true,
    		"show_in_rest"        => false,
    		"rest_base"           => "",
    		"has_archive"         => true,
    		"show_in_menu"        => true,
    		"exclude_from_search" => false,
    		"capability_type"     => "post",
    		"map_meta_cap"        => true,
    		"hierarchical"        => false,
    		"rewrite"             => array( "slug" => ICTU_GC_CPT_STAP, "with_front" => true ),
    		"query_var"           => true,
    		"supports"            => array( "title", "editor", "thumbnail", "excerpt" ),		
			);
    	register_post_type( ICTU_GC_CPT_STAP, $args );

      // ---------------------------------------------------------------------------------------------------
      // custom post type voor 'doelgroep'

    	$labels = array(
    		"name"                  => _x( 'Doelgroepen', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"singular_name"         => _x( 'Doelgroep', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"menu_name"             => _x( 'Doelgroepen', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"all_items"             => _x( 'Alle doelgroepen', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"add_new"               => _x( 'Nieuwe doelgroep toevoegen', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"add_new_item"          => _x( 'Nieuwe doelgroep toevoegen', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"edit_item"             => _x( 'Doelgroep bewerken', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"new_item"              => _x( 'Nieuwe doelgroep', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"view_item"             => _x( 'Doelgroep bekijken', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"search_items"          => _x( 'Zoek een doelgroep', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"not_found"             => _x( 'Niets gevonden', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"not_found_in_trash"    => _x( 'Niets gevonden', 'doelgroep type', 'ictu-gc-posttypes-inclusie' ),
    		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
    		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
    		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
    		);
    
    	$args = array(
    		"label"                 => _x( 'Doelgroepen', 'Stappen label', 'ictu-gc-posttypes-inclusie' ),
    		"labels"              => $labels,
    		"description"         => "",
    		"public"              => true,
    		"publicly_queryable"  => true,
    		"show_ui"             => true,
    		"show_in_rest"        => false,
    		"rest_base"           => "",
    		"has_archive"         => false,
    		"show_in_menu"        => true,
    		"exclude_from_search" => false,
    		"capability_type"     => "post",
    		"map_meta_cap"        => true,
    		"hierarchical"        => false,
    		"rewrite"             => array( "slug" => ICTU_GC_CPT_DOELGROEP, "with_front" => true ),
    		"query_var"           => true,
    		"supports"            => array( "title", "editor", "thumbnail", "excerpt" ),		
			);
    	register_post_type( ICTU_GC_CPT_DOELGROEP, $args );
    
      // ---------------------------------------------------------------------------------------------------
      // custom post type voor 'doelgroep'

    	$labels = array(
    		"name"                  => _x( 'Citaten', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"singular_name"         => _x( 'Citaat', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"menu_name"             => _x( 'Citaten', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"all_items"             => _x( 'Alle citaten', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"add_new"               => _x( 'Nieuw citaat toevoegen', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"add_new_item"          => _x( 'Nieuw citaat toevoegen', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"edit_item"             => _x( 'Citaat bewerken', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"new_item"              => _x( 'Nieuw citaat', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"view_item"             => _x( 'Citaat bekijken', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"search_items"          => _x( 'Zoek een Citaat', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"not_found"             => _x( 'Niets gevonden', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"not_found_in_trash"    => _x( 'Niets gevonden', 'citaat type', 'ictu-gc-posttypes-inclusie' ),
    		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
    		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
    		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
    		);
    
    	$args = array(
    		"label"                 => _x( 'Citaten', 'Stappen label', 'ictu-gc-posttypes-inclusie' ),
    		"labels"              => $labels,
      "menu_icon"           => "dashicons-format-quote",      		
    		"description"         => "",
    		"public"              => true,
    		"publicly_queryable"  => true,
    		"show_ui"             => true,
    		"show_in_rest"        => false,
    		"rest_base"           => "",
    		"has_archive"         => false,
    		"show_in_menu"        => true,
    		"exclude_from_search" => false,
    		"capability_type"     => "post",
    		"map_meta_cap"        => true,
    		"hierarchical"        => false,
    		"rewrite"             => array( "slug" => ICTU_GC_CPT_CITAAT, "with_front" => true ),
    		"query_var"           => true,
    		"supports"            => array( "title", "editor", "thumbnail", "excerpt" ),		
			);
    	register_post_type( ICTU_GC_CPT_CITAAT, $args );
    
      // ---------------------------------------------------------------------------------------------------

      // custom post type voor 'vaardigheid'

    	$labels = array(
    		"name"                  => _x( "Vaardigheden", 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"singular_name"         => _x( 'Vaardigheid', 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"menu_name"             => _x( "Vaardigheden", 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"all_items"             => _x( "Alle vaardigheden", 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"add_new"               => _x( 'Nieuwe vaardigheid toevoegen', 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"add_new_item"          => _x( 'Nieuwe vaardigheid toevoegen', 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"edit_item"             => _x( 'Vaardigheid bewerken', 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"new_item"              => _x( 'Nieuwe vaardigheid', 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"view_item"             => _x( 'Vaardigheid bekijken', 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"search_items"          => _x( 'Zoek een vaardigheid', 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"not_found"             => _x( 'Niets gevonden', 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"not_found_in_trash"    => _x( 'Niets gevonden', 'vaardigheid type', 'ictu-gc-posttypes-inclusie' ),
    		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
    		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
    		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
    		);
    
    	$args = array(
    		"label"                 => _x( "Vaardigheden", "Label Vaardigheden", 'ictu-gc-posttypes-inclusie' ),
    		"labels"              => $labels,
      "menu_icon"           => "dashicons-image-filter",      		
    		"description"         => "",
    		"public"              => true,
    		"publicly_queryable"  => true,
    		"show_ui"             => true,
    		"show_in_rest"        => false,
    		"rest_base"           => "",
    		"has_archive"         => false,
    		"show_in_menu"        => true,
    		"exclude_from_search" => false,
    		"capability_type"     => "post",
    		"map_meta_cap"        => true,
    		"hierarchical"        => false,
    		"rewrite"             => array( "slug" => ICTU_GC_CPT_VAARDIGHEDEN, "with_front" => true ),
    		"query_var"           => true,
    		"supports"            => array( "title", "editor", "thumbnail", "excerpt" ),		
			);
    	register_post_type( ICTU_GC_CPT_VAARDIGHEDEN, $args );

    
      // ---------------------------------------------------------------------------------------------------

      // custom post type voor 'Tip'
  
      	$labels = array(
      		"name"                  => _x( "Aanraders", 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Aanrader', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( "Aanraders", 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( "Alle aanraders", 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuwe aanrader toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Nieuwe aanrader toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'Aanrader bewerken', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuwe aanrader', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'aanrader bekijken', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek een aanrader', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
      
      	$args = array(
	        "label"               => _x( "Aanraders", "Label tips", 'ictu-gc-posttypes-inclusie' ),
	        "labels"              => $labels,
	        "menu_icon"           => "dashicons-yes",      		
	        "description"         => "",
	        "public"              => true,
	        "publicly_queryable"  => true,
	        "show_ui"             => true,
	        "show_in_rest"        => false,
	        "rest_base"           => "",
	        "has_archive"         => false,
	        "show_in_menu"        => true,
	        "exclude_from_search" => false,
	        "capability_type"     => "post",
	        "map_meta_cap"        => true,
	        "hierarchical"        => false,
	        "rewrite"             => array( "slug" => ICTU_GC_CPT_AANRADER, "with_front" => true ),
	        "query_var"           => true,
	        "supports"            => array( "title", "excerpt" ),		
  			);
      	register_post_type( ICTU_GC_CPT_AANRADER, $args );

    
      // ---------------------------------------------------------------------------------------------------

      // custom post type voor 'Tip'
  
      	$labels = array(
      		"name"                  => _x( "Afraders", 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Afrader', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( "Afraders", 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( "Alle afraders", 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuwe afrader toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Nieuwe afrader toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'Afrader bewerken', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuwe afrader', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'afrader bekijken', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek een afrader', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
      
      	$args = array(
        "label"                 => _x( "Afraders", "Label tips", 'ictu-gc-posttypes-inclusie' ),
        "labels"              => $labels,
        "menu_icon"           => "dashicons-no",      		
        "description"         => "",
        "public"              => true,
        "publicly_queryable"  => true,
        "show_ui"             => true,
        "show_in_rest"        => false,
        "rest_base"           => "",
        "has_archive"         => false,
        "show_in_menu"        => true,
        "exclude_from_search" => false,
        "capability_type"     => "post",
        "map_meta_cap"        => true,
        "hierarchical"        => false,
        "rewrite"             => array( "slug" => ICTU_GC_CPT_AFRADER, "with_front" => true ),
        "query_var"           => true,
        "supports"            => array( "title", "excerpt" ),		
  			);
      	register_post_type( ICTU_GC_CPT_AFRADER, $args );
    
      // ---------------------------------------------------------------------------------------------------

      // custom post type voor 'vaardigheid'
  
      	$labels = array(
      		"name"                  => _x( 'Methodes', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Methode', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( 'Methodes', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( 'Alle methodes', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuwe methode toevoegen', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Nieuwe methode toevoegen', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'Methode bewerken', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuwe methode', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'Methode bekijken', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek een Methode', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Niets gevonden', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Niets gevonden', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
      
      	$args = array(
      		"label"                 => _x( 'Methodes', 'Methodes label', 'ictu-gc-posttypes-inclusie' ),
      		"labels"              => $labels,
        "menu_icon"           => "dashicons-book-alt",      		
      		"description"         => "",
      		"public"              => true,
      		"publicly_queryable"  => true,
      		"show_ui"             => true,
      		"show_in_rest"        => false,
      		"rest_base"           => "",
      		"has_archive"         => false,
      		"show_in_menu"        => true,
      		"exclude_from_search" => false,
      		"capability_type"     => "post",
      		"map_meta_cap"        => true,
      		"hierarchical"        => false,
      		"rewrite"             => array( "slug" => ICTU_GC_CPT_METHODE, "with_front" => true ),
      		"query_var"           => true,
      		"supports"            => array( "title", "editor", "excerpt" ),		
  			);
  			
      	register_post_type( ICTU_GC_CPT_METHODE, $args );
    
      // ---------------------------------------------------------------------------------------------------
      // tijd taxonomie voor methode
      	$labels = array(
      		"name"                  => _x( 'Tijd', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Tijd', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' )
      		);
      
      	$labels = array(
      		"name"                  => _x( 'Tijd', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Tijd', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( 'Tijd', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( 'Alle tijdsinschattingen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuwe tijdsinschatting toevoegen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Voeg nieuwe tijdsinschatting toe', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'Bewerk tijdsinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuwe tijdsinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'Bekijk tijdsinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek tijdsinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Geen tijdsinschattingen gevonden', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Geen tijdsinschattingen gevonden in de prullenbak', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
  
      	$args = array(
      		"label"                 => _x( 'Tijd', 'Digibeter label', 'ictu-gc-posttypes-inclusie' ),
      		"labels"              => $labels,
      		"public"              => true,
      		"hierarchical"        => true,
      		"label"                  => _x( 'Tijd', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"show_ui"             => true,
      		"show_in_menu"        => true,
      		"show_in_nav_menus"   => true,
      		"query_var"           => true,
      		"rewrite"             => array( 'slug' => ICTU_GC_CT_TIJD, 'with_front' => true, ),
      		"show_admin_column"   => false,
      		"show_in_rest"        => false,
      		"rest_base"           => "",
      		"show_in_quick_edit"  => true,
      	);
      	register_taxonomy( ICTU_GC_CT_TIJD, array( ICTU_GC_CPT_METHODE ), $args );

      // ---------------------------------------------------------------------------------------------------
      // Personeel taxonomie voor methode
      	$labels = array(
      		"name"                  => _x( 'Personeel', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Personeel', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' )
      		);
      
      	$labels = array(
      		"name"                  => _x( 'Personeel', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Personeel', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( 'Personeel', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( 'Alle personeelsinschattingen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuwe personeelsinschatting toevoegen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Voeg nieuwe personeelsinschatting toe', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'Bewerk personeelsinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuwe personeelsinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'Bekijk personeelsinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek personeelsinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Geen personeelsinschattingen gevonden', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Geen personeelsinschattingen gevonden in de prullenbak', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
  
      	$args = array(
      		"label"                 => _x( 'Personeel', 'Digibeter label', 'ictu-gc-posttypes-inclusie' ),
      		"labels"              => $labels,
      		"public"              => true,
      		"hierarchical"        => true,
      		"label"                  => _x( 'Personeel', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"show_ui"             => true,
      		"show_in_menu"        => true,
      		"show_in_nav_menus"   => true,
      		"query_var"           => true,
      		"rewrite"             => array( 'slug' => ICTU_GC_CT_MANKRACHT, 'with_front' => true, ),
      		"show_admin_column"   => false,
      		"show_in_rest"        => false,
      		"rest_base"           => "",
      		"show_in_quick_edit"  => true,
      	);
      	register_taxonomy( ICTU_GC_CT_MANKRACHT, array( ICTU_GC_CPT_METHODE ), $args );

      // ---------------------------------------------------------------------------------------------------
      // Kosten taxonomie voor methode
      	$labels = array(
      		"name"                  => _x( 'Kosten', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Kosten', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' )
      		);
      
      	$labels = array(
      		"name"                  => _x( 'Kosten', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Kosten', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( 'Kosten', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( 'Alle kostensinschattingen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuwe kostensinschatting toevoegen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Voeg nieuwe kostensinschatting toe', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'Bewerk kostensinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuwe kostensinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'Bekijk kostensinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek kostensinschatting', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Geen kostensinschattingen gevonden', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Geen kostensinschattingen gevonden in de prullenbak', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
  
      	$args = array(
      		"label"                 => _x( 'Kosten', 'Digibeter label', 'ictu-gc-posttypes-inclusie' ),
      		"labels"              => $labels,
      		"public"              => true,
      		"hierarchical"        => true,
      		"label"                  => _x( 'Kosten', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"show_ui"             => true,
      		"show_in_menu"        => true,
      		"show_in_nav_menus"   => true,
      		"query_var"           => true,
      		"rewrite"             => array( 'slug' => ICTU_GC_CT_KOSTEN, 'with_front' => true, ),
      		"show_admin_column"   => false,
      		"show_in_rest"        => false,
      		"rest_base"           => "",
      		"show_in_quick_edit"  => true,
      	);
      	register_taxonomy( ICTU_GC_CT_KOSTEN, array( ICTU_GC_CPT_METHODE ), $args );

      // ---------------------------------------------------------------------------------------------------
      // Expertise taxonomie voor methode
      	$labels = array(
      		"name"                  => _x( 'Expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' )
      		);
      
      	$labels = array(
      		"name"                  => _x( 'Expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( 'Expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( 'Alle expertises', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuwe expertise toevoegen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Voeg nieuwe expertise toe', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'Bewerk expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuwe expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'Bekijk expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Geen expertises gevonden', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Geen expertises gevonden in de prullenbak', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
  
      	$args = array(
      		"label"                 => _x( 'Expertise', 'Digibeter label', 'ictu-gc-posttypes-inclusie' ),
      		"labels"              => $labels,
      		"public"              => true,
      		"hierarchical"        => true,
      		"label"                  => _x( 'Expertise', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"show_ui"             => true,
      		"show_in_menu"        => true,
      		"show_in_nav_menus"   => true,
      		"query_var"           => true,
      		"rewrite"             => array( 'slug' => ICTU_GC_CT_EXPERTISE, 'with_front' => true, ),
      		"show_admin_column"   => false,
      		"show_in_rest"        => false,
      		"rest_base"           => "",
      		"show_in_quick_edit"  => true,
      	);
      	register_taxonomy( ICTU_GC_CT_EXPERTISE, array( ICTU_GC_CPT_METHODE ), $args );

      // ---------------------------------------------------------------------------------------------------
      // deelnemers taxonomie voor methode
      	$labels = array(
      		"name"                  => _x( 'Deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' )
      		);
      
      	$labels = array(
      		"name"                  => _x( 'Deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( 'Deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( 'Alle deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuwe deelnemers toevoegen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Voeg nieuwe deelnemers toe', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'Bewerk deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuwe deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'Bekijk deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Geen deelnemers gevonden', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Geen deelnemers gevonden in de prullenbak', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
  
      	$args = array(
      		"label"                 => _x( 'Deelnemers', 'Digibeter label', 'ictu-gc-posttypes-inclusie' ),
      		"labels"              => $labels,
      		"public"              => true,
      		"hierarchical"        => true,
      		"label"                  => _x( 'Deelnemers', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"show_ui"             => true,
      		"show_in_menu"        => true,
      		"show_in_nav_menus"   => true,
      		"query_var"           => true,
      		"rewrite"             => array( 'slug' => ICTU_GC_CT_DEELNEMERS, 'with_front' => true, ),
      		"show_admin_column"   => false,
      		"show_in_rest"        => false,
      		"rest_base"           => "",
      		"show_in_quick_edit"  => true,
      	);
      	register_taxonomy( ICTU_GC_CT_DEELNEMERS, array( ICTU_GC_CPT_METHODE ), $args );

      // ---------------------------------------------------------------------------------------------------
      // deelnemers taxonomie voor methode
      	$labels = array(
      		"name"                  => _x( 'Onderwerpen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Onderwerp', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' )
      		);
      
      	$labels = array(
      		"name"                  => _x( 'Onderwerpen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Onderwerp', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( 'Onderwerp', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( "Alles onder 'Onderwerpen'", 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuw item toevoegen', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Voeg nieuw item toe', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'Bewerk item', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuw item', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'Bekijk item', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek item', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Geen items gevonden', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Geen items gevonden in de prullenbak', 'digibeterkleuren', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
  
      	$args = array(
      		"label"                 => _x( 'Onderwerpen', 'Aanrader label', 'ictu-gc-posttypes-inclusie' ),
      		"labels"              => $labels,
      		"public"              => true,
      		"hierarchical"        => true,
      		"label"                  => _x( 'Onderwerpen', 'Vaardigheden', 'ictu-gc-posttypes-inclusie' ),
      		"show_ui"             => true,
      		"show_in_menu"        => true,
      		"show_in_nav_menus"   => true,
      		"query_var"           => true,
      		"rewrite"             => array( 'slug' => ICTU_GC_CT_ONDERWERP_TIP, 'with_front' => true, ),
      		"show_admin_column"   => false,
      		"show_in_rest"        => false,
      		"rest_base"           => "",
      		"show_in_quick_edit"  => true,
      	);
      	
      	register_taxonomy( ICTU_GC_CT_ONDERWERP_TIP, array( ICTU_GC_CPT_AANRADER, ICTU_GC_CPT_AFRADER ), $args );


      // ---------------------------------------------------------------------------------------------------

      // clean up after ourselves
    	flush_rewrite_rules();
  
    }

//
  
	/** ----------------------------------------------------------------------------------------------------
	* filter the breadcrumb
	*/
	public function get_classifications( $theid = '', $taxonomy = '', $wrapper = 'li' ) {
		
		$return     = '';
		
		if ( $theid && $taxonomy ) {
	
			$args = array(
				'name' => $taxonomy
			);
			$output		= 'objects'; // or names
			
			$taxobject  = get_taxonomies( $args, $output ); 
			$tax_info   = array_values($taxobject)[0];
			$return     = '<' . $wrapper . '><span class="term">' . $tax_info->label . '</span>: <span class="term-values">';
			$term_list  = wp_get_post_terms( $theid, $taxonomy, array("fields" => "all"));
			$counter    = 0;
			
			foreach( $term_list as $term_single ) {
				
				$counter++;
				$term_link = get_term_link( $term_single );
				
				if ( $counter > 1 ) {
					$return .= ', '; //do something here
				}
//				$return .= '<a href="' . esc_url( $term_link ) . '">' . $term_single->name . '</a>';
				$return .= $term_single->name;
			}
			
			$return .= '</span></' . $wrapper . '>';
			
		}
		
		return $return;
		
	}

    /** ----------------------------------------------------------------------------------------------------
     * filter the breadcrumb
     */
	public function filter_breadcrumb( $crumb = '', $args = '' ) {
		
		global $post;

		$span_before_start  = '<span class="breadcrumb-link-wrap" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
		$span_between_start = '<span itemprop="name">';
		$span_before_end    = '</span>';

		if ( is_singular( ICTU_GC_CPT_CITAAT ) || is_singular( ICTU_GC_CPT_STAP ) ) {
		
			$crumb = get_the_title( get_the_id() ) ;
		
		}
		// 'doelgroep'
		
		if ( is_singular( ICTU_GC_CPT_DOELGROEP ) ) {
			
			$crumb = 'poepje ' . ICTU_GC_CPT_DOELGROEP . '<br>';
			
			$brief_page_overview        = get_field('themesettings_inclusie_doelgroeppagina', 'option');		// code hier
			
			if ( $brief_page_overview ) {

				$actueelpagetitle = get_the_title( $brief_page_overview );
				
				if ( $brief_page_overview ) {
					$crumb = gc_wbvb_breadcrumbstring( $brief_page_overview, $args );
				}
			}

			
		}
		
		return $crumb;
		
	}
    
    //** ---------------------------------------------------------------------------------------------------


  

}

endif;

//========================================================================================================
/*
	
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

*/
 
//add_action('genesis_after_header', 'wbvb_dump_actions', 5);

function wbvb_dump_actions(){ 

	$hook_name = 'genesis_loop';
	global $wp_filter;
	echo '<pre>';
	var_dump( $wp_filter[$hook_name] );	
	echo '</pre>';

}

//========================================================================================================

if (! function_exists( 'gc_wbvb_breadcrumbstring' ) ) {

	function gc_wbvb_breadcrumbstring( $currentpageID, $args ) {
	
		global $post;
		$crumb = '';
		$countertje = 0;
		
		if ( $currentpageID ) {
			$crumb = '<a href="' . get_permalink( $currentpageID ) . '">' . get_the_title( $currentpageID ) .'</a>' . $args['sep'] . ' ' . get_the_title( $post->ID );
			$postparents = get_post_ancestors( $currentpageID );
	
			foreach( $postparents as $postparent ) {
				$countertje ++;
				$crumb = '<a href="' . get_permalink( $postparent ) . '">' . get_the_title( $postparent ) .'</a>' . $args['sep'] . $crumb;
			}
		}
		
		return $crumb;
		
	}

}

//========================================================================================================
<?php

/**
 * @link                https://wbvb.nl
 * @package             ictu-gc-posttypes-inclusie
 *
 * @wordpress-plugin
 * Plugin Name:         ICTU / Gebruiker Centraal Inclusie post types and taxonomies
 * Plugin URI:          https://github.com/ICTU/Gebruiker-Centraal---Inclusie---custom-post-types-taxonomies
 * Description:         Plugin for digitaleoverheid.nl to register custom post types and custom taxonomies
 * Version:             0.0.2
 * Version description: First code for prototype.
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
/*
stappen
citaat
doelgroep
vaardigheid

aanrader / afrader
methode (technieken)

*/

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
  define( 'ICTU_GC_CPT_METHODE', 'methode' );  // slug for custom post type 'methode'
}

if ( ! defined( 'ICTU_GC_CPT_PROCESTIP' ) ) {
  define( 'ICTU_GC_CPT_PROCESTIP', 'procestip' );  // slug for custom post type 'methode'
}

if ( ! defined( 'ICTU_GC_CT_TIJD' ) ) {
  define( 'ICTU_GC_CT_TIJD', 'tijd' );  // slug for custom taxonomy 'tijd'
}

if ( ! defined( 'ICTU_GC_CT_MANKRACHT' ) ) {
  define( 'ICTU_GC_CT_MANKRACHT', 'mankracht' );  // slug for custom taxonomy 'mankracht'
}

if ( ! defined( 'ICTU_GC_CT_KOSTEN' ) ) {
  define( 'ICTU_GC_CT_KOSTEN', 'kosten' );  // slug for custom taxonomy 'kosten'
}

if ( ! defined( 'ICTU_GC_CT_EXPERTISE' ) ) {
  define( 'ICTU_GC_CT_EXPERTISE', 'expertise' );  // slug for custom taxonomy 'expertise'
}

if ( ! defined( 'ICTU_GC_CT_DEELNEMERS' ) ) {
  define( 'ICTU_GC_CT_DEELNEMERS', 'deelnemers' );  // slug for custom taxonomy 'deelnemers'
}

if ( ! defined( 'ICTU_GC_CT_ONDERWERP_TIP' ) ) {
  define( 'ICTU_GC_CT_ONDERWERP_TIP', 'onderwerpen' );  // tax for custom cpt do's & dont's
}


define( 'ICTU_GC_ARCHIVE_CSS',           'ictu-gc-header-css' );  
define( 'ICTU_GC_FOLDER',                  'do-stelselplaat' );
define( 'ICTU_GC_BASE_URL',                trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'ICTU_GC_ASSETS_URL',              trailingslashit( ICTU_GC_BASE_URL ) );
define( 'ICTU_GC_VERSION',                 '0.0.2' );


//========================================================================================================
// constants for rewrite rules


//========================================================================================================


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.2
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

      $this->setup_actions();

    }
  
    /** ----------------------------------------------------------------------------------------------------
     * Hook this plugins functions into WordPress
     */
    private function setup_actions() {

      add_action( 'init', array( $this, 'ictu_gc_register_post_type' ) );
//      add_action( 'init', array( $this, 'acf_fields' ) );
      
      
      add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
      add_action( 'init', array( $this, 'ictu_gc_add_rewrite_rules' ) );


      add_filter( 'genesis_single_crumb',   array( $this, 'filter_breadcrumb' ), 10, 2 );
      add_filter( 'genesis_page_crumb',     array( $this, 'filter_breadcrumb' ), 10, 2 );
      add_filter( 'genesis_archive_crumb',  array( $this, 'filter_breadcrumb' ), 10, 2 ); 				
      
      
      add_action( 'genesis_entry_content',  array( $this, 'prepend_content' ), 8 ); 				
      add_action( 'genesis_entry_content',  array( $this, 'append_content' ), 15 ); 			

      // add a page temlate name
      $this->templates                      = array();
      $this->templatefile   		            = 'home-inclusie.php';
    	
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

      $post_templates[$this->templatefile]  = _x( 'Home Inclusie', "naam template",  "ictu-gc-posttypes-inclusie" );    
      return $post_templates;
    
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
        
          echo '<div class="flexbox">';
        
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
              echo '<p><a href="' . $section_link['url'] . '">' . $section_link['title'] . '</a></p>';
            }
            echo '</section>';
        
          endwhile;

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
     
    public function ictu_gc_frontend_home_before_content() {
      
      global $post;
      
      if ( function_exists( 'get_field' ) ) {

        $home_inleiding   = get_field( 'home_template_inleiding', $post->ID );
        $home_stappen     = get_field( 'home_template_stappen', $post->ID );
        
        if ( $home_inleiding ) {
          echo $home_inleiding;
        }


        if( $home_stappen ): 
        
          $section_title  = _x( 'Stappen', 'titel op home-pagina', 'ictu-gc-posttypes-inclusie' );
          $title_id       = sanitize_title( $section_title . '-' . $post->ID );
          $stepcounter    = 0;

          echo '<section aria-labelledby="' . $title_id . '" id="home-chart">';
          echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';


          foreach( $home_stappen as $stap ): 
          
            $stepcounter++;
            $readmore = sprintf( __( '%s', 'ictu-gc-posttypes-inclusie' ), get_the_title( $stap->ID ) ); 

            if ( get_field( 'stap_verkorte_titel', $stap->ID ) ) {
              $titel = get_field( 'stap_verkorte_titel', $stap->ID );
            }
            else {
              $titel = get_the_title( $stap->ID );
            }
            
            if ( get_field( 'stap_inleiding', $stap->ID ) ) {
              $inleiding = get_field( 'stap_inleiding', $stap->ID );
            }
            else {
              $stap_post      = get_post( $stap->ID );
              $content        = $stap_post->post_content;
              $inleiding      = apply_filters('the_content', $content);   
            }
            
            $xtraclass = ' hidden';
            
            if  ( $stepcounter == 10 ) {
              $xtraclass = '';
            }
            
            echo '<div id="step_' . $stepcounter . '" class="step">';
            echo '<button class="stepcounter">' . $stepcounter . '</button>';
            echo '<div class="container">';
            echo '<h3 class="label"><span>' . $titel . '</span></h3>';
            echo '<div class="description' . $xtraclass . '">' . $inleiding;
            echo '<a href="' . get_permalink( $stap->ID ) . '">' . $readmore . '</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
          endforeach;


          echo '</section>';

        endif; 



        if( have_rows('home_template_doelgroepen') ):
        
          $section_title = _x( 'Doelgroepen', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
          $title_id       = sanitize_title( $section_title . '-' . $post->ID );
          $posttype       = '';

          echo '<section aria-labelledby="' . $title_id . '">';
          echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';

          echo '<div class="flexbox">';

         	// loop through the rows of data
          while ( have_rows('home_template_doelgroepen') ) : the_row();          
          
            $doelgroep      = get_sub_field('home_template_doelgroepen_doelgroep');
            $citaat         = get_sub_field('home_template_doelgroepen_citaat');
            $citaat_auteur  = sanitize_text_field( get_field( 'citaat_auteur', $citaat->ID ) );

            $citaat_post    = get_post( $citaat->ID );
            $content        = $citaat_post->post_content;
            $content        = apply_filters('the_content', $content);   
            $posttype       = get_post_type( $doelgroep->ID );
            $title_id       = sanitize_title( $posttype . '-' . $doelgroep->ID );
          
            echo '<div class="' . $posttype . ' flexblock" id="' . $title_id . '">';
            echo '<div class="featured-image">&nbsp;</div>';
            echo '<h3><a href="' . get_permalink( $doelgroep->ID ) . '">' . get_the_title( $doelgroep->ID ) . '</a></h3>';
            echo '<div class="tegeltje">' . $content . '<p><strong>' . $citaat_auteur . '</strong></p></div>';
            echo '</div>';
            
          endwhile;

          echo '</div>';

          $obj = get_post_type_object( $posttype );

          echo '<p><a href="' . get_post_type_archive_link( $posttype ) . '">' . $obj->name . '</a></p>';

          echo '</section>';

        endif; 

        
      }
      
    }    
  
    //========================================================================================================

    /**
     * Register frontend styles
     */
    public function ictu_gc_register_frontend_style_script( ) {

      wp_enqueue_style( ICTU_GC_ARCHIVE_CSS, trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/frontend.css', array(), ICTU_GC_VERSION, 'all' );

      $header_css     = '';
      $acfid          = get_the_id();
      $page_template  = get_post_meta( $acfid, '_wp_page_template', true );

      if ( !is_admin() && ( $this->templatefile == $page_template ) ) {

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
      
        if ( $header_css ) {
          wp_add_inline_style( ICTU_GC_ARCHIVE_CSS, $header_css );
        }

      }
    }

    //========================================================================================================

    /**
    * Modify page content if using a specific page template.
    */
    public function ictu_gc_frontend_use_page_template() {

      global $post;
      
      $page_template  = get_post_meta( get_the_ID(), '_wp_page_template', true );

      if ( $this->templatefile == $page_template ) {

        // append actielijnen
        add_action( 'genesis_entry_content',  array( $this, 'ictu_gc_frontend_home_before_content' ), 8 ); 				
        add_action( 'genesis_entry_content',  array( $this, 'ictu_gc_frontend_home_after_content' ), 12 ); 				
        

      }

    	//=================================================

//      add_filter( 'genesis_post_info',   array( $this, 'filter_postinfo' ), 10, 2 );

    }
    

    /** ----------------------------------------------------------------------------------------------------
     * Add rewrite rules
     */
    public function ictu_gc_add_rewrite_rules() {
    
  
    }


    /** ----------------------------------------------------------------------------------------------------
     * filter the breadcrumb
     */
    public function prepend_content( $thecontent) {

      global $post;

      if ( is_singular( ICTU_GC_CPT_STAP ) ) {

        if ( function_exists( 'get_field' ) ) {

          $stap_inleiding = get_field( 'stap_inleiding', $post->ID );
          $stap_methodes  = get_field( 'stap_methodes', $post->ID );
          
          if ( $stap_inleiding ) {
            echo $stap_inleiding;
          }

          if( $stap_methodes ):

            $section_title = _x( 'Methoden', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
            $title_id       = sanitize_title( $section_title . '-' . $post->ID );
          
            echo '<section aria-labelledby="' . $title_id . '">';
            echo '<h2 id="' . $title_id . '">' . $section_title . '</h2>';
            echo '<div class="flexbox">';
          
            // loop through the rows of data
            foreach( $stap_methodes as $post ):

              setup_postdata( $post );

              $theid = $post->ID;
          
              $section_title  = get_the_title( $theid );
              $section_text   = get_the_excerpt( $theid );
              $section_link   = get_sub_field( 'home_template_teaser_link' );
              $title_id       = sanitize_title( $section_title );
          
              echo '<div class="flexblock">';
              echo '<h3 id="' . $title_id . '">' . $section_title . '</h3>';
              echo $section_text;
              echo '<p><a href="' . get_permalink( $theid ) . '">' . get_the_title( $theid ) . '</a></p>';
              echo '</div>';
          
            endforeach;
            
            wp_reset_postdata();            
  
            echo '</div>';
            echo '</section>';
          
          endif; 

          $section_title = _x( 'Proces', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
          $title_id       = sanitize_title( $section_title . '-' . $post->ID );
          echo '<h2 id="' . $title_id . '">' . $section_title . '</h2>';
          
        }
      }
      elseif ( is_singular( ICTU_GC_CPT_DOELGROEP ) ) {

        $section_title  = _x( 'Citaten', 'titel op methode-pagina', 'ictu-gc-posttypes-inclusie' );
        $title_id       = sanitize_title( $section_title . '-' . $post->ID );
        $facts_citaten  = get_field( 'facts_citaten', $post->ID );

        echo '<section aria-labelledby="' . $title_id . '">';
        echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';

        echo '<div class="flexbox tegeltjes">';

        if ( is_object( $facts_citaten ) || is_array( $facts_citaten ) ) {
          // loop through the rows of data
          foreach( $facts_citaten as $post ):
  
            setup_postdata( $post );
  
            $theid = $post->ID;
        
            $section_title  = get_the_title( $theid );
            $title_id       = sanitize_title( $section_title );
            $citaat_post    = get_post( $theid );
            $content        = $citaat_post->post_content;
            $section_text   = apply_filters('the_content', $content);   
            $citaat_auteur  = sanitize_text_field( get_field( 'citaat_auteur', $theid ) );
  
            echo '<div class="flexblock tegeltje">' . $section_text . '<p><strong>' . $citaat_auteur . '</strong></p></div>';
        
          endforeach;
  
          echo '</div>';
          echo '</section>';
          
          wp_reset_postdata();            

        }
      }
      else {
//        echo ' citaten niet<br>';
      }

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
      elseif ( is_singular( ICTU_GC_CPT_AFRADER ) ||  is_singular( ICTU_GC_CPT_AANRADER ) ) {

        if ( ! $post->post_content ) {
          // content is apparently empty
          $section_text   = get_the_excerpt( $post->ID );
          
          if ( $section_text ) {
            echo '<p>' . $section_text . '</p>';
          }
          else {
            echo '<p>' . sprintf( __( "No further content is available for '%s'.", 'ictu-gc-posttypes-inclusie' ), get_the_title( $post->ID ) ) . '</p>'; 
          }

        }
        
      }
      elseif ( is_singular( ICTU_GC_CPT_STAP ) ) {
      }
      elseif ( is_singular( ICTU_GC_CPT_DOELGROEP ) ) {

        $section_title      = _x( 'Cijfers', 'titel op doelgroep-pagina', 'ictu-gc-posttypes-inclusie' );
        $title_id           = sanitize_title( $section_title . '-title' );
        $facts_title        = get_field( 'facts_title' );
        $facts_description  = get_field( 'facts_description' );
        $facts_source_url   = get_field( 'facts_source_url' );
        $facts_source_label = get_field( 'facts_source_label' );
        
        if ( $facts_title ) {
        
          echo '<section aria-labelledby="' . $title_id . '" class="facts-figures">';
          echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';
          echo '<div><span class="hugely">' . sanitize_text_field( $facts_title ) . '</span> ';
          echo sanitize_text_field( $facts_description ) . '</div>';
          if ( $facts_source_url && $facts_source_label ) {
            echo '<p><a href="' . esc_url( $facts_source_url ) . '">' . sanitize_text_field( $facts_source_label ) . '</a></p>';
          }
          echo '</section>';
        
        }
        

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

              echo '<div class="flexbox">';

              if ( $vaardigheid_aanraders ) {
                $section_title = _x( 'Aanrader', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
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
                $section_title = _x( 'Afrader', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie' );
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
          echo '<ul>';
          echo $classificering;
          echo '</ul>';
          echo '</section>';
          
        }
        
      }
      
      if ( is_singular( ICTU_GC_CPT_STAP ) || is_singular( 'page' ) ) {

        if ( function_exists( 'get_field' ) ) {
          
          $gerelateerdecontent = get_field( 'gerelateerde_content_toevoegen', get_the_id() );
  
          if ( $gerelateerdecontent == 'ja' ) {
  
            $section_title  = get_field( 'content_block_title', $post->ID );
            $title_id       = sanitize_title( $section_title . '-title' );
            $related_items  = get_field('content_block_items');
  
            echo '<section aria-labelledby="' . $title_id . '" class="related-content">';
            echo '<h2 id="' . $title_id . '">' . $section_title . '</h2>';
            echo '<div class="flexbox">';
          
            // loop through the rows of data
            foreach( $related_items as $post ):
  
              setup_postdata( $post );
  
              $theid = $post->ID;
          
              $section_title  = get_the_title( $theid );
              $section_text   = get_the_excerpt( $theid );
              $section_link   = get_sub_field( 'home_template_teaser_link' );
              $title_id       = sanitize_title( $section_title );
          
              echo '<div class="flexblock">';
              echo '<h3 id="' . $title_id . '">' . $section_title . '</h3>';
              echo $section_text;
              echo '<p><a href="' . get_permalink( $theid ) . '">' . get_the_title( $theid ) . '</a></p>';
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
                
      }

      return $thecontent;

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
    		"has_archive"         => true,
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
      // custom post type voor 'Citaten'

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

      // custom post type voor 'Aanraders'
  
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

      // custom post type voor 'Afraders'
  
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

      // custom post type voor 'methode'
  
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
      // custom post type voor 'procestip'
  
      	$labels = array(
      		"name"                  => _x( 'Procestips', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"singular_name"         => _x( 'Procestip', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"menu_name"             => _x( 'Procestips', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"all_items"             => _x( 'Alle procestips', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"add_new"               => _x( 'Nieuwe procestip toevoegen', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"add_new_item"          => _x( 'Nieuwe procestip toevoegen', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"edit_item"             => _x( 'procestip bewerken', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"new_item"              => _x( 'Nieuwe procestip', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"view_item"             => _x( 'procestip bekijken', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"search_items"          => _x( 'Zoek een procestip', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"not_found"             => _x( 'Niets gevonden', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"not_found_in_trash"    => _x( 'Niets gevonden', 'Methode type', 'ictu-gc-posttypes-inclusie' ),
      		"featured_image"        => __( 'Featured image', 'ictu-gc-posttypes-inclusie' ),
      		"archives"              => __( 'Archives', 'ictu-gc-posttypes-inclusie' ),
      		"uploaded_to_this_item" => __( 'Uploaded media', 'ictu-gc-posttypes-inclusie' ),
      		);
      
      	$args = array(
      		"label"                 => _x( 'Procestips', 'Methodes label', 'ictu-gc-posttypes-inclusie' ),
      		"labels"              => $labels,
        "menu_icon"           => "dashicons-welcome-learn-more",      		
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
  			
      	register_post_type( ICTU_GC_CPT_PROCESTIP, $args );
    
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

      $return     = 'START';

      if ( $theid && $taxonomy ) {


        $args=array(
          'name' => $taxonomy
        );
        $output = 'objects'; // or names
        
        $taxobject  = get_taxonomies( $args, $output ); 
        $tax_info   = array_values($taxobject)[0];
        $return     = '<' . $wrapper . '><span class="term">' . $tax_info->label . '</span>: ';
        $term_list  = wp_get_post_terms( $theid, $taxonomy, array("fields" => "all"));
        $counter    = 0;

        foreach( $term_list as $term_single ) {

          $counter++;
          $term_link = get_term_link( $term_single );
          
          if ( $counter > 1 ) {
            $return .= ', '; //do something here
          }
          $return .= '<a href="' . esc_url( $term_link ) . '">' . $term_single->name . '</a>';
        }

        $return .= '</' . $wrapper . '>';

      }

      return $return;

    }
    


//
  
    /** ----------------------------------------------------------------------------------------------------
     * filter the breadcrumb
     */
    public function filter_breadcrumb( $crumb = '', $args = '' ) {

      global $post;
      
      if ( is_singular( ICTU_GC_CPT_CITAAT ) || is_singular( ICTU_GC_CPT_STAP ) ) {
        
        $crumb = get_the_title( get_the_id() ) ;
        
      }

      return $crumb;

    }
    
    //** ---------------------------------------------------------------------------------------------------


    public function acf_fields() {
      
      if( function_exists('acf_add_local_field_group') ):
      
      acf_add_local_field_group(array(
      	'key' => 'group_5c8f882222034',
      	'title' => 'Citaat: auteur',
      	'fields' => array(
      		array(
      			'key' => 'field_5c8f882d1c131',
      			'label' => 'citaat auteur',
      			'name' => 'citaat_auteur',
      			'type' => 'text',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '',
      			'placeholder' => '',
      			'prepend' => '',
      			'append' => '',
      			'maxlength' => '',
      		),
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'citaat',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'acf_after_title',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));
      
      acf_add_local_field_group(array(
      	'key' => 'group_5c8fd53fbda24',
      	'title' => 'Doelgroep: citaat, facts & figures',
      	'fields' => array(
      		array(
      			'key' => 'field_5c922f65f8152',
      			'label' => 'Bijbehorende citaten',
      			'name' => 'facts_citaten',
      			'type' => 'relationship',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'post_type' => array(
      				0 => 'citaat',
      			),
      			'taxonomy' => '',
      			'filters' => array(
      				0 => 'search',
      			),
      			'elements' => '',
      			'min' => '',
      			'max' => '',
      			'return_format' => 'object',
      		),
      		array(
      			'key' => 'field_5c8fd54f1a328',
      			'label' => 'facts_title',
      			'name' => 'facts_title',
      			'type' => 'text',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '',
      			'placeholder' => '',
      			'prepend' => '',
      			'append' => '',
      			'maxlength' => '',
      		),
      		array(
      			'key' => 'field_5c8fd568b08ec',
      			'label' => 'facts_description',
      			'name' => 'facts_description',
      			'type' => 'wysiwyg',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'tabs' => 'all',
      			'toolbar' => 'full',
      			'media_upload' => 1,
      			'default_value' => '',
      			'delay' => 0,
      		),
      		array(
      			'key' => 'field_5c8fd57f464c4',
      			'label' => 'facts_source_url',
      			'name' => 'facts_source_url',
      			'type' => 'url',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '',
      			'placeholder' => '',
      		),
      		array(
      			'key' => 'field_5c8fd5975c283',
      			'label' => 'facts_source_label',
      			'name' => 'facts_source_label',
      			'type' => 'text',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '',
      			'placeholder' => '',
      			'prepend' => '',
      			'append' => '',
      			'maxlength' => '',
      		),
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'doelgroep',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'normal',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));
      
      acf_add_local_field_group(array(
      	'key' => 'group_5c8fd6bacf265',
      	'title' => 'Doelgroep: vaardigheden',
      	'fields' => array(
      		array(
      			'key' => 'field_5c924f598eeab',
      			'label' => 'doelgroep_vaardigheden',
      			'name' => 'doelgroep_vaardigheden',
      			'type' => 'relationship',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'post_type' => array(
      				0 => 'vaardigheden',
      			),
      			'taxonomy' => '',
      			'filters' => array(
      				0 => 'search',
      				1 => 'taxonomy',
      			),
      			'elements' => '',
      			'min' => '',
      			'max' => '',
      			'return_format' => 'object',
      		),
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'doelgroep',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'normal',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));
      
      acf_add_local_field_group(array(
      	'key' => 'group_5c90e063ca578',
      	'title' => 'Homepage template: inleiding, stappen en doelgroepen',
      	'fields' => array(
      		array(
      			'key' => 'field_5c90fd5fe0a58',
      			'label' => 'home_template_inleiding',
      			'name' => 'home_template_inleiding',
      			'type' => 'wysiwyg',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '',
      			'tabs' => 'all',
      			'toolbar' => 'full',
      			'media_upload' => 1,
      			'delay' => 0,
      		),
      		array(
      			'key' => 'field_5c90e0739fa4b',
      			'label' => 'Stappen',
      			'name' => 'home_template_stappen',
      			'type' => 'relationship',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'post_type' => array(
      				0 => 'stap',
      			),
      			'taxonomy' => '',
      			'filters' => array(
      				0 => 'search',
      				1 => 'taxonomy',
      			),
      			'elements' => '',
      			'min' => '',
      			'max' => '',
      			'return_format' => 'object',
      		),
      		array(
      			'key' => 'field_5c90e096cca0a',
      			'label' => 'Doelgroepen',
      			'name' => 'home_template_doelgroepen',
      			'type' => 'repeater',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'collapsed' => '',
      			'min' => 0,
      			'max' => 0,
      			'layout' => 'table',
      			'button_label' => '',
      			'sub_fields' => array(
      				array(
      					'key' => 'field_5c9104bff7ade',
      					'label' => 'Kies doelgroep',
      					'name' => 'home_template_doelgroepen_doelgroep',
      					'type' => 'post_object',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'post_type' => array(
      						0 => 'doelgroep',
      					),
      					'taxonomy' => '',
      					'allow_null' => 1,
      					'multiple' => 0,
      					'return_format' => 'object',
      					'ui' => 1,
      				),
      				array(
      					'key' => 'field_5c910507f7adf',
      					'label' => 'Kies citaat',
      					'name' => 'home_template_doelgroepen_citaat',
      					'type' => 'post_object',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'post_type' => array(
      						0 => 'citaat',
      					),
      					'taxonomy' => '',
      					'allow_null' => 1,
      					'multiple' => 0,
      					'return_format' => 'object',
      					'ui' => 1,
      				),
      			),
      		),
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'page_template',
      				'operator' => '==',
      				'value' => 'home-inclusie.php',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'acf_after_title',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));
      
      acf_add_local_field_group(array(
      	'key' => 'group_5c91ff4e9f37c',
      	'title' => 'Homepage template: teasers',
      	'fields' => array(
      		array(
      			'key' => 'field_5c91ff4eb3ace',
      			'label' => 'Doelgroepen',
      			'name' => 'home_template_teasers',
      			'type' => 'repeater',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'collapsed' => 'field_5c91ff4ebb071',
      			'min' => 0,
      			'max' => 0,
      			'layout' => 'block',
      			'button_label' => '',
      			'sub_fields' => array(
      				array(
      					'key' => 'field_5c91ff4ebb071',
      					'label' => 'Titel',
      					'name' => 'home_template_teaser_title',
      					'type' => 'text',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'default_value' => '',
      					'placeholder' => '',
      					'prepend' => '',
      					'append' => '',
      					'maxlength' => '',
      				),
      				array(
      					'key' => 'field_5c91ff4ebb07a',
      					'label' => 'home_template_teaser_text',
      					'name' => 'home_template_teaser_text',
      					'type' => 'wysiwyg',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'default_value' => '',
      					'tabs' => 'all',
      					'toolbar' => 'basic',
      					'media_upload' => 0,
      					'delay' => 0,
      				),
      				array(
      					'key' => 'field_5c92023852c87',
      					'label' => 'home_template_teaser_link',
      					'name' => 'home_template_teaser_link',
      					'type' => 'link',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'return_format' => 'array',
      				),
      			),
      		),
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'page_template',
      				'operator' => '==',
      				'value' => 'home-inclusie.php',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'acf_after_title',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));
      
      acf_add_local_field_group(array(
      	'key' => 'group_5c8f9ba967736',
      	'title' => 'Stap en pagina\'s: gerelateerde content',
      	'fields' => array(
      		array(
      			'key' => 'field_5c8fe203a8435',
      			'label' => 'Gerelateerde content toevoegen?',
      			'name' => 'gerelateerde_content_toevoegen',
      			'type' => 'radio',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'choices' => array(
      				'ja' => 'Ja',
      				'nee' => 'Nee',
      			),
      			'allow_null' => 0,
      			'other_choice' => 0,
      			'default_value' => 'nee',
      			'layout' => 'vertical',
      			'return_format' => 'value',
      			'save_other_choice' => 0,
      		),
      		array(
      			'key' => 'field_5c8fd404bd765',
      			'label' => 'content_block_title',
      			'name' => 'content_block_title',
      			'type' => 'text',
      			'instructions' => '',
      			'required' => 1,
      			'conditional_logic' => array(
      				array(
      					array(
      						'field' => 'field_5c8fe203a8435',
      						'operator' => '==',
      						'value' => 'ja',
      					),
      				),
      			),
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '',
      			'placeholder' => '',
      			'prepend' => '',
      			'append' => '',
      			'maxlength' => '',
      		),
      		array(
      			'key' => 'field_5c8fd42e15a23',
      			'label' => 'content_block_items',
      			'name' => 'content_block_items',
      			'type' => 'relationship',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => array(
      				array(
      					array(
      						'field' => 'field_5c8fe203a8435',
      						'operator' => '==',
      						'value' => 'ja',
      					),
      				),
      			),
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'post_type' => array(
      				0 => 'post',
      				1 => 'page',
      				2 => 'doelgroep',
      				3 => 'stap',
      			),
      			'taxonomy' => '',
      			'filters' => array(
      				0 => 'search',
      				1 => 'post_type',
      				2 => 'taxonomy',
      			),
      			'elements' => '',
      			'min' => '',
      			'max' => '',
      			'return_format' => 'object',
      		),
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'stap',
      			),
      		),
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'page',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'normal',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));
      
      acf_add_local_field_group(array(
      	'key' => 'group_5c8fdeebf0c34',
      	'title' => 'Stap en pagina\'s: handige (externe) links',
      	'fields' => array(
      		array(
      			'key' => 'field_5c8fe142c5418',
      			'label' => 'Handige links toevoegen?',
      			'name' => 'handige_links_toevoegen',
      			'type' => 'radio',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'choices' => array(
      				'ja' => 'Ja',
      				'nee' => 'Nee',
      			),
      			'allow_null' => 0,
      			'other_choice' => 0,
      			'default_value' => 'nee',
      			'layout' => 'vertical',
      			'return_format' => 'value',
      			'save_other_choice' => 0,
      		),
      		array(
      			'key' => 'field_5c8fdeec07d4b',
      			'label' => 'links_block_title',
      			'name' => 'links_block_title',
      			'type' => 'text',
      			'instructions' => '',
      			'required' => 1,
      			'conditional_logic' => array(
      				array(
      					array(
      						'field' => 'field_5c8fe142c5418',
      						'operator' => '==',
      						'value' => 'ja',
      					),
      				),
      			),
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '',
      			'placeholder' => '',
      			'prepend' => '',
      			'append' => '',
      			'maxlength' => '',
      		),
      		array(
      			'key' => 'field_5c8fdeec07d58',
      			'label' => 'links_block_items',
      			'name' => 'links_block_items',
      			'type' => 'repeater',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => array(
      				array(
      					array(
      						'field' => 'field_5c8fe142c5418',
      						'operator' => '==',
      						'value' => 'ja',
      					),
      				),
      			),
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'collapsed' => '',
      			'min' => 1,
      			'max' => 0,
      			'layout' => 'table',
      			'button_label' => '',
      			'sub_fields' => array(
      				array(
      					'key' => 'field_5c8fdf2a0c1a1',
      					'label' => 'links_block_item_url',
      					'name' => 'links_block_item_url',
      					'type' => 'url',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '30',
      						'class' => '',
      						'id' => '',
      					),
      					'default_value' => '',
      					'placeholder' => '',
      				),
      				array(
      					'key' => 'field_5c8fe1000c1a2',
      					'label' => 'links_block_item_linktext',
      					'name' => 'links_block_item_linktext',
      					'type' => 'text',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '30',
      						'class' => '',
      						'id' => '',
      					),
      					'default_value' => '',
      					'placeholder' => '',
      					'prepend' => '',
      					'append' => '',
      					'maxlength' => '',
      				),
      				array(
      					'key' => 'field_5c8fe10d0c1a3',
      					'label' => 'links_block_item_description',
      					'name' => 'links_block_item_description',
      					'type' => 'textarea',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '40',
      						'class' => '',
      						'id' => '',
      					),
      					'default_value' => '',
      					'placeholder' => '',
      					'maxlength' => '',
      					'rows' => '',
      					'new_lines' => '',
      				),
      			),
      		),
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'stap',
      			),
      		),
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'page',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'normal',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));
      
      acf_add_local_field_group(array(
      	'key' => 'group_5c8fde441c0a9',
      	'title' => 'Stap: inleiding en methodes',
      	'fields' => array(
      		array(
      			'key' => 'field_5c91fb7281870',
      			'label' => 'Verkorte titel',
      			'name' => 'stap_verkorte_titel',
      			'type' => 'text',
      			'instructions' => 'Deze tekst wordt als label getoond in het stappenschema. Gebruik bij voorkeur 1 woord.',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '',
      			'placeholder' => '',
      			'prepend' => '',
      			'append' => '',
      			'maxlength' => '',
      		),
      		array(
      			'key' => 'field_5c90bae01c857',
      			'label' => 'stap_inleiding',
      			'name' => 'stap_inleiding',
      			'type' => 'wysiwyg',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '',
      			'tabs' => 'all',
      			'toolbar' => 'basic',
      			'media_upload' => 1,
      			'delay' => 0,
      		),
      		array(
      			'key' => 'field_5c8fde5259d46',
      			'label' => 'stap_methodes',
      			'name' => 'stap_methodes',
      			'type' => 'relationship',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'post_type' => array(
      				0 => 'methode',
      			),
      			'taxonomy' => '',
      			'filters' => array(
      				0 => 'search',
      			),
      			'elements' => '',
      			'min' => '',
      			'max' => '',
      			'return_format' => 'object',
      		),
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'stap',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'acf_after_title',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));
      
      acf_add_local_field_group(array(
      	'key' => 'group_5c9398df21747',
      	'title' => 'Vaardigheid: aan- en afraders',
      	'fields' => array(
      		array(
      			'key' => 'field_5c9398f446669',
      			'label' => 'Afraders',
      			'name' => 'vaardigheid_afraders',
      			'type' => 'relationship',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'post_type' => array(
      				0 => 'afrader',
      			),
      			'taxonomy' => '',
      			'filters' => array(
      				0 => 'search',
      				1 => 'taxonomy',
      			),
      			'elements' => '',
      			'min' => '',
      			'max' => '',
      			'return_format' => 'object',
      		),
      		array(
      			'key' => 'field_5c93993e49d5c',
      			'label' => 'Aanraders',
      			'name' => 'vaardigheid_aanraders',
      			'type' => 'relationship',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'post_type' => array(
      				0 => 'aanrader',
      			),
      			'taxonomy' => '',
      			'filters' => array(
      				0 => 'search',
      				1 => 'taxonomy',
      			),
      			'elements' => '',
      			'min' => '',
      			'max' => '',
      			'return_format' => 'object',
      		),
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'vaardigheden',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'normal',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));
      
      endif;
            
    }  

}

endif;

//========================================================================================================

if ( ! function_exists( 'bidirectional_acf_update_value' ) ) {

  function bidirectional_acf_update_value( $value, $post_id, $field  ) {
    
dovardump( $value, 'value' );
dovardump( $post_id, 'post_id' );
dovardump( $field, 'field' );

    // vars
    $field_name   = $field['name'];
    $field_key    = $field['key'];
    $global_name  = 'is_updating_' . $field_name;
    
    $debugstring = 'bidirectional_acf_update_value';
    
    $debugstring .= "value='" . implode( ", ", $value ) . "'";
    $debugstring .= ", post_id='" . $post_id . "'";
    $debugstring .= " (type=" . get_post_type( $post_id ) . ")";
    $debugstring .= ", field_key='" . $field_key . "'";
    $debugstring .= ", field_name='" . $field_name . "'";
    
    // dodebug( $debugstring );
    
    // bail early if this filter was triggered from the update_field() function called within the loop below
    // - this prevents an inifinte loop
    if( !empty($GLOBALS[ $global_name ]) ) return $value;
    
    
    // set global variable to avoid inifite loop
    // - could also remove_filter() then add_filter() again, but this is simpler
    $GLOBALS[ $global_name ] = 1;
    
    
    // loop over selected posts and add this $post_id
    if( is_array($value) ) {
    
      // dodebug( 'bidirectional_acf_update_value: is array' );
    
    	foreach( $value as $post_id2 ) {
    
        $debugstring = "post_id2='" . $post_id2 . "'";
        $debugstring .= " (type=" . get_post_type( $post_id2 ) . ")";
    
    
        // dodebug( $debugstring );
    
    		
    		// load existing related posts
    		$value2 = get_field($field_name, $post_id2, false);
    		
    		
    		// allow for selected posts to not contain a value
    		if( empty($value2) ) {
    			
    			$value2 = array();
    			
    		}
    
    		
    		
    		// bail early if the current $post_id is already found in selected post's $value2
    		if( in_array($post_id, $value2) ) continue;
    		
    		
    		// append the current $post_id to the selected post's 'related_posts' value
    		$value2[] = $post_id;
    		
    		
    		// update the selected post's value (use field's key for performance)
    		update_field($field_key, $value2, $post_id2);
    		
    	}
    
    }
    
    
    // find posts which have been removed
    $old_value = get_field($field_name, $post_id, false);
    
    if( is_array($old_value) ) {
    	
      	foreach( $old_value as $post_id2 ) {
      		
      		// bail early if this value has not been removed
      		if( is_array($value) && in_array($post_id2, $value) ) continue;
      		
      		
      		// load existing related posts
      		$value2 = get_field($field_name, $post_id2, false);
      		
      		
      		// bail early if no value
      		if( empty($value2) ) continue;
      		
      		
      		// find the position of $post_id within $value2 so we can remove it
      		$pos = array_search($post_id, $value2);
      		
      		
      		// remove
      		unset( $value2[ $pos] );
      		
      		
      		// update the un-selected post's value (use field's key for performance)
      		update_field($field_key, $value2, $post_id2);
      		
      	}
    	
    }
    
    // reset global varibale to allow this filter to function as per normal
    $GLOBALS[ $global_name ] = 0;
    
    // return
    return $value;
      
  }

}

// maak de relate tussen een vaardigheid en bijbehorende af- en aanraders wederkerig
add_filter('acf/update_value/name=vaardigheid_afraders', 'bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=vaardigheid_aanraders', 'bidirectional_acf_update_value', 10, 3);

// maak de relate tussen een af- en aanrader en bijbehorende vaardighede wederkerig
add_filter('acf/update_value/name=aan_afrader_vaardigheden', 'bidirectional_acf_update_value', 10, 3);

//========================================================================================================



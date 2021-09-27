<?php

/**
    * @link                    https://inclusie.gebruikercentraal.nl
    * @package                ictu-gc-posttypes-inclusie
    *
    * @wordpress-plugin
    * Plugin Name:            ICTU / Gebruiker Centraal / Inclusie post types and taxonomies
    * Plugin URI:             https://github.com/ICTU/Gebruiker-Centraal---Inclusie---custom-post-types-taxonomies
    * Description:            Plugin for inclusie.gebruikercentraal.nl to register custom post types and custom taxonomies
    * Version:                1.1.10.a
    * Version description:    Iconen bijgewerkt, ivm toegankelijkheidscheck.
    * Author:                 Tamara de Haas & Paul van Buuren
    * Author URI:             https://wbvb.nl/
    * License:                GPL-2.0+
    * License URI:            http://www.gnu.org/licenses/gpl-2.0.txt
    * Text Domain:            ictu-gc-posttypes-inclusie
    * Domain Path:            /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

//========================================================================================================

add_action('plugins_loaded', ['ICTU_GC_Register_taxonomies', 'init'], 10);

//========================================================================================================

if (!defined('ICTU_GC_CPT_STAP')) {
    define('ICTU_GC_CPT_STAP', 'stap');   // slug for custom taxonomy 'document'
}

if (!defined('ICTU_GC_CPT_CITAAT')) {
    define('ICTU_GC_CPT_CITAAT', 'citaat');   // slug for custom taxonomy 'citaat'
}

if (!defined('ICTU_GC_CPT_DOELGROEP')) {
    define('ICTU_GC_CPT_DOELGROEP', 'doelgroep');  // slug for custom post type 'doelgroep'
}

if (!defined('ICTU_GC_CPT_VAARDIGHEDEN')) {
    define('ICTU_GC_CPT_VAARDIGHEDEN', 'vaardigheden');  // slug for custom post type 'nietzomaarzo'
}

if (!defined('ICTU_GC_CPT_AANRADER')) {
    define('ICTU_GC_CPT_AANRADER', 'aanrader');  // slug for custom post type 'nietzomaarzo'
}

if (!defined('ICTU_GC_CPT_AFRADER')) {
    define('ICTU_GC_CPT_AFRADER', 'afrader');  // slug for custom post type 'nietzomaarzo'
}

if (!defined('ICTU_GC_CPT_METHODE')) {
    define('ICTU_GC_CPT_METHODE', 'methode');  // slug for custom post type 'doelgroep'
}

if (!defined('ICTU_GC_CT_TIJD')) {
    define('ICTU_GC_CT_TIJD', 'tijd');  // slug for custom taxonomy 'tijd'
}

if (!defined('ICTU_GC_CT_MANKRACHT')) {
    define('ICTU_GC_CT_MANKRACHT', 'mankracht');  // slug for custom taxonomy 'mankracht'
}

if (!defined('ICTU_GC_CT_KOSTEN')) {
    define('ICTU_GC_CT_KOSTEN', 'kosten');  // slug for custom taxonomy 'mankracht'
}

if (!defined('ICTU_GC_CT_EXPERTISE')) {
    define('ICTU_GC_CT_EXPERTISE', 'expertise');  // slug for custom taxonomy 'mankracht'
}

if (!defined('ICTU_GC_CT_DEELNEMERS')) {
    define('ICTU_GC_CT_DEELNEMERS', 'deelnemers');  // slug for custom taxonomy 'mankracht'
}

if (!defined('ICTU_GC_CT_ONDERWERP_TIP')) {
    define('ICTU_GC_CT_ONDERWERP_TIP', 'onderwerpen');  // tax for custom cpt do's & dont's
}

if (!defined('ICTU_GC_CPT_PROCESTIP')) {
    define('ICTU_GC_CPT_PROCESTIP', 'procestip');  // tax for custom cpt tip
}

define('ICTU_GC_ARCHIVE_CSS', 'ictu-gc-header-css');
define('ICTU_GC_BASE_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('ICTU_GC_ASSETS_URL', trailingslashit(ICTU_GC_BASE_URL));
define('ICTU_GC_INCL_VERSION', '1.1.10.a');

//========================================================================================================

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */


if (!class_exists('ICTU_GC_Register_taxonomies')) :

    class ICTU_GC_Register_taxonomies {

        /**
         * @var Rijksvideo
         */
        public $inclusieobject = NULL;

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

            require_once dirname(__FILE__) . '/includes/inclusie.acf-definitions.php';

        }

        /** ----------------------------------------------------------------------------------------------------
         * Hook this plugins functions into WordPress
         */
        private function setup_actions() {

            add_action('init', [$this, 'ictu_gc_register_post_type']);
            add_action('init', 'ictu_gc_inclusie_initialize_acf_fields'); // function from inclusie.acf-definitions.php
            add_action('init', [
              $this,
              'ictu_gc_admin_inclusie_set_terms_order',
            ]);

            add_action('get_the_terms', [
              $this,
              'ictu_gc_admin_inclusie_get_terms_in_order',
            ], 10, 4);

            add_action('init', [$this, 'ictu_gc_add_rewrite_rules']);

            add_action('genesis_single_crumb', [
              $this,
              'filter_breadcrumb',
            ], 10, 2);
            add_action('genesis_page_crumb', [
              $this,
              'filter_breadcrumb',
            ], 10, 2);
            add_action('genesis_archive_crumb', [
              $this,
              'filter_breadcrumb',
            ], 10, 2);

            /** Adding custom Favicon */
            add_action('genesis_pre_load_favicon', [$this, 'custom_favicon']);

            add_action('genesis_entry_content', [$this, 'append_content'], 15);

            // add a page temlate name
            $this->templates = [];
            $this->template_home = 'home-inclusie.php';

            // add the page template to the templates list
            add_filter('theme_page_templates', [
              $this,
              'ictu_gc_add_page_templates',
            ]);

            // activate the page filters
            add_filter('template_redirect', [
              $this,
              'ictu_gc_frontend_use_page_template',
            ]);

            // disable the author pages
            add_action('template_redirect', 'ictu_gctheme_disable_author_pages');

            // add styling and scripts
            add_action('wp_enqueue_scripts', [
              $this,
              'ictu_gc_register_frontend_style_script',
            ]);

            // add header css
            add_action('wp_enqueue_scripts', [
              $this,
              'ictu_gc_append_header_css_local',
            ]);
            add_action('wp_enqueue_scripts', 'ictu_gctheme_card_append_header_css');


        }

        //========================================================================================================

        /**
         * Hides the custom post template for pages on WordPress 4.6 and older
         *
         * @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
         *
         * @return array Expanded array of page templates.
         */
        function ictu_gc_add_page_templates($post_templates) {

            $post_templates[$this->template_home] = _x('Inclusie - Home page', "naam template", "ictu-gc-posttypes-inclusie");
            return $post_templates;

        }


        //========================================================================================================

        /**
         * Admin: force tax to sort by assigned sort order
         *
         * @in: terms, term ID, taxonomy
         * @return terms
         */
        public function ictu_gc_admin_inclusie_get_terms_in_order($terms, $id, $taxonomy) {
            $terms = wp_cache_get($id, "{$taxonomy}_relationships_sorted");
            if (FALSE === $terms) {
                $terms = wp_get_object_terms($id, $taxonomy, ['orderby' => 'term_order']);
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
        public function ictu_gc_admin_inclusie_set_terms_order() {
            global $wp_taxonomies;  //fixed missing semicolon
            // the following relates to tags, but you can add more lines like this for any taxonomy
            $wp_taxonomies['post_tag']->sort = TRUE;
            $wp_taxonomies['post_tag']->args = ['orderby' => 'term_order'];
        }


        //========================================================================================================

        /**
         * Handles the front-end display.
         *
         * @return void
         */
        public function ictu_gc_frontend_doelgroeppagina_content() {

            global $post;

            $all_or_some = get_field('doelgroeppagina_showall_or_select', $post->ID);

            if ('showsome' === $all_or_some) {

                $doelgroepen = get_field('doelgroeppagina_kies_doelgroepen', $post->ID);

                if ($doelgroepen) {

                    echo '<div class="grid grid--col-3">';

                    $postcounter = 0;

                    foreach ($doelgroepen as $post):

                        setup_postdata($post);

                        $postcounter++;
                        $citaat = get_field('facts_citaten', $post->ID);

                        echo ictu_gctheme_card_doelgroep($post, $citaat);

                    endforeach;

                    echo '</div>';

                    wp_reset_query();

                }
                else {
                    echo '<p>' . _x('Geen doelgroepen geselecteerd voor deze pagina.', "error", "ictu-gc-posttypes-inclusie") . '</p>';
                }
            }
            else {

                $args = [
                  'post_type' => ICTU_GC_CPT_DOELGROEP,
                  'posts_per_page' => -1,
                  'order' => 'ASC',
                  'orderby' => 'post_title',

                ];
                $alledoelgroepen = new WP_query($args);

                if ($alledoelgroepen->have_posts()) {

                    echo '<div class="grid grid--col-3">';

                    $postcounter = 0;

                    while ($alledoelgroepen->have_posts()) : $alledoelgroepen->the_post();

                        $postcounter++;
                        $citaat = get_field('facts_citaten', $post->ID);

                        echo ictu_gctheme_card_doelgroep($post, $citaat);

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
        public function ictu_gc_frontend_doelgroep_avatar_en_citaat() {

            global $post;

            $stap_inleiding = get_field('stap_inleiding', $post->ID);
            $stap_methodes = get_field('stap_methodes', $post->ID);
            $stap_methode_inleiding = get_field('stap_methode_inleiding', $post->ID);

            // Set doelgroeppoppetje

            $doelgroeppoppetje = 'poppetje-1';
            if (get_field('doelgroep_avatar', $post->ID)) {
                $doelgroeppoppetje = get_field('doelgroep_avatar', $post->ID);
            }

            echo '<div id="doelgroep-inleiding" class="doelgroep--' . $doelgroeppoppetje . '">';
            echo '<header class="entry-header wrap"><h1 class="entry-title">' . get_the_title() . '</h1></header>';

            if ($stap_inleiding) {
                echo sanitize_text_field($stap_inleiding);
            }
            else {
                if (get_the_excerpt($post->ID)) {
                    echo '<p class="doelgroep__posttext">' . get_the_excerpt($post->ID) . '</p>';
                }
            }

            $section_title = _x('Citaten', 'titel op methode-pagina', 'ictu-gc-posttypes-inclusie');
            $title_id = sanitize_title($section_title . '-' . $post->ID);
            $facts_citaten = get_field('facts_citaten', $post->ID);

            echo '<div class="" id="doelgroep-inleiding-citaten">';

            // loop through the rows of data
            foreach ($facts_citaten as $post):

                setup_postdata($post);

                $theid = $post->ID;

                $section_title = get_the_title($theid);
                $title_id = sanitize_title($section_title);
                $citaat_post = get_post($theid);
                $content = '&rdquo;' . $citaat_post->post_content . '&rdquo;';
                $section_text = apply_filters('the_content', $content);
                $citaat_auteur = sanitize_text_field(get_field('citaat_auteur', $theid));

                echo '<div class="tegeltje">' . $section_text . '<p class="author"><strong>' . $citaat_auteur . '</strong></p></div>';

            endforeach;

            //echo '<div class="feat-image ' . $doelgroeppoppetje . '">;</div>';

            echo '</div>';

            echo '<div class="doelgroep__avatar"></div>'; // #doelgroep-inleiding
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

            if (!$homepageID) {
                return;
            }
            if ( is_search() ) {
	            // geen stappen tonen als deze pagina getoond wordt in sitesearch zoekresultaten
				// @since	1.1.8
                return;
            }

            if (function_exists('get_field')) {

                $home_stappen = get_field('home_template_stappen', $homepageID);

                if ($home_stappen):
                    $section_title = _x('Stappen', 'titel op home-pagina', 'ictu-gc-posttypes-inclusie');
                    $title_id = sanitize_title($section_title . '-' . $post->ID);
                    $stepcounter = 0;

                    echo '<div aria-labelledby="' . $title_id . '" class="stepnav">';
                    echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';
                    echo '<ol class="stepnav__items l-item-count-' . count($home_stappen) . '">';

                    foreach ($home_stappen as $stap):
                        unset($icon_classes);
                        $active = '';
                        $icon_classes[] = 'stepnav__icon';
                        $stepcounter++;
                        $titel = get_the_title($stap->ID);

                        if (get_field('stap_verkorte_titel', $stap->ID)) {
                            $titel = get_field('stap_verkorte_titel', $stap->ID);
                        }

                        if (get_field('stap_icon', $stap->ID)) {
                            $icon_classes[] = 'icon--' . get_field('stap_icon', $stap->ID);
                        }

                        if ($stap->ID === $post->ID) {
                            $active = 'active';
                        }

                        $title_id = sanitize_title(get_the_title($stap->ID) . '-' . $stepcounter);
                        $steptitle = sprintf(_x('%s. %s', 'Label stappen', 'ictu-gc-posttypes-inclusie'), $stepcounter, $titel);


                        $stepnav_item = '<li id="step_' . $stepcounter . '" class="stepnav__step">' .
                          '<a href="' . get_permalink($stap->ID) . '" class="stepnav__link ' . (($active) ? 'is-active' : '') . '" title="' . $titel . '" >' .
                          '<svg class="icon icon--stepnav" focusable="false">' .
                          '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="' . get_stylesheet_directory_uri() . '/images/svg/stepchart/defs/svg/sprite.defs.svg#' . get_field('stap_icon', $stap->ID) . '"></use> ' .
                          '</svg> ' .
                          '<span class="stepnav__linktext">' . $titel . '</span>' .
                          '</a></li>';

                        echo $stepnav_item;

                    endforeach;

                    echo '</ol>';
                    echo '</div>';

                endif;

            } // if (function_exists('get_field'))
        }

        //========================================================================================================

        /**
         * Display title and short description before body text of single step
         *
         * @return void
         */


        public function ictu_gc_frontend_stap_inleiding() {

            global $post;

            if ( is_search() ) {
	            // geen inleiding tonen als deze pagina getoond wordt in sitesearch zoekresultaten
				// @since	1.1.8
                return;
            }

            if (function_exists('get_field')) {

                $stap_inleiding = get_field('stap_inleiding', $post->ID);
                $stap_methodes_titel = get_field('stap_methodes_titel', $post->ID);

                if (!$stap_methodes_titel) {
                    $stap_methodes_titel = _x('Methoden', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie');
                }

                // Make reusable Intro region as a data container
                echo '<div class="region region--content-top">' .
                  '<div class="page-intro inleiding">' .
                  '<header class="entry-header"><h1 class="entry-title">' . get_the_title() . '</h1>' . '</header>';

                if ($stap_inleiding) {
                    echo $stap_inleiding;
                }

                echo '</div>'; // class="page-intro inleiding"
                echo '</div>'; //  class="region region--content-top"


            } // if (function_exists('get_field'))

        }

        //========================================================================================================

        /**
         * Lists the attached methodes to a single step
         *
         * @return void
         */


        public function ictu_gc_frontend_stap_methodes($args = []) {

            global $post;

            $defaults = [
              'ID' => 0,
              'titletag' => 'h2',
              'echo' => TRUE,
            ];

            // Parse incoming $args into an array and merge it with $defaults
            $args = wp_parse_args($args, $defaults);

            if (function_exists('get_field')) {


                $stap_methodes = get_field('stap_methodes', $post->ID);
                $stap_methode_inleiding = get_field('stap_methode_inleiding', $post->ID);
                $stap_methodes_titel = get_field('stap_methodes_titel', $post->ID);

                if ($stap_methodes):

                    $section_title = $stap_methodes_titel;

                    $title_id = sanitize_title($section_title . '-' . $post->ID);

                    echo '<section  aria-labelledby="' . $title_id . '" class="section section--related-methoden">';
                    echo '<div class="l-section-top">';
                    echo '<h2 id="' . $title_id . '" class="section__title">' . $section_title . '</h2>';

                    if ($stap_methode_inleiding) {
                        echo $stap_methode_inleiding;
                    }
                    else {
                        echo sprintf('<p>%s</p>', _x('Dit is een selectie van methoden, technieken en instrumenten die je in kunt zetten bij het uitvoeren van deze stap. Soms gaat het om standaardmethoden die worden ingezet voor het uitvoeren van een ontwerptraject waarbij de gebruiker centraal staat. Andere methoden richten zich specifiek op inclusie. ', 'Stap: intro bij methoden', 'ictu-gc-posttypes-inclusie'));
                    }
                    echo '</div>'; // .page-intro__intro-text

                    echo '<div class="grid grid--col-3">';

                    // loop through the rows of data
                    foreach ($stap_methodes as $post):

                        setup_postdata($post);

                        $theid = $post->ID;

                        $section_title = get_the_title($theid);
                        $section_text = get_the_excerpt($theid);
                        $section_link = get_sub_field('home_template_teaser_link');
                        $title_id = sanitize_title($section_title);

                        echo '<div class="card">';
                        echo '<div class="card__content">';
                        echo '<h3 class="card__title" id="' . $title_id . '">' .
                          '<a class="arrow-link" href="' . get_permalink($theid) . '">' .
                          '<span class="arrow-link__text">' . $section_title . '</span>' .
                          '<span class="arrow-link__icon"></span>' .
                          '</a></h3>';
                        echo '<p>';
                        echo $section_text;
                        echo '</p>';
                        echo '</div>';
                        echo '</div>';

                    endforeach;

                    wp_reset_postdata();

                    echo '</div>'; // class="grid grid--col-3 text-block
                    echo '</section>';

                endif;

            } // if (function_exists('get_field'))

        }

        //========================================================================================================

        /**
         * Lists the attached (proces)tips to a single step
         * returns either an array or a block of HTML
         *
         * @return $return (string) / $menuarray (array)
         */


        public function ictu_gc_frontend_stap_procestips($args) {

            global $post;

            $defaults = [
              'ID' => 0,
              'titletag' => 'h2',
              'getmenu' => FALSE,
              'echo' => TRUE,
            ];

            // Parse incoming $args into an array and merge it with $defaults
            $args = wp_parse_args($args, $defaults);

            $menuarray = [];
            $return = '';

            if (function_exists('get_field')) {

                $stap_procestips_titel = get_field('stap_procestips_sectiontitle', $post->ID);
                $stap_procestips = get_field('stap_procestips', $post->ID);

                // Procestips
                if ($stap_procestips):
                    // er zijn procestips
                    $title_id = sanitize_title($stap_procestips_titel . '-' . $post->ID);

                    $return .= '<section  aria-labelledby="' . $title_id . '" class="section section--related section--related-tips">';
                    $return .= '<div class="l-section-top">';
                    $return .= '<' . $args['titletag'] . ' id="' . $title_id . '" class="section__title">' . $stap_procestips_titel . '</' . $args['titletag'] . '>';
                    $return .= '</div>';

                    $return .= '<div class="l-section-content">';

                    // loop through the rows of data
                    foreach ($stap_procestips as $post):

                        setup_postdata($post);

                        $theid = $post->ID;

                        $section_title = get_the_title($theid);
                        $section_text = get_the_excerpt($theid);
                        $title_id = sanitize_title($section_title);

                        if ($args['getmenu']) {
                            $menuarray[$title_id] = $section_title;
                        }
                        else {

                            $return .= '<div aria-labelledby="' . $title_id . '" class="doelgroep-tips">';
                            $return .= '<h3><a href="' . get_permalink($theid) . '" id="' . $title_id . '">' . $section_title . '</a></h3>';
                            $return .= $section_text;
                            $return .= '</div>'; // .doelgroep-tips;

                        }


                    endforeach;

                    wp_reset_query();

                    $return .= '</div>'; //  .wrap
                    $return .= '</section>'; //  class="section--related-tips

                endif; // $stap_tips_od

            } // if (function_exists('get_field'))
            else {
                $return = 'Activeer ACF plugin';
            }

            if ($args['getmenu']) {
                return $menuarray;
            }
            elseif ($args['echo']) {
                echo $return;
            }
            else {
                return $return;
            }


        }

        //========================================================================================================

        /**
         * Displays Optimaal Digitaal tips for a single step
         *
         * @in: $args (array)
         * @return $return (string) / $menuarray (array)
         */


        public function ictu_gc_frontend_stap_optimaaldigitaal($args) {

            global $post;

            $defaults = [
              'ID' => 0,
              'titletag' => 'h2',
              'getmenu' => FALSE,
              'echo' => TRUE,
            ];

            // Parse incoming $args into an array and merge it with $defaults
            $args = wp_parse_args($args, $defaults);

            $menuarray = [];
            $return = '';

            if (function_exists('get_field')) {

                $stap_tips_od_titel = get_field('stap_tips_optimaal_digitaal_sectiontitle', $post->ID);


                // Optimaal Digitaal tips
                if (have_rows('stap_tips_optimaal_digitaal', $post->ID)):

                    // er zijn Optimaal Digitaal-tips
                    $title_id = sanitize_title($stap_tips_od_titel . '-' . $post->ID);

                    $return .= '<section  aria-labelledby="' . $title_id . '" class="section section--related section--related-optimaaldigitaal-tips">';
                    $return .= '<div class="l-section-top">';
                    $return .= '<h2 id="' . $title_id . '" class="section__title">' . $stap_tips_od_titel . '</h2>';
                    $return .= '</div>';
                    $return .='<div class="l-section-content">';

                    $row_count = count(get_field('stap_tips_optimaal_digitaal', $post->ID));

                    if(!($row_count === 3)):
                        $return .= '<div class="cards grid grid--col-2">';
                    else :
                        $return .= '<div class="cards grid grid--col-3">';
                    endif;


                    while (have_rows('stap_tips_optimaal_digitaal', $post->ID)) : the_row();

                        $section_title = get_sub_field('stap_tip_optimaal_digitaal_titel');
                        $tipnummer = get_sub_field('stap_tip_optimaal_digitaal_tipnummer');
                        $section_link = get_sub_field('stap_tip_optimaal_digitaal_url');
                        $section_class = get_sub_field('stap_tip_optimaal_digitaal_tipthema');

                        $title_id = sanitize_title($section_title);

                        $section_title = od_wbvb_custom_post_title($section_title);

                        if ($args['getmenu']) {
                            $menuarray[$title_id] = $section_title;
                        }
                        else {

                            $tipcard = '<div class="card card--tipkaart tipkaart ' . $section_class . '">';
                            $tipcard .= ($section_link ? '<a href="' . $section_link . '" class="tipkaart__link">' : '');
                            $tipcard .= '<span class="tipkaart__nummer">Tip ' . $tipnummer . '</span>';
                            $tipcard .= '<h3 class="tipkaart__title" id="' . $title_id . '">' . $section_title . '</h3>';
                            $tipcard .= '<span class="tipkaart__categorie">'.$section_class.'</span>';
                            $tipcard .= ($section_link ? '</a>' : '');
                            $tipcard .= '</div>';

                            $return .= $tipcard;

                        }

                    endwhile;

                    $return .= '</div>'; // .cards grid grid--col-2
                    $return .= '</div>'; // .l-section-content
                    $return .= '</section>';

                else:
                    // nothing

                endif; // $stap_tips_od

            } // if (function_exists('get_field'))
            else {
                $return = 'Activeer ACF plugin';
            }

            if ($args['getmenu']) {
                return $menuarray;
            }
            elseif ($args['echo']) {
                echo $return;
            }
            else {
                return $return;
            }

        }



        //========================================================================================================

        /**
         * Handles the front-end display.
         *
         * @return void
         */
        public function ictu_gc_frontend_home_template_stappen() {

            global $post;

			ictu_gctheme_home_template_stappen( $post, trailingslashit(plugin_dir_url(__FILE__)) . 'assets/images/stappenplan-bg-fullscreen.svg' );

        }


        //========================================================================================================

        /**
         * Register frontend styles
         */
        public function ictu_gc_frontend_home_template_doelgroepen() {

            global $post;


            if (have_rows('home_template_doelgroepen')):

                $section_title = _x('Doelgroepen', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie');
                $title_id = sanitize_title($section_title . '-' . $post->ID);
                $posttype = '';

                echo '<div class="region region--content-top">' .
                  '<div class="overview">' .
                  // Items
                  '<div class="overview__items grid grid--col-3">';

                // loop through the rows of data
                while (have_rows('home_template_doelgroepen')) : the_row();

                    $doelgroep = get_sub_field('home_template_doelgroepen_doelgroep');
                    $citaat = get_sub_field('home_template_doelgroepen_citaat');

                    echo ictu_gctheme_card_doelgroep($doelgroep, $citaat);

                endwhile;

                echo '</div>';

                $doelgroeplink = get_post_type_archive_link(ICTU_GC_CPT_DOELGROEP);
                $label = _x('Alle doelgroepen', 'Linktekst doelgroepoverzicht', 'ictu-gc-posttypes-inclusie'); // $obj->name;
                $doelgroeppaginaid = get_field('themesettings_inclusie_doelgroeppagina', 'option');

                if ($doelgroeppaginaid) {

                    $doelgroeplink = get_permalink($doelgroeppaginaid);
                    $label = get_the_title($doelgroeppaginaid);

                }

                echo '<a href="' . $doelgroeplink . '" class="btn btn--primary ' . $posttype . '">' . $label . '</a>';

            endif;

        }


        //========================================================================================================

        /**
         * Register frontend styles
         */
        public function ictu_gc_append_header_css_local() {

            global $post;


			if ( ! defined( 'ID_SKIPLINKS' ) ) {
				define( 'ID_SKIPLINKS', 'skiplinks' );
			}

			$dependencies = array( ID_SKIPLINKS );

            wp_enqueue_style(
            	ICTU_GC_ARCHIVE_CSS,
            	trailingslashit(plugin_dir_url(__FILE__)) . 'css/frontend.css',
            	$dependencies,
            	ICTU_GC_INCL_VERSION,
            	'all'
            );

            $header_css = '';
            $acfid = get_the_id();
            $page_template = get_post_meta($acfid, '_wp_page_template', TRUE);

            if (!is_admin() && ($this->template_home == $page_template)) {

                if (have_rows('home_template_doelgroepen')):

                    // loop through the rows of data
                    while (have_rows('home_template_doelgroepen')) : the_row();

                        $doelgroep = get_sub_field('home_template_doelgroepen_doelgroep');
                        $posttype = get_post_type($doelgroep->ID);
                        $title_id = sanitize_title($posttype . '-' . $doelgroep->ID);
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($doelgroep->ID), 'large');

                        if ($image[0]) {
                            $header_css .= "#" . $title_id . " .featured-image { ";
                            $header_css .= "background-image: url('" . $image[0] . "'); ";
                            $header_css .= "} ";
                        }
                        else {
                            //              $header_css .= "background: yellow;";
                        }

                    endwhile;

                endif;

            }

            $gerelateerdecontent = get_field('gerelateerde_content_toevoegen', $acfid);

            if ('ja' === $gerelateerdecontent) {

                $related_items = get_field('content_block_items');

                // loop through the rows of data
                foreach ($related_items as $post):

                    setup_postdata($post);

                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');

                    if ($image[0]) {
                        $header_css .= "#related_" . $post->ID . " .card__image { ";
                        $header_css .= "background-image: url('" . $image[0] . "'); ";
                        $header_css .= "} ";
                    }


                endforeach;

                wp_reset_postdata();

            }

            if ($header_css) {
                wp_add_inline_style(ICTU_GC_ARCHIVE_CSS, $header_css);
            }

        }

        //========================================================================================================

        /**
         * Register frontend styles
         */
	    public function ictu_gc_register_frontend_style_script() {

		    global $post;

		    $infooter = true;

		    // to do: geen externe scripts laden
		    $version     = ICTU_GC_BEELDBANK_VERSION;
		    $version_btn = ICTU_GC_BEELDBANK_VERSION;
		    if ( WP_DEBUG ) {
			    // als WP_DEBUG actief, gebruik filedate als versienummer
			    $version     = filemtime( dirname( __FILE__ ) . '/js/stepchart.js' );
			    $version_btn = filemtime( dirname( __FILE__ ) . '/js/btn.js' );
		    }

		    wp_enqueue_script( 'gen-jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js', '', $version, $infooter );
		    wp_enqueue_script( 'inclusie-waypoints', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/libs/jquery.waypoints.min.js', '', $version, $infooter );
		    wp_enqueue_script( 'inclusie-stepchart', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/stepchart.js', '', $version, $infooter );
		    wp_enqueue_script( 'inclusie-btn', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/btn.js', '', $version_btn, $infooter );
		    wp_enqueue_script( 'inclusie-contentmenu', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/content-menu.js', '', $version, $infooter );

		    $params = [
			    'open'  => _x( 'Open menu', 'Open / close button menu', 'ictu-gc-posttypes-inclusie' ),
			    'close' => _x( 'Close menu', 'Open / close button menu', 'ictu-gc-posttypes-inclusie' ),
		    ];

		    //' . _x('Sluit', 'label knop stepchart', 'ictu-gc-posttypes-inclusie') .

		    wp_localize_script( 'inclusie-contentmenu', 'contentmenu', $params );


	    }

        //========================================================================================================

        /**
         * Modify page content if using a specific page template.
         */
        public function ictu_gc_frontend_use_page_template() {

            global $post;

            $page_template = get_post_meta(get_the_ID(), '_wp_page_template', TRUE);

            if ($this->template_home == $page_template) {

                remove_filter('genesis_post_title_output', 'gc_wbvb_sharebuttons_for_page_top', 15);

                //* Remove standard header
                remove_action('genesis_entry_header', 'genesis_entry_header_markup_open', 5);
                remove_action('genesis_entry_header', 'genesis_entry_header_markup_close', 15);
                remove_action('genesis_entry_header', 'genesis_do_post_title');

                //* Remove the post content (requires HTML5 theme support)
                remove_action('genesis_entry_content', 'genesis_do_post_content');

                // stappen toevoegen
                add_action('genesis_entry_content', [
                  $this,
                  'ictu_gc_frontend_home_template_stappen',
                ], 8);

                // poppetje en citaat toevoegen
                add_action('genesis_entry_content', [
                  $this,
                  'ictu_gc_frontend_home_template_doelgroepen',
                ], 12);


                // append content
                add_action('genesis_after_content', 'ictu_gctheme_home_template_teasers', 12);

                add_action('genesis_after_content', [
                  $this,
                  'ictu_gc_frontend_stap_get_related_content',
                ], 14);


            }
            elseif (ICTU_GC_CPT_VAARDIGHEDEN == get_post_type()) {
                // single vaardigheden display

                remove_action('genesis_entry_header', 'genesis_entry_header_markup_open', 5);
                remove_action('genesis_entry_header', 'genesis_entry_header_markup_close', 15);
                remove_action('genesis_entry_header', 'genesis_do_post_title');


                // append content
                add_action('genesis_entry_header', [
                  $this,
                  'ictu_gc_frontend_vaardigheid_append_title',
                ], 10);
                add_action('genesis_entry_content', [
                  $this,
                  'ictu_gc_frontend_vaardigheid_append_aanraders_afraders',
                ], 12);

            }
            elseif (ICTU_GC_CPT_STAP == get_post_type()) {

                //* Remove standard header
                remove_action('genesis_entry_header', 'genesis_entry_header_markup_open', 5);
                remove_action('genesis_entry_header', 'genesis_entry_header_markup_close', 15);
                remove_action('genesis_entry_header', 'genesis_do_post_title');


                // 1: overzicht met stappen boven de titel
                // append content
                add_action('genesis_before_loop', [
                  $this,
                  'ictu_gc_frontend_stap_before_content',
                ], 8);

                // 2: titel op een groen vlak zetten, met een korte inleiding indien nodig
                add_action('genesis_loop', [
                  $this,
                  'ictu_gc_frontend_stap_inleiding',
                ], 8);

                // 3: uitschrijven van the_content

                // 4: uitschrijven 'methoden' (ictu_gc_frontend_stap_methodes)
                add_action('genesis_after_entry', [
                  $this,
                  'ictu_gc_frontend_stap_methodes',
                ], 12);

                // 5: verzamelen en uitschrijven van toc-menu
                add_action('genesis_after_entry', [
                  $this,
                  'ictu_gc_frontend_stap_append_toc_and_related',
                ], 12);

            }
            elseif (is_singular(ICTU_GC_CPT_DOELGROEP)) {

                //* Remove standard header
                remove_action('genesis_entry_header', 'genesis_entry_header_markup_open', 5);
                remove_action('genesis_entry_header', 'genesis_entry_header_markup_close', 15);
                remove_action('genesis_entry_header', 'genesis_do_post_title');

                // poppetje en citaat toevoegen
                add_action('genesis_before_entry', [
                  $this,
                  'ictu_gc_frontend_doelgroep_avatar_en_citaat',
                ], 8);

                // cijfers toevoegen
                add_action('genesis_entry_header', [
                  $this,
                  'ictu_gc_frontend_doelgroep_append_cijfers',
                ], 10);

                // inhoudsopgave (menu) toevoegen
                add_action('genesis_entry_content', [
                  $this,
                  'ictu_gc_frontend_doelgroep_append_toc',
                ], 12);

                // tips toevoegen
                add_action('genesis_entry_content', [
                  $this,
                  'ictu_gc_frontend_doelgroep_append_content',
                ], 14);


            }


            //=================================================

            add_filter('genesis_post_info', [$this, 'filter_postinfo'], 10, 2);

        }


        /** ----------------------------------------------------------------------------------------------------
         * Append TOC (table of contents) and related content to stap single
         */
        public function ictu_gc_frontend_stap_append_toc_and_related() {

            global $post;

            $args = [
              'getmenu' => TRUE,
            ];

            $array_inline_menu_items = $this->ictu_gc_frontend_stap_construct_page($args);

            if ($array_inline_menu_items) {

                echo '<div class="entry-content extra-content" id="stap_append_toc">'; // #stap_append_toc
                echo '<div class="wrap">';
                echo $this->ictu_gc_general_construct_toc($array_inline_menu_items);
                echo '</div>'; // class="wrap";

                $args = [
                  'ID' => 0,
                  'titletag' => 'h2',
                  'getmenu' => FALSE,
                  'echo' => FALSE,
                ];

                echo $this->ictu_gc_frontend_stap_procestips($args);
                echo $this->ictu_gc_frontend_stap_optimaaldigitaal($args);
                echo '</div>'; // #stap_append_toc

                echo $this->ictu_gc_frontend_stap_get_related_content($args);


            }

        }


        /** ----------------------------------------------------------------------------------------------------
         * Append TOC (table of contents) to doelgroep single
         */
        public function ictu_gc_frontend_doelgroep_append_toc() {

            global $post;

            $args = [
              'getmenu' => TRUE,
            ];

            $array_inline_menu_items = $this->ictu_gc_frontend_doelgroep_construct_page($args);

            if ($array_inline_menu_items) {

                echo $this->ictu_gc_general_construct_toc($array_inline_menu_items);

            }

        }


        /** ----------------------------------------------------------------------------------------------------
         * Append vaardigheden to doelgroep single
         */
        public function ictu_gc_frontend_doelgroep_append_content() {

            global $post;

            $args = [
              'getmenu' => FALSE,
            ];

            echo $this->ictu_gc_frontend_doelgroep_construct_page($args);

        }


        /** ----------------------------------------------------------------------------------------------------
         * Append vaardigheden to doelgroep single
         */
        public function ictu_gc_frontend_stap_get_related_content($args = []) {

            global $post;

            $defaults = [
              'ID' => 0,
              'titletag' => 'h2',
              'getmenu' => FALSE,
              'echo' => TRUE,
            ];

            // Parse incoming $args into an array and merge it with $defaults
            $args = wp_parse_args($args, $defaults);

            // for ictu_gctheme_frontend_general_get_related_content(), see related-content-links.php in themes/ictuwp-theme-gebruikercentraal
            // @since	1.1.4
            $return = ictu_gctheme_frontend_general_get_related_content($args);

            if ($args['getmenu']) {
                return $return;
            }
            elseif ($args['echo']) {
                echo $return;
            }
            else {
                return $return;
            }


        }

        //========================================================================================================

        /**
         * collects all the parts of the single step page. Return either an array with links for in the TOC or
         * return a block of HTML (as a return value or as echoed string)
         *
         * @return $return (string) / $menuarray (array)
         */
        public function ictu_gc_frontend_stap_construct_page($args = []) {

            global $post;

            $defaults = [
              'ID' => 0,
              'titletag' => 'h2',
              'getmenu' => FALSE,
              'echo' => FALSE,
            ];

            // Parse incoming $args into an array and merge it with $defaults
            $args = wp_parse_args($args, $defaults);

            $return = '';

            if ($args['getmenu']) {

                return array_merge(
                  $this->ictu_gc_frontend_stap_procestips($args),
                  $this->ictu_gc_frontend_stap_optimaaldigitaal($args),
                  $this->ictu_gc_frontend_stap_get_related_content($args)
                );

            }
            else {
                $return .= $this->ictu_gc_frontend_stap_procestips($args);
                $return .= $this->ictu_gc_frontend_stap_optimaaldigitaal($args);
                $return .= $this->ictu_gc_frontend_stap_get_related_content($args);
            }

            if ($args['echo']) {
                echo $return;
            }
            else {
                return $return;
            }

        }

        /** ----------------------------------------------------------------------------------------------------
         * Append vaardigheden to doelgroep single
         */
        public function ictu_gc_frontend_doelgroep_construct_page($args = []) {

            global $post;

            $defaults = [
              'ID' => 0,
              'titletag' => 'h2',
              'getmenu' => FALSE,
              'echo' => FALSE,
            ];

            // Parse incoming $args into an array and merge it with $defaults
            $args = wp_parse_args($args, $defaults);

            $return = '';

            if ($args['getmenu']) {
                // for ictu_gctheme_frontend_general_get_related_content(), see related-content-links.php in themes/ictuwp-theme-gebruikercentraal
                // @since	1.1.4
                return array_merge(
                  $this->ictu_gc_frontend_doelgroep_get_tips($args),
                  $this->ictu_gc_frontend_doelgroep_get_vaardigheden($args),
                  ictu_gctheme_frontend_general_get_related_content($args)
                );

            }
            else {
                // for ictu_gctheme_frontend_general_get_related_content(), see related-content-links.php in themes/ictuwp-theme-gebruikercentraal
                // @since	1.1.4
                $return .= $this->ictu_gc_frontend_doelgroep_get_tips($args);
                $return .= $this->ictu_gc_frontend_doelgroep_get_vaardigheden($args);
                $return .= ictu_gctheme_frontend_general_get_related_content($args);
            }

            if ($args['echo']) {
                echo $return;
            }
            else {
                return $return;
            }


        }


        /** ----------------------------------------------------------------------------------------------------
         * Append vaardigheden to doelgroep single
         */
        public function ictu_gc_frontend_doelgroep_get_tips($args = []) {

            global $post;

            $defaults = [
              'ID' => 0,
              'titletag' => 'h2',
              'getmenu' => FALSE,
              'echo' => TRUE,
            ];

            // Parse incoming $args into an array and merge it with $defaults
            $args = wp_parse_args($args, $defaults);

            $menuarray = [];
            $return = '';

            $doelgroep_tips = get_field('doelgroep_tips', $post->ID);
            $doelgroep_tips_titel = get_field('doelgroep_tips_titel', $post->ID);
            $doelgroep_tips_inleiding = get_field('doelgroep_tips_inleiding', $post->ID);
            $doelgroeppoppetje = get_field('doelgroep_avatar', $post->ID);

            $section_title = ($doelgroep_tips_titel) ? $doelgroep_tips_titel : _x('Tips', 'titel op Doelgroep-pagina', 'ictu-gc-posttypes-inclusie');
            $title_id = sanitize_title($section_title . '-' . $post->ID);

            if ($doelgroep_tips):

                // Reusable section wrapper + title. This is not specific for tips.
                $return = '<div class="section section--tips-doelgroep doelgroep--' . $doelgroeppoppetje . '">';

                if ($args['getmenu']) {
                    $menuarray[$title_id] = $section_title;
                }
                else {
                    $return .= '<h2 id="' . $title_id . '" class="section__title">' .
                      '<span class="l-tiptitle-icon">&nbsp;</span>' .
                      $section_title . '</h2>';
                }

                if ($doelgroep_tips_inleiding) {
                    $return .= '<p>' . $doelgroep_tips_inleiding . '</p>';
                }

                // loop through the rows of data
                foreach ($doelgroep_tips as $post):

                    setup_postdata($post);

                    $theid = $post->ID;
                    $section_title = get_the_title($theid);
                    $section_text = get_the_excerpt($theid);
                    $title_id = sanitize_title($section_title);

                    if ($args['getmenu']) {
                        $menuarray[$title_id] = $section_title;
                    }
                    else {

                        $return .= '<section aria-labelledby="' . $title_id . '" class="doelgroep-tips">';
                        $return .= '<h3><a href="' . get_permalink($theid) . '" id="' . $title_id . '">' . $section_title . '</a></h3>';
                        $return .= $section_text;
                        $return .= '</section>';

                    }


                endforeach;

                wp_reset_postdata();

                $return .= '</div>';

            endif;

            if ($args['getmenu']) {
                return $menuarray;
            }
            elseif ($args['echo']) {
                echo $return;
            }
            else {
                return $return;
            }


        }

        /** ----------------------------------------------------------------------------------------------------
         * Append vaardigheden to doelgroep single
         */
        public function ictu_gc_frontend_doelgroep_get_vaardigheden($args = []) {

            global $post;

            $defaults = [
              'ID' => 0,
              'titletag' => 'h2',
              'getmenu' => FALSE,
              'echo' => TRUE,
            ];

            // Parse incoming $args into an array and merge it with $defaults
            $args = wp_parse_args($args, $defaults);

            $menuarray = [];
            $return = '';

            $doelgroep_vaardigheden = get_field('doelgroep_vaardigheden', $post->ID);
            $doelgroep_vaardigheden_titel = get_field('doelgroep_vaardigheden_titel', $post->ID);
            $doelgroep_vaardigheden_inleiding = get_field('doelgroep_vaardigheden_inleiding', $post->ID);

            if ($doelgroep_vaardigheden):

                // Reusable section wrapper + title. This is not specific for tips.
                $return = '<div class="section section--capabilities">';

                $section_title = ($doelgroep_vaardigheden_titel) ? $doelgroep_vaardigheden_titel : _x('Vaardigheden', 'titel op Doelgroep-pagina', 'ictu-gc-posttypes-inclusie');
                $title_id = sanitize_title($section_title . '-' . $post->ID);

                if ($args['getmenu']) {
                    $menuarray[$title_id] = $section_title;
                }

                $return .= '<h2 id="' . $title_id . '" class="section__title">' . $section_title . '</h2>';

                if ($doelgroep_vaardigheden_inleiding) {
                    $return .= '<p>' . $doelgroep_vaardigheden_inleiding . '</p>';
                }

                // loop through the rows of data
                foreach ($doelgroep_vaardigheden as $post):

                    setup_postdata($post);

                    $theid = $post->ID;

                    $section_title = get_the_title($theid);
                    $section_text = get_the_excerpt($theid);

                    $vaardigheid_icoon = get_field('vaardigheid_icoon', $theid);


                    $content = $post->post_content;
                    $section_text = apply_filters('the_content', $content);

                    $section_link = get_sub_field('home_template_teaser_link');
                    $title_id = sanitize_title($section_title . '-' . $theid);

                    $vaardigheid_afraders = get_field('vaardigheid_afraders', $theid);
                    $vaardigheid_aanraders = get_field('vaardigheid_aanraders', $theid);

                    if ($vaardigheid_afraders || $vaardigheid_aanraders):

                        if ($args['getmenu']) {
                            $menuarray[$title_id] = $section_title;
                        }
                        else {

                            $return .= '<section class="capability' . (get_field('vaardigheid_icoon', $theid) ? ' capability--' . get_field('vaardigheid_icoon') : '') . '" aria-labelledby="' . $title_id . '">';
                            $return .= '<h3 class="capability__title" id="' . $title_id . '">' .
                              '<span class="capability__icon' . (get_field('vaardigheid_icoon', $theid) ? ' icon--' . get_field('vaardigheid_icoon') : '') . '" aria-hidden="true">&nbsp;</span>' .
                              $section_title . '</h3>';
                            $return .= $section_text;

                            $return .= '<div class="dos-donts">';

                            if ($vaardigheid_aanraders) {
                                $section_title = _x('Aanraders', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie');
                                $title_id = sanitize_title($section_title . '-' . $theid . '-' . $section_title);
                                $return .= '<div class="dos-donts__column col--dos">';
                                $return .= '<h4 id="' . $title_id . '">' . $section_title . '</h4>';
                                $return .= '<ul>';
                                foreach ($vaardigheid_aanraders as $vaardigheid_aanrader):
                                    $return .= '<li>' . get_the_title($vaardigheid_aanrader->ID) . '</li>';
                                endforeach;
                                //	            wp_reset_postdata();
                                $return .= '</ul>';
                                $return .= '</div>';
                            }
                            if ($vaardigheid_afraders) {
                                $section_title = _x('Afraders', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie');
                                $title_id = sanitize_title($section_title . '-' . $theid . '-' . $section_title);
                                $return .= '<div class="dos-donts__column col--donts">';
                                $return .= '<h4 id="' . $title_id . '">' . $section_title . '</h4>';
                                $return .= '<ul>';
                                foreach ($vaardigheid_afraders as $vaardigheid_aanrader):
                                    $return .= '<li>' . get_the_title($vaardigheid_aanrader->ID) . '</li>';
                                endforeach;
                                //	            wp_reset_postdata();
                                $return .= '</ul>';
                                $return .= '</div>';
                            }

                            $return .= '</div>';

                            $return .= '</section>';

                        }

                    endif;


                endforeach;

                wp_reset_postdata();

                $return .= '</div>';

            endif;

            if ($args['getmenu']) {
                return $menuarray;
            }
            elseif ($args['echo']) {
                echo $return;
            }
            else {
                return $return;
            }

        }


        /** ----------------------------------------------------------------------------------------------------
         * Add an archive title
         */
        public function ictu_gc_frontend_vaardigheid_append_aanraders_afraders($args) {

            global $post;

            $defaults = [
              'ID' => 0,
              'titletag' => 'h2',
              'echo' => TRUE,
            ];

            // Parse incoming $args into an array and merge it with $defaults
            $args = wp_parse_args($args, $defaults);

            $theid = $post->ID;
            $return = '';

            $vaardigheid_afraders = get_field('vaardigheid_afraders', $post->ID);
            $vaardigheid_aanraders = get_field('vaardigheid_aanraders', $post->ID);

            if ($vaardigheid_afraders || $vaardigheid_aanraders):

                $return = '<div class="dos-donts">';

                if ($vaardigheid_aanraders) {
                    $section_title = _x('Aanraders', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie');
                    $title_id = sanitize_title($section_title . '-' . $post->ID);

                    $return .= '<div class="dos-donts__column col--dos">';
                    $return .= '<' . $args['titletag'] . ' id="' . $title_id . '">' . $section_title . '</' . $args['titletag'] . '>';
                    $return .= '<ul>';
                    foreach ($vaardigheid_aanraders as $vaardigheid_aanrader):
                        $return .= '<li>' . get_the_title($vaardigheid_aanrader->ID) . '</li>';
                    endforeach;
                    $return .= '</ul>';
                    $return .= '</div>';
                }
                if ($vaardigheid_afraders) {
                    $section_title = _x('Afraders', 'titel op Stap-pagina', 'ictu-gc-posttypes-inclusie');
                    $title_id = sanitize_title($section_title . '-' . $post->ID);

                    $return .= '<div class="dos-donts__column col--donts">';
                    $return .= '<' . $args['titletag'] . ' id="' . $title_id . '">' . $section_title . '</' . $args['titletag'] . '>';
                    $return .= '<ul>';
                    foreach ($vaardigheid_afraders as $vaardigheid_afrader):
                        $return .= '<li>' . get_the_title($vaardigheid_afrader->ID) . '</li>';
                    endforeach;
                    $return .= '</ul>';
                    $return .= '</div>';
                }

                $return .= '</div>'; // .dos-donts

            else:
                $return = 'ictu_gc_frontend_vaardigheid_append_aanraders_afraders: NO';

            endif;

            if ($args['echo']) {
                echo $return;
            }
            else {
                return $return;
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

            $args = [
              'post_type' => ICTU_GC_CPT_DOELGROEP,
              'posts_per_page' => -1,
              'order' => 'ASC',
              'orderby' => 'post_title',

            ];
            $sidebarposts = new WP_query($args);

            if ($sidebarposts->have_posts()) {

                echo '<div class="overview grid grid--col-3 overview--doelgroep">';

                $postcounter = 0;

                while ($sidebarposts->have_posts()) : $sidebarposts->the_post();

                    $postcounter++;

                    $citaat = get_field('facts_citaten', $post->ID);
                    echo ictu_gctheme_card_doelgroep($post, $citaat);

                endwhile;

                echo '</div>';

                wp_reset_query();

            }
        }


        /** ----------------------------------------------------------------------------------------------------
         * Add rewrite rules
         */
        public function ictu_gc_add_rewrite_rules() {

            return '';

        }

        /** ----------------------------------------------------------------------------------------------------
         * Add content to the content
         * \0/
         */
        public function append_content($thecontent) {

            global $post;

            if (is_singular(ICTU_GC_CPT_CITAAT)) {

                if (function_exists('get_field')) {

                    $auteur = get_field('citaat_auteur', $post->ID);

                    if ($auteur) {
                        echo '<p><cite>' . $auteur . '</cite></p>';
                    }

                }

            }
            elseif (is_singular(ICTU_GC_CPT_STAP)) {
            }
            elseif (is_singular(ICTU_GC_CPT_DOELGROEP)) {
            }
            elseif (is_singular(ICTU_GC_CPT_VAARDIGHEDEN)) {
            }
            elseif (is_singular(ICTU_GC_CPT_METHODE)) {

                $classificering = '';
                $theid = get_the_id();

                $classificering = $this->get_classifications($theid, ICTU_GC_CT_TIJD);
                $classificering .= $this->get_classifications($theid, ICTU_GC_CT_MANKRACHT);
                $classificering .= $this->get_classifications($theid, ICTU_GC_CT_KOSTEN);
                $classificering .= $this->get_classifications($theid, ICTU_GC_CT_EXPERTISE);
                $classificering .= $this->get_classifications($theid, ICTU_GC_CT_DEELNEMERS);

                if ($classificering) {

                    $section_title = _x('Classificering', 'titel op methode-pagina', 'ictu-gc-posttypes-inclusie');
                    $title_id = sanitize_title($section_title . '-title');
                    $related_items = get_field('content_block_items');

                    echo '<section aria-labelledby="' . $title_id . '" class="section">';
                    echo '<h2 class="section__title" id="' . $title_id . '">' . $section_title . '</h2>';
                    echo '<dl class="definition-list">';
                    echo $classificering;
                    echo '</dl>';
                    echo '</section>';

                }

            }

            if (is_singular(ICTU_GC_CPT_STAP) || is_singular('page')) {
                //
            }

            return $thecontent;

        }


        /** ----------------------------------------------------------------------------------------------------
         * Prepends a title before the content
         */
        public function ictu_gc_frontend_doelgroep_append_title() {

            global $post;

            $title = sprintf(_x('Over %s', 'Label stappen', 'ictu-gc-posttypes-inclusie'), get_the_title());
            echo '<h2>' . $title . '</h2>';

        }

        /** ----------------------------------------------------------------------------------------------------
         * Prepends a title before the content
         */
        public function ictu_gc_frontend_doelgroep_append_cijfers() {

            global $post;

            $section_title = _x('Cijfers', 'titel op doelgroep-pagina', 'ictu-gc-posttypes-inclusie');
            $title_id = sanitize_title($section_title . '-title');
            $facts_title = get_field('facts_title');
            $facts_description = get_field('facts_description');
            $facts_source_url = get_field('facts_source_url');
            $facts_source_label = get_field('facts_source_label');

            if ($facts_title) {

                echo '<section aria-labelledby="' . $title_id . '" class="facts-figures">';
                echo '<h2 id="' . $title_id . '" class="visuallyhidden">' . $section_title . '</h2>';
                echo '<span class="hugely">' . sanitize_text_field($facts_title) . '</span><span class="source">';
                echo sanitize_text_field($facts_description);
                if ($facts_source_url && $facts_source_label) {
                    echo '<cite>' . _x('Bron:', "Cijfers", "ictu-gc-posttypes-inclusie") . ' ';
                    echo '<a href="' . esc_url($facts_source_url) . '">' . sanitize_text_field($facts_source_label) . '</a></cite>';
                }
                echo '</span></section>';

                // Sorry :(
                echo '<div class="spacer-doelgroep">&nbsp;</div>';

            }


        }


        //========================================================================================================

        /**
         * Display title value for a single vaardigheid page
         *
         * @return void (echo HTML)
         */
        public function ictu_gc_frontend_vaardigheid_append_title() {

            global $post;

            $section_title = get_the_title($post->ID);
            $title_id = sanitize_title($section_title . '-' . $post->ID);
            $vaardigheid_icoon = get_field('vaardigheid_icoon', $post->ID);

            echo '<header class="entry-header"><h1 id="' . $title_id . '" class="' . $vaardigheid_icoon . ' entry-title">' . $section_title . '</h1></header>';

        }


        //========================================================================================================

        /**
         * Write out Table Of Contents (TOC) for a single page
         *
         * @in: $menuitems (array)
         *
         * @return void
         */
        public function ictu_gc_general_construct_toc($menuitems = []) {

            $return = '';
            //			return 'TOC';

            if ($menuitems) {
                // Relative wrapper

                $return .= '<div class="l-content-menu-wrapper">';

                // Absolute
                $return .= '<div class="content-menu open">';
                $return .= '<div class="l-inner">';

                $return .= '<h2 class="content-menu__title">' . _x('Menu', 'Titel voor inhoudsopgave', 'ictu-gc-posttypes-inclusie') . '</h2>';
                $return .= '<ul class="content-menu__menu">';
                foreach ($menuitems as $key => $value) {
                    $return .= '<li class="content-menu__item"><a class="content-menu__link" href="#' . $key . '">' . $value . '</a></li>';//
                }
                $return .= '</ul>' .
                  '</div>' .
                  '<span class="spacer spacer--shade" data-visually-hidden="true"><span class="inner">&nbsp;</span></span>' .
                  '<button class="btn btn--trigger-open"><span class="btn__text">' . _x('Close menu', 'Open / close button menu', 'ictu-gc-posttypes-inclusie') . '</span></button>' .
                  '</div>' .
                  '</div>';

            }

            return $return;


        }


        /** ----------------------------------------------------------------------------------------------------
         * Do actually register the post types we need
         */
        public function ictu_gc_register_post_type() {


            // ---------------------------------------------------------------------------------------------------
            // custom post type voor 'stappen'
            $labels = [
              "name" => _x('Stappen', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Stap', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Stappen', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle stappen', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe stap toevoegen', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Nieuwe stap toevoegen', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Stap bewerken', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe stap', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Stap bekijken', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek een stap', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Niets gevonden', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Niets gevonden', 'stap type', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Stappen', 'Stappen label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "menu_icon" => "dashicons-editor-ol",
              "description" => "",
              "public" => TRUE,
              "publicly_queryable" => TRUE,
              "show_ui" => TRUE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "has_archive" => TRUE,
              "show_in_menu" => TRUE,
              "exclude_from_search" => FALSE,
              "capability_type" => "post",
              "map_meta_cap" => TRUE,
              "hierarchical" => FALSE,
              "rewrite" => ["slug" => ICTU_GC_CPT_STAP, "with_front" => TRUE],
              "query_var" => TRUE,
              "supports" => ["title", "editor", "thumbnail", "excerpt"],
            ];
            register_post_type(ICTU_GC_CPT_STAP, $args);

            // ---------------------------------------------------------------------------------------------------
            // custom post type voor 'doelgroep'

            $labels = [
              "name" => _x('Doelgroepen', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Doelgroep', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Doelgroepen', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle doelgroepen', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe doelgroep toevoegen', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Nieuwe doelgroep toevoegen', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Doelgroep bewerken', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe doelgroep', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Doelgroep bekijken', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek een doelgroep', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Niets gevonden', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Niets gevonden', 'doelgroep type', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Doelgroepen', 'Stappen label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "description" => "",
              "public" => TRUE,
              "publicly_queryable" => TRUE,
              "show_ui" => TRUE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "has_archive" => FALSE,
              "show_in_menu" => TRUE,
              "exclude_from_search" => FALSE,
              "capability_type" => "post",
              "map_meta_cap" => TRUE,
              "hierarchical" => FALSE,
              "rewrite" => [
                "slug" => ICTU_GC_CPT_DOELGROEP,
                "with_front" => TRUE,
              ],
              "query_var" => TRUE,
              "supports" => ["title", "editor", "thumbnail", "excerpt"],
            ];
            register_post_type(ICTU_GC_CPT_DOELGROEP, $args);

            // ---------------------------------------------------------------------------------------------------
            // custom post type voor 'doelgroep'

            $labels = [
              "name" => _x('Citaten', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Citaat', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Citaten', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle citaten', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuw citaat toevoegen', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Nieuw citaat toevoegen', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Citaat bewerken', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuw citaat', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Citaat bekijken', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek een Citaat', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Niets gevonden', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Niets gevonden', 'citaat type', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Citaten', 'Stappen label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "menu_icon" => "dashicons-format-quote",
              "description" => "",
              "public" => TRUE,
              "publicly_queryable" => TRUE,
              "show_ui" => TRUE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "has_archive" => FALSE,
              "show_in_menu" => TRUE,
              "exclude_from_search" => FALSE,
              "capability_type" => "post",
              "map_meta_cap" => TRUE,
              "hierarchical" => FALSE,
              "rewrite" => ["slug" => ICTU_GC_CPT_CITAAT, "with_front" => TRUE],
              "query_var" => TRUE,
              "supports" => ["title", "editor", "thumbnail", "excerpt"],
            ];
            register_post_type(ICTU_GC_CPT_CITAAT, $args);

            // ---------------------------------------------------------------------------------------------------

            // custom post type voor 'vaardigheid'

            $labels = [
              "name" => _x("Vaardigheden", 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Vaardigheid', 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x("Vaardigheden", 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x("Alle vaardigheden", 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe vaardigheid toevoegen', 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Nieuwe vaardigheid toevoegen', 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Vaardigheid bewerken', 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe vaardigheid', 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Vaardigheid bekijken', 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek een vaardigheid', 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Niets gevonden', 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Niets gevonden', 'vaardigheid type', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x("Vaardigheden", "Label Vaardigheden", 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "menu_icon" => "dashicons-image-filter",
              "description" => "",
              "public" => TRUE,
              "publicly_queryable" => TRUE,
              "show_ui" => TRUE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "has_archive" => FALSE,
              "show_in_menu" => TRUE,
              "exclude_from_search" => FALSE,
              "capability_type" => "post",
              "map_meta_cap" => TRUE,
              "hierarchical" => FALSE,
              "rewrite" => [
                "slug" => ICTU_GC_CPT_VAARDIGHEDEN,
                "with_front" => TRUE,
              ],
              "query_var" => TRUE,
              "supports" => ["title", "editor", "thumbnail", "excerpt"],
            ];
            register_post_type(ICTU_GC_CPT_VAARDIGHEDEN, $args);


            // ---------------------------------------------------------------------------------------------------
            // custom post type voor 'Aanrader'

            $labels = [
              "name" => _x("Aanraders", 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Aanrader', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x("Aanraders", 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x("Alle aanraders", 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe aanrader toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Nieuwe aanrader toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Aanrader bewerken', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe aanrader', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('aanrader bekijken', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek een aanrader', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x("Aanraders", "Label tips", 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "menu_icon" => "dashicons-yes",
              "description" => "",
              "public" => TRUE,
              "publicly_queryable" => TRUE,
              "show_ui" => TRUE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "has_archive" => FALSE,
              "show_in_menu" => TRUE,
              "exclude_from_search" => FALSE,
              "capability_type" => "post",
              "map_meta_cap" => TRUE,
              "hierarchical" => FALSE,
              "rewrite" => [
                "slug" => ICTU_GC_CPT_AANRADER,
                "with_front" => TRUE,
              ],
              "query_var" => TRUE,
              "supports" => ["title", "excerpt"],
            ];
            register_post_type(ICTU_GC_CPT_AANRADER, $args);


            // ---------------------------------------------------------------------------------------------------

            // custom post type voor 'Afrader'

            $labels = [
              "name" => _x("Afraders", 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Afrader', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x("Afraders", 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x("Alle afraders", 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe afrader toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Nieuwe afrader toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Afrader bewerken', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe afrader', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('afrader bekijken', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek een afrader', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x("Afraders", "Label tips", 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "menu_icon" => "dashicons-no",
              "description" => "",
              "public" => TRUE,
              "publicly_queryable" => TRUE,
              "show_ui" => TRUE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "has_archive" => FALSE,
              "show_in_menu" => TRUE,
              "exclude_from_search" => FALSE,
              "capability_type" => "post",
              "map_meta_cap" => TRUE,
              "hierarchical" => FALSE,
              "rewrite" => [
                "slug" => ICTU_GC_CPT_AFRADER,
                "with_front" => TRUE,
              ],
              "query_var" => TRUE,
              "supports" => ["title", "excerpt"],
            ];
            register_post_type(ICTU_GC_CPT_AFRADER, $args);

            // ---------------------------------------------------------------------------------------------------

            // custom post type voor 'Methode'

            $labels = [
              "name" => _x('Methodes', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Methode', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Methodes', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle methodes', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe methode toevoegen', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Nieuwe methode toevoegen', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Methode bewerken', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe methode', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Methode bekijken', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek een Methode', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Niets gevonden', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Niets gevonden', 'Methode type', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Methodes', 'Methodes label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "menu_icon" => "dashicons-book-alt",
              "description" => "",
              "public" => TRUE,
              "publicly_queryable" => TRUE,
              "show_ui" => TRUE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "has_archive" => FALSE,
              "show_in_menu" => TRUE,
              "exclude_from_search" => FALSE,
              "capability_type" => "post",
              "map_meta_cap" => TRUE,
              "hierarchical" => FALSE,
              "rewrite" => [
                "slug" => ICTU_GC_CPT_METHODE,
                "with_front" => TRUE,
              ],
              "query_var" => TRUE,
              "supports" => ["title", "editor", "excerpt"],
            ];

            register_post_type(ICTU_GC_CPT_METHODE, $args);
            // ---------------------------------------------------------------------------------------------------

            // custom post type voor 'Tip'

            $labels = [
              "name" => _x('Tips', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Tip', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Tips', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle tips', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe tip toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Nieuwe tip toevoegen', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Tip bewerken', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe tip', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Tip bekijken', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek een tip', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Niets gevonden', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Tips', 'Tip type', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "menu_icon" => "dashicons-book-alt",
              "description" => "",
              "public" => TRUE,
              "publicly_queryable" => TRUE,
              "show_ui" => TRUE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "has_archive" => FALSE,
              "show_in_menu" => TRUE,
              "exclude_from_search" => FALSE,
              "capability_type" => "post",
              "map_meta_cap" => TRUE,
              "hierarchical" => FALSE,
              "rewrite" => [
                "slug" => ICTU_GC_CPT_PROCESTIP,
                "with_front" => TRUE,
              ],
              "query_var" => TRUE,
              "supports" => ["title", "editor", "excerpt", "thumbnail"],
            ];

            register_post_type(ICTU_GC_CPT_PROCESTIP, $args);


            // ---------------------------------------------------------------------------------------------------
            // tijd taxonomie voor methode
            $labels = [
              "name" => _x('Tijd', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Tijd', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
            ];

            $labels = [
              "name" => _x('Tijd', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Tijd', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Tijd', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle tijdsinschattingen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe tijdsinschatting toevoegen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Voeg nieuwe tijdsinschatting toe', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Bewerk tijdsinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe tijdsinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Bekijk tijdsinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek tijdsinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Geen tijdsinschattingen gevonden', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Geen tijdsinschattingen gevonden in de prullenbak', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Tijd', 'Digibeter label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "public" => TRUE,
              "hierarchical" => TRUE,
              "label" => _x('Tijd', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "show_ui" => TRUE,
              "show_in_menu" => TRUE,
              "show_in_nav_menus" => TRUE,
              "query_var" => TRUE,
              "rewrite" => ['slug' => ICTU_GC_CT_TIJD, 'with_front' => TRUE,],
              "show_admin_column" => FALSE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "show_in_quick_edit" => TRUE,
            ];
            register_taxonomy(ICTU_GC_CT_TIJD, [ICTU_GC_CPT_METHODE], $args);

            // ---------------------------------------------------------------------------------------------------
            // Personeel taxonomie voor methode
            $labels = [
              "name" => _x('Personeel', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Personeel', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
            ];

            $labels = [
              "name" => _x('Personeel', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Personeel', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Personeel', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle personeelsinschattingen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe personeelsinschatting toevoegen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Voeg nieuwe personeelsinschatting toe', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Bewerk personeelsinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe personeelsinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Bekijk personeelsinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek personeelsinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Geen personeelsinschattingen gevonden', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Geen personeelsinschattingen gevonden in de prullenbak', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Personeel', 'Digibeter label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "public" => TRUE,
              "hierarchical" => TRUE,
              "label" => _x('Personeel', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "show_ui" => TRUE,
              "show_in_menu" => TRUE,
              "show_in_nav_menus" => TRUE,
              "query_var" => TRUE,
              "rewrite" => [
                'slug' => ICTU_GC_CT_MANKRACHT,
                'with_front' => TRUE,
              ],
              "show_admin_column" => FALSE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "show_in_quick_edit" => TRUE,
            ];
            register_taxonomy(ICTU_GC_CT_MANKRACHT, [ICTU_GC_CPT_METHODE], $args);

            // ---------------------------------------------------------------------------------------------------
            // Kosten taxonomie voor methode
            $labels = [
              "name" => _x('Kosten', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Kosten', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
            ];

            $labels = [
              "name" => _x('Kosten', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Kosten', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Kosten', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle kostensinschattingen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe kostensinschatting toevoegen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Voeg nieuwe kostensinschatting toe', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Bewerk kostensinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe kostensinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Bekijk kostensinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek kostensinschatting', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Geen kostensinschattingen gevonden', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Geen kostensinschattingen gevonden in de prullenbak', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Kosten', 'Digibeter label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "public" => TRUE,
              "hierarchical" => TRUE,
              "label" => _x('Kosten', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "show_ui" => TRUE,
              "show_in_menu" => TRUE,
              "show_in_nav_menus" => TRUE,
              "query_var" => TRUE,
              "rewrite" => ['slug' => ICTU_GC_CT_KOSTEN, 'with_front' => TRUE,],
              "show_admin_column" => FALSE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "show_in_quick_edit" => TRUE,
            ];
            register_taxonomy(ICTU_GC_CT_KOSTEN, [ICTU_GC_CPT_METHODE], $args);

            // ---------------------------------------------------------------------------------------------------
            // Expertise taxonomie voor methode
            $labels = [
              "name" => _x('Expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
            ];

            $labels = [
              "name" => _x('Expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle expertises', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe expertise toevoegen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Voeg nieuwe expertise toe', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Bewerk expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Bekijk expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Geen expertises gevonden', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Geen expertises gevonden in de prullenbak', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Expertise', 'Digibeter label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "public" => TRUE,
              "hierarchical" => TRUE,
              "label" => _x('Expertise', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "show_ui" => TRUE,
              "show_in_menu" => TRUE,
              "show_in_nav_menus" => TRUE,
              "query_var" => TRUE,
              "rewrite" => [
                'slug' => ICTU_GC_CT_EXPERTISE,
                'with_front' => TRUE,
              ],
              "show_admin_column" => FALSE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "show_in_quick_edit" => TRUE,
            ];
            register_taxonomy(ICTU_GC_CT_EXPERTISE, [ICTU_GC_CPT_METHODE], $args);

            // ---------------------------------------------------------------------------------------------------
            // deelnemers taxonomie voor methode
            $labels = [
              "name" => _x('Deelnemers', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Deelnemers', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Deelnemers', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x('Alle deelnemers', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuwe deelnemers toevoegen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Voeg nieuwe deelnemers toe', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Bewerk deelnemers', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuwe deelnemers', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Bekijk deelnemers', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek deelnemers', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Geen deelnemers gevonden', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Geen deelnemers gevonden in de prullenbak', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Deelnemers', 'Digibeter label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "public" => TRUE,
              "hierarchical" => TRUE,
              "label" => _x('Deelnemers', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "show_ui" => TRUE,
              "show_in_menu" => TRUE,
              "show_in_nav_menus" => TRUE,
              "query_var" => TRUE,
              "rewrite" => [
                'slug' => ICTU_GC_CT_DEELNEMERS,
                'with_front' => TRUE,
              ],
              "show_admin_column" => FALSE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "show_in_quick_edit" => TRUE,
            ];
            register_taxonomy(ICTU_GC_CT_DEELNEMERS, [ICTU_GC_CPT_METHODE], $args);

            // ---------------------------------------------------------------------------------------------------
            // Onderwerpen taxonomie voor aanraders en afraders
            $labels = [
              "name" => _x('Onderwerpen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "singular_name" => _x('Onderwerp', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "menu_name" => _x('Onderwerp', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "all_items" => _x("Alles onder 'Onderwerpen'", 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new" => _x('Nieuw item toevoegen', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "add_new_item" => _x('Voeg nieuw item toe', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "edit_item" => _x('Bewerk item', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "new_item" => _x('Nieuw item', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "view_item" => _x('Bekijk item', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "search_items" => _x('Zoek item', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found" => _x('Geen items gevonden', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "not_found_in_trash" => _x('Geen items gevonden in de prullenbak', 'Taxonomie', 'ictu-gc-posttypes-inclusie'),
              "featured_image" => _x('Featured image', 'plaatje', 'ictu-gc-posttypes-inclusie'),
              "archives" => _x('Archives', 'archief', 'ictu-gc-posttypes-inclusie'),
              "uploaded_to_this_item" => _x('Uploaded media', 'media', 'ictu-gc-posttypes-inclusie'),
            ];

            $args = [
              "label" => _x('Onderwerpen', 'Aanrader label', 'ictu-gc-posttypes-inclusie'),
              "labels" => $labels,
              "public" => TRUE,
              "hierarchical" => TRUE,
              "label" => _x('Onderwerpen', 'Vaardigheden', 'ictu-gc-posttypes-inclusie'),
              "show_ui" => TRUE,
              "show_in_menu" => TRUE,
              "show_in_nav_menus" => TRUE,
              "query_var" => TRUE,
              "rewrite" => [
                'slug' => ICTU_GC_CT_ONDERWERP_TIP,
                'with_front' => TRUE,
              ],
              "show_admin_column" => FALSE,
              "show_in_rest" => FALSE,
              "rest_base" => "",
              "show_in_quick_edit" => TRUE,
            ];
            register_taxonomy(ICTU_GC_CT_ONDERWERP_TIP, [
              ICTU_GC_CPT_AANRADER,
              ICTU_GC_CPT_AFRADER,
            ], $args);


            // ---------------------------------------------------------------------------------------------------

            // clean up after ourselves
            flush_rewrite_rules();

        }

        //

        /** ----------------------------------------------------------------------------------------------------
         * filter the breadcrumb
         */
        public function get_classifications($theid = '', $taxonomy = '', $wrapper1 = 'dt', $wrapper2 = 'dd') {

            $return = '';

            if ($theid && $taxonomy) {

                $args = [
                  'name' => $taxonomy,
                ];
                $output = 'objects'; // or names

                $taxobject = get_taxonomies($args, $output);
                $tax_info = array_values($taxobject)[0];
                $return = '<' . $wrapper1 . '>' . $tax_info->label . '</' . $wrapper1 . '> <' . $wrapper2 . '>';
                $term_list = wp_get_post_terms($theid, $taxonomy, ["fields" => "all"]);
                $counter = 0;

                foreach ($term_list as $term_single) {

                    $counter++;
                    $term_link = get_term_link($term_single);

                    if ($counter > 1) {
                        $return .= ', '; //do something here
                    }
                    //				$return .= '<a href="' . esc_url( $term_link ) . '">' . $term_single->name . '</a>';
                    $return .= $term_single->name;
                }

                $return .= '</' . $wrapper2 . '>';

            }

            return $return;

        }


        /** ----------------------------------------------------------------------------------------------------
         * filter the breadcrumb
         */
        public function filter_breadcrumb($crumb = '', $args = '') {

            global $post;

            $span_before_start = '<span class="breadcrumb-link-wrap" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
            $span_between_start = '<span itemprop="name">';
            $span_before_end = '</span>';

            if (is_singular(ICTU_GC_CPT_CITAAT) || is_singular(ICTU_GC_CPT_STAP)) {

                $crumb = get_the_title(get_the_id());

            }
            // 'doelgroep'

            if (
              is_singular(ICTU_GC_CPT_DOELGROEP) ||
              is_singular(ICTU_GC_CPT_VAARDIGHEDEN) ||
              is_singular(ICTU_GC_CPT_METHODE) ||
              is_singular(ICTU_GC_CPT_PROCESTIP)
            ) {

                $crumb = '';
                $overview_page = '';

                if (is_singular(ICTU_GC_CPT_DOELGROEP)) {
                    $overview_page = get_field('themesettings_inclusie_doelgroeppagina', 'option');    // code hier
                }
                elseif (is_singular(ICTU_GC_CPT_VAARDIGHEDEN)) {
                    $overview_page = get_field('themesettings_inclusie_vaardighedenpagina', 'option');    // code hier
                }
                elseif (is_singular(ICTU_GC_CPT_METHODE)) {
                    $overview_page = get_field('themesettings_inclusie_methodepagina', 'option');    // code hier
                }
                elseif (is_singular(ICTU_GC_CPT_PROCESTIP)) {
                    $overview_page = get_field('themesettings_inclusie_tipspagina', 'option');    // code hier
                }

                if ($overview_page) {
                    $crumb = ictu_gctheme_breadcrumbstring($overview_page, $args);
                }

            }


            return $crumb;

        }

        /** ----------------------------------------------------------------------------------------------------
         * add extra class to page template .entry
         */
        function add_class_inleiding_to_entry($attributes) {
            $attributes['class'] .= ' inleiding';
            return $attributes;
        }

        /** ----------------------------------------------------------------------------------------------------
         * add default favicon
         */
        function custom_favicon($icon) {
            $icon = ICTU_GC_ASSETS_URL . 'images/favicon.png';
            return $icon;
        }


    }

endif;


//========================================================================================================

/** ----------------------------------------------------------------------------------------------------
 * Initialise translations
 */
function rijkshuisstijlposttypes_load_plugin_textdomain() {

	load_plugin_textdomain( "ictu-gc-posttypes-inclusie", false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}

add_action( 'plugins_loaded', 'rijkshuisstijlposttypes_load_plugin_textdomain' );

//========================================================================================================


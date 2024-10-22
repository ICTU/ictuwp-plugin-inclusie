<?php

/*
// Gebruiker Centraal - inclusie.acf-definitions.php
// ----------------------------------------------------------------------------------
// ACF definities voor inclusie plugin
// ----------------------------------------------------------------------------------
// @package   ictu-gc-posttypes-inclusie
// @author    Paul van Buuren
// @license   GPL-2.0+
// @version   1.1.3
// @desc.     Moved sections backend code and styling for related links and content to the gebruiker-centraal theme.
// @link      https://github.com/ICTU/ictuwp-plugin-inclusie
 */


// Get icons from stepchart JSON
function getIcons() {
    $icon_array = [];
    $icon_list = file_get_contents( get_stylesheet_directory()  .'/images/svg/stepchart/stepchart_icons.json');
    $icon_list = json_decode($icon_list, true);

    foreach ($icon_list as $key => $icon) {
        $icon_array[$key] =
          '<span style="display: inline-flex; margin-top: 10px; align-items: center; position: relative; min-height: 40px; padding-left: 45px">'.
          '<svg class="icon icon--medium icon--stepchart" aria-hidden="true" focusable="false" style="width: 30px; height: 40px; position: absolute; left: 7px; top: 0;"> '.
          '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="'. get_stylesheet_directory_uri()  .'/images/svg/stepchart/defs/svg/sprite.defs.svg#'. $key .'"></use> '.
          '</svg>' .
          '<span class="label-text">'. $icon .'</span></span>';
    }

    return $icon_array;
}


if( ! function_exists('ictu_gc_inclusie_initialize_acf_fields') ) {


	function ictu_gc_inclusie_initialize_acf_fields() {
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
						'key' => 'field_5cdbde536c7d1',
						'label' => 'doelgroep_avatar',
						'name' => 'doelgroep_avatar',
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
							'poppetje-1' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-1-small.png"> Avatar',
							'poppetje-2' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-2-small.png"> poppetje-2',
							'poppetje-3' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-3-small.png"> poppetje-3',
							'poppetje-4' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-4-small.png"> poppetje-4',
							'poppetje-5' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-5-small.png"> poppetje-5',
							'poppetje-6' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-6-small.png"> poppetje-6',
							'poppetje-7' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-7-small.png"> poppetje-7',
							'poppetje-8' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-8-small.png"> poppetje-8',
							'poppetje-9' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-9-small.png"> poppetje-9',
							'poppetje-10' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-10-small.png"> poppetje-10',
							'poppetje-11' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-11-small.png"> poppetje-11',
							'poppetje-12' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-12-small.png"> poppetje-12',
							'poppetje-13' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-13-small.png"> poppetje-13',
							'poppetje-14' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-14-small.png"> poppetje-14',
							'poppetje-15' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/avatar/poppetje-15-small.png"> poppetje-15',
						),
						'allow_null' => 0,
						'other_choice' => 0,
						'default_value' => '',
						'layout' => 'vertical',
						'return_format' => 'value',
						'save_other_choice' => 0,
					),
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
						'label' => 'Titel boven facts & figures',
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
						'label' => 'Beschrijving facts & figures',
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
						'label' => 'URL facts & figures',
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
						'label' => 'Linktekst URL facts & figures',
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
							'value' => GC_INCLUSIE_DOELGROEP_CPT,
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


			//------------------------------------------------------------------------------------------------
			// velden voor doelgroep: vaardigheden

			acf_add_local_field_group(array(
				'key' => 'group_5c8fd6bacf265',
				'title' => 'Doelgroep: vaardigheden',
				'fields' => array(
					array(
						'key' => 'field_5d8915ca6e188',
						'label' => 'Titel',
						'name' => 'doelgroep_vaardigheden_titel',
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
						'key' => 'field_5d8915e2dc208',
						'label' => 'Inleiding',
						'name' => 'doelgroep_vaardigheden_inleiding',
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
						'key' => 'field_5c924f598eeab',
						'label' => 'Vaardigheden',
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
							'value' => GC_INCLUSIE_DOELGROEP_CPT,
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

			//------------------------------------------------------------------------------------------------
			// velden voor doelgroep: tips

			acf_add_local_field_group(array(
				'key' => 'group_5d8901c501a1f',
				'title' => 'Doelgroep: tips',
				'fields' => array(
					array(
						'key' => 'field_5d8910a036605',
						'label' => 'Titel',
						'name' => 'doelgroep_tips_titel',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => 'Tips',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5d8910c61238d',
						'label' => 'Inleiding',
						'name' => 'doelgroep_tips_inleiding',
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
						'key' => 'field_5d8901c513ed9',
						'label' => 'Tips',
						'name' => 'doelgroep_tips',
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
							0 => 'procestip',
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
							'value' => GC_INCLUSIE_DOELGROEP_CPT,
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




			//------------------------------------------------------------------------------------------------
			// velden voor stap
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
						'key' => 'field_5cdb1d4374d09',
						'label' => 'Icoontje',
						'name' => 'stap_icon',
						'type' => 'radio',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => getIcons(),
						'allow_null' => 0,
						'other_choice' => 0,
						'default_value' => 'identificeer',
						'layout' => 'vertical',
						'return_format' => 'value',
						'save_other_choice' => 0,
					),
					array(
						'key' => 'field_5c90bae01c857',
						'label' => 'Inleiding',
						'name' => 'stap_inleiding',
						'type' => 'wysiwyg',
						'instructions' => 'korte inleiding bij deze stap. Wordt getoond in het cirkelschema op de homepage.',
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
						'key' => 'field_5d7245be8ffc0',
						'label' => 'Titel bij de methodes',
						'name' => 'stap_methodes_titel',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => 'Methodes',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5ce3d0e2f8917',
						'label' => 'Inleiding bij de methodes',
						'name' => 'stap_methode_inleiding',
						'type' => 'wysiwyg',
						'instructions' => 'Korte inleiding bij methoden.',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => 'Dit is een selectie van methoden, technieken en instrumenten die je in kunt zetten bij het uitvoeren van deze stap. Soms gaat het om standaardmethoden die worden ingezet voor het uitvoeren van een ontwerptraject waarbij de gebruiker centraal staat. Andere methoden richten zich specifiek op inclusie.',
						'tabs' => 'all',
						'toolbar' => 'basic',
						'media_upload' => 1,
						'delay' => 0,
					),
					array(
						'key' => 'field_5c8fde5259d46',
						'label' => 'Methodes',
						'name' => 'stap_methodes',
						'type' => 'relationship',
						'instructions' => 'Kies de bij deze stap horende methodes.',
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
					array(
						'key' => 'field_5d84d94b05b52',
						'label' => 'Titel boven het blok met tips',
						'name' => 'stap_procestips_sectiontitle',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => 'Procestips',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5d84dcb9d1473',
						'label' => 'Bijbehorende procestips',
						'name' => 'stap_procestips',
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
							0 => 'procestip',
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
						'key' => 'field_5d84d4b652e0e',
						'label' => 'Tips Optimaal Digitaal',
						'name' => 'stap_tips_optimaal_digitaal_sectiontitle',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => 'Optimaal Digitaal tips',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_5d84d3eed46b2',
						'label' => 'Bijbehorende tips Optimaal Digitaal',
						'name' => 'stap_tips_optimaal_digitaal',
						'type' => 'repeater',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => 'field_5d84d439d46b4',
						'min' => 0,
						'max' => 0,
						'layout' => 'row',
						'button_label' => 'Nieuw tip toevoegen',
						'sub_fields' => array(
							array(
								'key' => 'field_5d84d439d46b4',
								'label' => 'Titel',
								'name' => 'stap_tip_optimaal_digitaal_titel',
								'type' => 'text',
								'instructions' => 'De titel van de Optimaal Digitaal-tip',
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
								'key' => 'field_5d84d468d46b5',
								'label' => 'Tip-nummer',
								'name' => 'stap_tip_optimaal_digitaal_tipnummer',
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
								'key' => 'field_5d84d423d46b3',
								'label' => 'URL',
								'name' => 'stap_tip_optimaal_digitaal_url',
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
								'key' => 'field_5d84d472d46b6',
								'label' => 'Tip-thema',
								'name' => 'stap_tip_optimaal_digitaal_tipthema',
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
									'commitment' => 'Commitment',
									'gebruiksgemak' => 'Gebruiksgemak',
									'informatieveiligheid' => 'Informatieveiligheid',
									'kanaalsturing' => 'Kanaalsturing',
									'procesaanpak' => 'Procesaanpak',
									'samenwerking' => 'Samenwerking',
									'inclusie' => 'Inclusie',
								),
								'allow_null' => 0,
								'other_choice' => 0,
								'default_value' => 'inclusie',
								'layout' => 'vertical',
								'return_format' => 'value',
								'save_other_choice' => 0,
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


			//------------------------------------------------------------------------------------------------
			// velden voor een vaardigheid
			acf_add_local_field_group(array(
				'key' => 'group_5c9398df21747',
				'title' => 'Vaardigheid: icoon en aan- en afraders',
				'fields' => array(
					array(
						'key' => 'field_5dad881de7774',
						'label' => 'Icoon bij deze vaardigheid',
						'name' => 'vaardigheid_icoon',
						'type' => 'radio',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'aandacht' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/aandacht.png"> Aandacht',
							'angst-voor-technologie' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/angst-voor-technologie.png"> Angst voor technologie',
							'auditief' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/auditief.png"> Auditief',
							'creativiteit' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/creativiteit.png"> Creativiteit',
							'denken' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/denken.png"> Denken',
							'geheugen' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/geheugen.png"> Geheugen',
							'leren' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/leren.png"> Leren',
							'motivatie' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/motivatie.png"> Motivatie',
							'non-verbale-boodschappen' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/non-verbale-boodschappen.png"> Non-verbale boodschappen',
							'perceptuele-snelheid' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/perceptuele-snelheid.png"> Perceptuele snelheid',
							'privacy' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/privacy.png"> Privacy',
							'problemen-oplossen' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/problemen-oplossen.png"> Problemen oplossen',
							'psychosociaal' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/psychosociaal.png"> Psychosociaal',
							'rekenen' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/rekenen.png"> Rekenen',
							'ruimtelijke-orientatie' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/ruimtelijke-orientatie.png"> Ruimtelijke oriëntatie',
							'self-efficacy' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/self-efficacy.png"> Self efficacy',
							'snelheid' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/snelheid.png"> Snelheid',
							'taal' => '<img src="/wp-content/plugins/ictuwp-plugin-inclusie/images/icons/vaardigheden/taal.png"> Taal',
						),
						'allow_null' => 0,
						'other_choice' => 0,
						'default_value' => 'aandacht',
						'layout' => 'vertical',
						'return_format' => 'value',
						'save_other_choice' => 0,
					),
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



			//--------------------------------------------------------------------------------------------
			// Instellingen voor inclusiewebsite / theme settings
			acf_add_local_field_group(array(
				'key' => 'group_5d726d93a46f2',
				'title' => 'Theme-instellingen voor inclusiewebsite',
				'fields' => array(
					array(
						'key' => 'field_5d726daa06090',
						'label' => 'Pagina met doelgroepoverzicht',
						'name' => 'themesettings_inclusie_doelgroeppagina',
						'type' => 'post_object',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'post_type' => array(
							0 => 'page',
						),
						'taxonomy' => '',
						'allow_null' => 0,
						'multiple' => 0,
						'return_format' => 'object',
						'ui' => 1,
					),
					array(
						'key' => 'field_5d726daa06091',
						'label' => 'Pagina met vaardighedenoverzicht',
						'name' => 'themesettings_inclusie_vaardighedenpagina',
						'type' => 'post_object',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'post_type' => array(
							0 => 'page',
						),
						'taxonomy' => '',
						'allow_null' => 0,
						'multiple' => 0,
						'return_format' => 'object',
						'ui' => 1,
					),
					array(
						'key' => 'field_5d726daa06092',
						'label' => 'Pagina met methodes-overzicht',
						'name' => 'themesettings_inclusie_methodepagina',
						'type' => 'post_object',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'post_type' => array(
							0 => 'page',
						),
						'taxonomy' => '',
						'allow_null' => 0,
						'multiple' => 0,
						'return_format' => 'object',
						'ui' => 1,
					),
					array(
						'key' => 'field_5d726daa06093',
						'label' => 'Pagina met tips-overzicht',
						'name' => 'themesettings_inclusie_tipspagina',
						'type' => 'post_object',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'post_type' => array(
							0 => 'page',
						),
						'taxonomy' => '',
						'allow_null' => 0,
						'multiple' => 0,
						'return_format' => 'object',
						'ui' => 1,
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'options_page',
							'operator' => '==',
							'value' => 'instellingen',
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


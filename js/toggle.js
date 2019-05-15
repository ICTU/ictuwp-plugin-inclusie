
/*
// Gebruiker Centraal - toggle.js
// ----------------------------------------------------------------------------------
// JS voor accordions op homepage
// ----------------------------------------------------------------------------------
// @package   	ictu-gc-posttypes-inclusie
// @author    	Paul van Buuren
// @license   	GPL-2.0+
// @version   	0.0.3a
// @credits		Scott Vinkle - see: https://codepen.io/svinkle/pen/mKfru
//				via https://a11yproject.com/patterns.html
// @desc.     	First code for prototype.
// @link      	https://github.com/ICTU/Gebruiker-Centraal---Inclusie---custom-post-types-taxonomies
 */

(function (document, window, undefined) {
	
	'use strict';

	// Vars
	var toggleSection	= jQuery('#home-chart'),
		toggleButton	= jQuery('.js-faq-question'),
		toggleTextinfo	= jQuery('.js-faq-answer'),
		showOneAnswerAtATime = true;
	
	/**
	* Save section focus
	*/
	var saveFocus = function (elem, thisSectiontoggleButtons) {
		
		// Reset other tab attributes
		thisSectiontoggleButtons.each(function () {
			jQuery(this).attr('tabindex', '-1');
			jQuery(this).attr('aria-selected', 'false');
		});
		
		// Set this tab attributes
		elem.attr({
			'tabindex': '0',
			'aria-selected': 'true'
		});
		
	};
	
	/**
	* Show answer on click
	*/
	var doShowSection = function (elem, thisSectiontoggleButtons) {
		var thisSection = elem.next();
		
		// Save focus
		saveFocus(elem, thisSectiontoggleButtons);
		
		// Set this tab attributes
		if (thisSection.hasClass('active')) {
			// Hide answer
			thisSection.removeClass('active');      
			elem.attr('aria-expanded', 'false');      
			thisSection.attr('aria-hidden', 'true');
		} else {
			if (showOneAnswerAtATime) {
				// Hide all answers
				toggleTextinfo.removeClass('active').attr('aria-hidden', 'true');
				toggleButton.attr('aria-expanded', 'false');
			}
			
			// Show answer
			thisSection.addClass('active');      
			elem.attr('aria-expanded', 'true');      
			thisSection.attr('aria-hidden', 'false');
		}
	};
	
	/**
	* Keyboard interaction
	*/
	var keyboardInteraction	= function (elem, e, thisSectiontoggleButtons) {
		var keyCode			= e.which,
		nextSection 		= elem.next().next().is('section.step') ? elem.next().next() : false,
		previousSection 	= elem.prev().prev().is('section.step') ? elem.prev().prev() : false,
		firstSection 		= elem.parent().find('section.step:first'),
		lastSection 		= elem.parent().find('section.step:last');
		
	switch(keyCode) {
		// Left/Up
		case 37:
		case 38:
			e.preventDefault();
			e.stopPropagation();
			
			// Check for previous section
			if (!previousSection) {
				// No previous, set focus on last section
				lastSection.focus();
			} else {
				// Move focus to previous section
				previousSection.focus();
			}
			
			break;
		
		// Right/Down
		case 39:
		case 40:
			e.preventDefault();
			e.stopPropagation();
			
			// Check for next section
			if (!nextSection) {
				// No next, set focus on first section
				firstSection.focus();
			} else {
				// Move focus to next section
				nextSection.focus();
			}
			
			break;
		
		// Home
		case 36:
			e.preventDefault();
			e.stopPropagation();
			
			// Set focus on first section
			firstSection.focus();
			break;
		
		// End
		case 35:
			e.preventDefault();
			e.stopPropagation();
			
			// Set focus on last section
			lastSection.focus();
			break;
		
		// Enter/Space
		case 13:
		case 32:
			e.preventDefault();
			e.stopPropagation();
			
			// Show answer content
			doShowSection(elem, thisSectiontoggleButtons);
			break;
		}
		
	};
	
	/**
	* On load, setup roles and initial properties
	*/
	
	// Each FAQ section
	toggleButton.each(function (i) {
		jQuery(this).attr({
			'id': 'faq-question-' + i,
			'role': 'tab',
			'aria-controls': 'faq-answer-' + i,
			'aria-expanded': 'false',
			'aria-selected': 'false',
			'tabindex': '-1'
		});
	});
	
	// Each FAQ Answer
	toggleTextinfo.each(function (i) {
		jQuery(this).attr({
			'id': 'faq-answer-' + i,
			'role': 'tabpanel',
			'aria-labelledby': 'faq-question-' + i,
			'aria-hidden': 'true'
		});
	});
	
	// Each FAQ Section
	toggleSection.each(function () {
		var $this = jQuery(this),
		thisSectiontoggleButtons = $this.find('.js-faq-question');
		
		// Set section attributes
		$this.attr({
			'role': 'tablist',
			'aria-multiselectable': 'true'
		});
		
		thisSectiontoggleButtons.each(function (i) {
			var $this = jQuery(this);
			
			// Make first tab clickable
			if (i === 0) {
				$this.attr('tabindex', '0');
			}
			
			// Click event
			$this.on('click', function () {
				doShowSection(jQuery(this), thisSectiontoggleButtons);
			});
			
			// Keydown event
			$this.on('keydown', function (e) {
				keyboardInteraction(jQuery(this), e, thisSectiontoggleButtons);
			});
			
			// Focus event
			$this.on('focus', function () {
				saveFocus(jQuery(this), thisSectiontoggleButtons);
			});
		});
	});
	
})(document, window  );

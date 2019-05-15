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
!function(e,a,t){"use strict";var r=jQuery("#home-chart"),i=jQuery(".js-faq-question"),n=jQuery(".js-faq-answer"),s=function(e,a){a.each(function(){jQuery(this).attr("tabindex","-1"),jQuery(this).attr("aria-selected","false")}),e.attr({tabindex:"0","aria-selected":"true"})},o=function(e,a){var t=e.next();s(e,a),t.hasClass("active")?(t.removeClass("active"),e.attr("aria-expanded","false"),t.attr("aria-hidden","true")):(n.removeClass("active").attr("aria-hidden","true"),i.attr("aria-expanded","false"),t.addClass("active"),e.attr("aria-expanded","true"),t.attr("aria-hidden","false"))};i.each(function(e){jQuery(this).attr({id:"faq-question-"+e,role:"tab","aria-controls":"faq-answer-"+e,"aria-expanded":"false","aria-selected":"false",tabindex:"-1"})}),n.each(function(e){jQuery(this).attr({id:"faq-answer-"+e,role:"tabpanel","aria-labelledby":"faq-question-"+e,"aria-hidden":"true"})}),r.each(function(){var e=jQuery(this),a=e.find(".js-faq-question");e.attr({role:"tablist","aria-multiselectable":"true"}),a.each(function(e){var t=jQuery(this);0===e&&t.attr("tabindex","0"),t.on("click",function(){o(jQuery(this),a)}),t.on("keydown",function(e){!function(e,a,t){var r=a.which,i=!!e.next().next().is("section.step")&&e.next().next(),n=!!e.prev().prev().is("section.step")&&e.prev().prev(),s=e.parent().find("section.step:first"),c=e.parent().find("section.step:last");switch(r){case 37:case 38:a.preventDefault(),a.stopPropagation(),n?n.focus():c.focus();break;case 39:case 40:a.preventDefault(),a.stopPropagation(),i?i.focus():s.focus();break;case 36:a.preventDefault(),a.stopPropagation(),s.focus();break;case 35:a.preventDefault(),a.stopPropagation(),c.focus();break;case 13:case 32:a.preventDefault(),a.stopPropagation(),o(e,t)}}(jQuery(this),e,a)}),t.on("focus",function(){s(jQuery(this),a)})})})}(document,window);
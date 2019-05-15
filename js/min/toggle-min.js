/*
// Gebruiker Centraal - toggle.js
// ----------------------------------------------------------------------------------
// JS voor accordions op homepage
// ----------------------------------------------------------------------------------
// @package   	ictu-gc-posttypes-inclusie
// @author    	Paul van Buuren
// @license   	GPL-2.0+
// @version   0.0.5
// @desc.     CSS-bugfixes; tabindex-gerommel uit JS weggehaald.
// @credits		Scott Vinkle - see: https://codepen.io/svinkle/pen/mKfru
//				via https://a11yproject.com/patterns.html
// @link      	https://github.com/ICTU/Gebruiker-Centraal---Inclusie---custom-post-types-taxonomies
 */
!function(e,a,t){"use strict";var r=jQuery("#home-chart"),s=jQuery(".js-faq-question"),i=jQuery(".js-faq-answer"),n=function(e,a){a.each(function(){jQuery(this).attr("aria-selected","false")}),e.attr({"aria-selected":"true"})},o=function(e,a){var t=e.next();if(n(e,a),t.hasClass("active"))t.removeClass("active"),e.attr("aria-expanded","false"),t.attr("aria-hidden","true"),t.attr("style","");else{i.removeClass("active").attr("aria-hidden","true"),s.attr("aria-expanded","false");var r=-1*(t.height()+210);t.addClass("active"),e.attr("aria-expanded","true"),t.attr("aria-hidden","false"),t.attr("style","transform: translateY("+r+"px)")}},c=function(e,a,t){var r=a.which,s=!!e.next().next().is("section.step")&&e.next().next(),n=!!e.prev().prev().is("section.step")&&e.prev().prev(),c=e.parent().find("section.step:first"),u=e.parent().find("section.step:last");switch(r){case 27:console.log("Esc!"),a.preventDefault(),a.stopPropagation(),i.each(function(){var e=jQuery(this);e.hasClass("active")&&(e.removeClass("active"),e.attr("aria-hidden","true"))});break;case 37:case 38:a.preventDefault(),a.stopPropagation(),n?n.focus():u.focus();break;case 39:case 40:a.preventDefault(),a.stopPropagation(),s?s.focus():c.focus();break;case 36:a.preventDefault(),a.stopPropagation(),c.focus();break;case 35:a.preventDefault(),a.stopPropagation(),u.focus();break;case 13:case 32:a.preventDefault(),a.stopPropagation(),o(e,t)}};s.each(function(e){jQuery(this).attr({id:"faq-question-"+e,role:"tab","aria-controls":"faq-answer-"+e,"aria-expanded":"false","aria-selected":"false"})}),i.each(function(e){jQuery(this).attr({id:"faq-answer-"+e,role:"tabpanel","aria-labelledby":"faq-question-"+e,"aria-hidden":"true"})}),r.each(function(){var e=jQuery(this),a=e.find(".js-faq-question");e.attr({role:"tablist","aria-multiselectable":"true"}),a.each(function(e){var t=jQuery(this);t.on("click",function(){o(jQuery(this),a)}),t.on("keydown",function(e){c(jQuery(this),e,a)}),t.on("focus",function(){n(jQuery(this),a)})})})}(document,window);